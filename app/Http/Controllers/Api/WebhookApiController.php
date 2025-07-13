<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebhookSetting;
use App\Models\WebhookLog;
use App\Services\WebhookService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class WebhookApiController extends Controller
{
    public function __construct(
        private WebhookService $webhookService
    ) {}

    /**
     * Get webhook settings
     */
    public function getSettings(): JsonResponse
    {
        $webhookSetting = WebhookSetting::first();

        if (!$webhookSetting) {
            return response()->json([
                'success' => false,
                'message' => 'No webhook settings configured',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $webhookSetting->id,
                'url' => $webhookSetting->url,
                'auth_type' => $webhookSetting->auth_type,
                'enabled_events' => $webhookSetting->enabled_events,
                'is_active' => $webhookSetting->is_active,
                'max_retry_attempts' => $webhookSetting->max_retry_attempts,
                'retry_delays' => $webhookSetting->retry_delays,
                'last_successful_delivery' => $webhookSetting->last_successful_delivery,
                'last_failed_delivery' => $webhookSetting->last_failed_delivery,
                'consecutive_failures' => $webhookSetting->consecutive_failures,
                'available_events' => WebhookSetting::AVAILABLE_EVENTS,
                'auth_types' => WebhookSetting::AUTH_TYPES,
            ]
        ]);
    }

    /**
     * Update webhook settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url|max:255',
            'auth_type' => ['required', Rule::in(array_keys(WebhookSetting::AUTH_TYPES))],
            'auth_credentials' => 'nullable|array',
            'enabled_events' => 'required|array',
            'enabled_events.*' => Rule::in(array_keys(WebhookSetting::AVAILABLE_EVENTS)),
            'is_active' => 'boolean',
            'max_retry_attempts' => 'integer|min:1|max:10',
            'retry_delays' => 'array',
            'retry_delays.*' => 'integer|min:1',
            'secret_key' => 'nullable|string|max:255',
        ]);

        $webhookSetting = WebhookSetting::first();

        if (!$webhookSetting) {
            $webhookSetting = new WebhookSetting();
        }

        $webhookSetting->fill($request->only([
            'url',
            'auth_type',
            'auth_credentials',
            'enabled_events',
            'is_active',
            'max_retry_attempts',
            'retry_delays',
            'secret_key',
        ]));

        $webhookSetting->save();

        return response()->json([
            'success' => true,
            'message' => 'Webhook settings updated successfully',
            'data' => $webhookSetting->makeHidden(['auth_credentials', 'secret_key'])
        ]);
    }

    /**
     * Test webhook delivery
     */
    public function testWebhook(): JsonResponse
    {
        $webhookSetting = WebhookSetting::first();

        if (!$webhookSetting) {
            return response()->json([
                'success' => false,
                'message' => 'No webhook settings configured'
            ], 404);
        }

        if (!$webhookSetting->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Webhook is not active'
            ], 400);
        }

        $result = $this->webhookService->sendTestWebhook($webhookSetting);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success'] ? 'Test webhook sent successfully' : 'Test webhook failed',
            'data' => $result
        ]);
    }

    /**
     * Get webhook logs
     */
    public function getLogs(Request $request): JsonResponse
    {
        $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
            'event_type' => 'nullable|string',
            'status' => ['nullable', Rule::in(array_keys(WebhookLog::STATUSES))],
            'webhook_setting_id' => 'nullable|integer|exists:webhook_settings,id',
        ]);

        $query = WebhookLog::with('webhookSetting')
            ->orderBy('created_at', 'desc');

        if ($request->event_type) {
            $query->forEvent($request->event_type);
        }

        if ($request->status) {
            $query->withStatus($request->status);
        }

        if ($request->webhook_setting_id) {
            $query->where('webhook_setting_id', $request->webhook_setting_id);
        }

        $logs = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }

    /**
     * Get webhook statistics
     */
    public function getStats(Request $request): JsonResponse
    {
        $request->validate([
            'days' => 'integer|min:1|max:365',
        ]);

        $webhookSetting = WebhookSetting::first();

        if (!$webhookSetting) {
            return response()->json([
                'success' => false,
                'message' => 'No webhook settings configured'
            ], 404);
        }

        $days = $request->days ?? 30;
        $stats = $this->webhookService->getWebhookStats($webhookSetting, $days);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Retry a specific webhook
     */
    public function retryWebhook(WebhookLog $webhookLog): JsonResponse
    {
        if (!$webhookLog->canRetry()) {
            return response()->json([
                'success' => false,
                'message' => 'This webhook cannot be retried'
            ], 400);
        }

        $webhookLog->incrementAttempt();
        $this->webhookService->deliverWebhook($webhookLog);

        return response()->json([
            'success' => true,
            'message' => 'Webhook retry initiated',
            'data' => $webhookLog->fresh()
        ]);
    }

    /**
     * Retry all failed webhooks
     */
    public function retryAllFailed(): JsonResponse
    {
        $retriedCount = $this->webhookService->retryFailedWebhooks();

        return response()->json([
            'success' => true,
            'message' => "Queued {$retriedCount} webhook(s) for retry",
            'data' => ['retried_count' => $retriedCount]
        ]);
    }

    /**
     * Delete webhook logs older than specified days
     */
    public function cleanupLogs(Request $request): JsonResponse
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $cutoffDate = now()->subDays($request->days);
        $deletedCount = WebhookLog::where('created_at', '<', $cutoffDate)->delete();

        return response()->json([
            'success' => true,
            'message' => "Deleted {$deletedCount} webhook log(s) older than {$request->days} days",
            'data' => ['deleted_count' => $deletedCount]
        ]);
    }

    /**
     * Get webhook log details
     */
    public function getLogDetails(WebhookLog $webhookLog): JsonResponse
    {
        $webhookLog->load('webhookSetting');

        return response()->json([
            'success' => true,
            'data' => $webhookLog
        ]);
    }
}
