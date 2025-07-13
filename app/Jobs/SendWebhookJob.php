<?php

namespace App\Jobs;

use App\Models\WebhookLog;
use App\Services\WebhookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1; // We handle retries manually
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $webhookLogId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(WebhookService $webhookService): void
    {
        $webhookLog = WebhookLog::find($this->webhookLogId);

        if (!$webhookLog) {
            Log::warning('Webhook log not found', ['webhook_log_id' => $this->webhookLogId]);
            return;
        }

        // Check if webhook setting is still active
        if (!$webhookLog->webhookSetting || !$webhookLog->webhookSetting->is_active) {
            $webhookLog->markAsFailed('Webhook setting is inactive or deleted');
            return;
        }

        // Deliver the webhook
        $webhookService->deliverWebhook($webhookLog);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $webhookLog = WebhookLog::find($this->webhookLogId);
        
        if ($webhookLog) {
            $webhookLog->markAsFailed('Job failed: ' . $exception->getMessage());
        }

        Log::error('Webhook job failed', [
            'webhook_log_id' => $this->webhookLogId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
