<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'webhook_setting_id',
        'event_type',
        'webhook_id',
        'url',
        'payload',
        'headers',
        'http_status',
        'response_body',
        'response_headers',
        'status',
        'attempt_number',
        'max_attempts',
        'next_retry_at',
        'error_message',
        'response_time',
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'response_headers' => 'array',
        'http_status' => 'integer',
        'attempt_number' => 'integer',
        'max_attempts' => 'integer',
        'next_retry_at' => 'datetime',
        'response_time' => 'decimal:3',
    ];

    /**
     * Available webhook statuses
     */
    public const STATUSES = [
        'pending' => 'Pending',
        'success' => 'Success',
        'failed' => 'Failed',
        'retrying' => 'Retrying',
    ];

    /**
     * Get the webhook setting that owns this log
     */
    public function webhookSetting(): BelongsTo
    {
        return $this->belongsTo(WebhookSetting::class);
    }

    /**
     * Scope to get logs for a specific event type
     */
    public function scopeForEvent($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope to get logs with a specific status
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get failed logs that need retry
     */
    public function scopeNeedsRetry($query)
    {
        return $query->where('status', 'retrying')
                    ->where('next_retry_at', '<=', now())
                    ->where('attempt_number', '<', function($subQuery) {
                        $subQuery->select('max_attempts')
                                ->from('webhook_logs as wl2')
                                ->whereColumn('wl2.id', 'webhook_logs.id');
                    });
    }

    /**
     * Check if this log can be retried
     */
    public function canRetry(): bool
    {
        return $this->status === 'retrying' && 
               $this->attempt_number < $this->max_attempts &&
               $this->next_retry_at <= now();
    }

    /**
     * Mark this log as successful
     */
    public function markAsSuccess(int $httpStatus, ?string $responseBody = null, ?array $responseHeaders = null, ?float $responseTime = null): void
    {
        $this->update([
            'status' => 'success',
            'http_status' => $httpStatus,
            'response_body' => $responseBody,
            'response_headers' => $responseHeaders,
            'response_time' => $responseTime,
            'error_message' => null,
            'next_retry_at' => null,
        ]);
    }

    /**
     * Mark this log as failed and schedule retry if applicable
     */
    public function markAsFailed(string $errorMessage, ?int $httpStatus = null, ?string $responseBody = null, ?array $responseHeaders = null, ?float $responseTime = null): void
    {
        $this->update([
            'status' => $this->attempt_number < $this->max_attempts ? 'retrying' : 'failed',
            'http_status' => $httpStatus,
            'response_body' => $responseBody,
            'response_headers' => $responseHeaders,
            'response_time' => $responseTime,
            'error_message' => $errorMessage,
            'next_retry_at' => $this->attempt_number < $this->max_attempts 
                ? now()->addSeconds($this->webhookSetting->getNextRetryDelay($this->attempt_number + 1))
                : null,
        ]);
    }

    /**
     * Increment attempt number for retry
     */
    public function incrementAttempt(): void
    {
        $this->increment('attempt_number');
    }

    /**
     * Get formatted response time
     */
    public function getFormattedResponseTimeAttribute(): string
    {
        if (!$this->response_time) {
            return 'N/A';
        }

        if ($this->response_time < 1) {
            return round($this->response_time * 1000) . 'ms';
        }

        return round($this->response_time, 2) . 's';
    }

    /**
     * Get status badge color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'success' => 'green',
            'failed' => 'red',
            'retrying' => 'yellow',
            'pending' => 'blue',
            default => 'gray',
        };
    }

    /**
     * Check if the webhook was successful (2xx status code)
     */
    public function isSuccessful(): bool
    {
        return $this->http_status >= 200 && $this->http_status < 300;
    }
}
