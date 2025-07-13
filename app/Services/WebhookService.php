<?php

namespace App\Services;

use App\Models\WebhookSetting;
use App\Models\WebhookLog;
use App\Jobs\SendWebhookJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * Send a webhook for a specific event
     */
    public function sendWebhook(string $eventType, array $data): void
    {
        $webhookSetting = WebhookSetting::where('is_active', true)->first();

        if (!$webhookSetting || !$webhookSetting->isEventEnabled($eventType)) {
            return;
        }

        $payload = $this->buildPayload($eventType, $data);
        $webhookId = Str::uuid()->toString();

        // Create webhook log entry
        $webhookLog = WebhookLog::create([
            'webhook_setting_id' => $webhookSetting->id,
            'event_type' => $eventType,
            'webhook_id' => $webhookId,
            'url' => $webhookSetting->url,
            'payload' => $payload,
            'status' => 'pending',
            'attempt_number' => 1,
            'max_attempts' => $webhookSetting->max_retry_attempts,
        ]);

        // Dispatch webhook job
        SendWebhookJob::dispatch($webhookLog->id);
    }

    /**
     * Actually send the webhook HTTP request
     */
    public function deliverWebhook(WebhookLog $webhookLog): void
    {
        $webhookSetting = $webhookLog->webhookSetting;
        $payload = json_encode($webhookLog->payload);
        
        // Prepare headers
        $headers = array_merge([
            'Content-Type' => 'application/json',
            'User-Agent' => 'VolumeUp-Webhook/1.0',
            'X-Webhook-ID' => $webhookLog->webhook_id,
            'X-Webhook-Event' => $webhookLog->event_type,
            'X-Webhook-Timestamp' => now()->toISOString(),
        ], $webhookSetting->getAuthHeaders());

        // Add HMAC signature if secret key is configured
        if ($signature = $webhookSetting->generateSignature($payload)) {
            $headers['X-Webhook-Signature'] = 'sha256=' . $signature;
        }

        $startTime = microtime(true);

        try {
            // Validate URL to prevent SSRF attacks
            if (!$this->isValidWebhookUrl($webhookSetting->url)) {
                throw new \Exception('Invalid webhook URL');
            }

            $response = Http::timeout(30)
                ->withHeaders($headers)
                ->post($webhookSetting->url, $webhookLog->payload);

            $responseTime = microtime(true) - $startTime;

            if ($response->successful()) {
                $webhookLog->markAsSuccess(
                    $response->status(),
                    $response->body(),
                    $response->headers(),
                    $responseTime
                );
                $webhookSetting->recordSuccess();
                
                Log::info('Webhook delivered successfully', [
                    'webhook_id' => $webhookLog->webhook_id,
                    'event_type' => $webhookLog->event_type,
                    'url' => $webhookSetting->url,
                    'status' => $response->status(),
                ]);
            } else {
                throw new \Exception('HTTP ' . $response->status() . ': ' . $response->body());
            }

        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            
            $webhookLog->markAsFailed(
                $e->getMessage(),
                isset($response) ? $response->status() : null,
                isset($response) ? $response->body() : null,
                isset($response) ? $response->headers() : null,
                $responseTime
            );

            $webhookSetting->recordFailure();

            Log::error('Webhook delivery failed', [
                'webhook_id' => $webhookLog->webhook_id,
                'event_type' => $webhookLog->event_type,
                'url' => $webhookSetting->url,
                'error' => $e->getMessage(),
                'attempt' => $webhookLog->attempt_number,
            ]);

            // Schedule retry if applicable
            if ($webhookSetting->shouldRetry($webhookLog->attempt_number)) {
                $retryDelay = $webhookSetting->getNextRetryDelay($webhookLog->attempt_number + 1);
                SendWebhookJob::dispatch($webhookLog->id)->delay(now()->addSeconds($retryDelay));
            }
        }
    }

    /**
     * Build the webhook payload
     */
    private function buildPayload(string $eventType, array $data): array
    {
        return [
            'event' => $eventType,
            'timestamp' => now()->toISOString(),
            'data' => $data,
            'webhook_id' => Str::uuid()->toString(),
        ];
    }

    /**
     * Validate webhook URL to prevent SSRF attacks
     */
    private function isValidWebhookUrl(string $url): bool
    {
        // Parse URL
        $parsed = parse_url($url);
        
        if (!$parsed || !isset($parsed['scheme']) || !isset($parsed['host'])) {
            return false;
        }

        // Only allow HTTP and HTTPS
        if (!in_array($parsed['scheme'], ['http', 'https'])) {
            return false;
        }

        // Resolve hostname to IP
        $ip = gethostbyname($parsed['host']);
        
        // Block private/local IP ranges
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }

        return true;
    }

    /**
     * Send a test webhook
     */
    public function sendTestWebhook(WebhookSetting $webhookSetting): array
    {
        $testPayload = [
            'event' => 'webhook.test',
            'timestamp' => now()->toISOString(),
            'data' => [
                'message' => 'This is a test webhook from Volume Up Agency',
                'test' => true,
            ],
            'webhook_id' => Str::uuid()->toString(),
        ];

        $webhookLog = WebhookLog::create([
            'webhook_setting_id' => $webhookSetting->id,
            'event_type' => 'webhook.test',
            'webhook_id' => $testPayload['webhook_id'],
            'url' => $webhookSetting->url,
            'payload' => $testPayload,
            'status' => 'pending',
            'attempt_number' => 1,
            'max_attempts' => 1, // No retries for test webhooks
        ]);

        $this->deliverWebhook($webhookLog);

        return [
            'success' => $webhookLog->status === 'success',
            'status' => $webhookLog->status,
            'http_status' => $webhookLog->http_status,
            'response_time' => $webhookLog->formatted_response_time,
            'error_message' => $webhookLog->error_message,
            'webhook_id' => $webhookLog->webhook_id,
        ];
    }

    /**
     * Get webhook statistics
     */
    public function getWebhookStats(WebhookSetting $webhookSetting, int $days = 30): array
    {
        $logs = $webhookSetting->logs()
            ->where('created_at', '>=', now()->subDays($days))
            ->get();

        $total = $logs->count();
        $successful = $logs->where('status', 'success')->count();
        $failed = $logs->where('status', 'failed')->count();
        $pending = $logs->where('status', 'pending')->count();
        $retrying = $logs->where('status', 'retrying')->count();

        return [
            'total_deliveries' => $total,
            'successful_deliveries' => $successful,
            'failed_deliveries' => $failed,
            'pending_deliveries' => $pending,
            'retrying_deliveries' => $retrying,
            'success_rate' => $total > 0 ? round(($successful / $total) * 100, 2) : 0,
            'average_response_time' => $logs->where('response_time', '>', 0)->avg('response_time'),
            'last_successful_delivery' => $webhookSetting->last_successful_delivery,
            'last_failed_delivery' => $webhookSetting->last_failed_delivery,
            'consecutive_failures' => $webhookSetting->consecutive_failures,
        ];
    }

    /**
     * Retry failed webhooks
     */
    public function retryFailedWebhooks(): int
    {
        $logsToRetry = WebhookLog::needsRetry()->get();
        
        foreach ($logsToRetry as $log) {
            $log->incrementAttempt();
            SendWebhookJob::dispatch($log->id);
        }

        return $logsToRetry->count();
    }
}
