<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebhookSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'priority',
        'url',
        'auth_type',
        'auth_credentials',
        'enabled_events',
        'is_active',
        'max_retry_attempts',
        'retry_delays',
        'secret_key',
        'last_successful_delivery',
        'last_failed_delivery',
        'consecutive_failures',
    ];

    protected $casts = [
        'auth_credentials' => 'encrypted:json',
        'enabled_events' => 'array',
        'retry_delays' => 'array',
        'is_active' => 'boolean',
        'last_successful_delivery' => 'datetime',
        'last_failed_delivery' => 'datetime',
        'consecutive_failures' => 'integer',
        'max_retry_attempts' => 'integer',
    ];

    /**
     * Available webhook events
     */
    public const AVAILABLE_EVENTS = [
        'lead.created' => 'Lead Created',
        'lead.updated' => 'Lead Updated',
        'lead.status_changed' => 'Lead Status Changed',
        'commission.created' => 'Commission Created',
        'commission.updated' => 'Commission Updated',
        'commission.approved' => 'Commission Approved',
        'commission.paid' => 'Commission Paid',
    ];

    /**
     * Available authentication types
     */
    public const AUTH_TYPES = [
        'none' => 'No Authentication',
        'basic' => 'Basic Authentication',
        'bearer' => 'Bearer Token',
        'custom' => 'Custom Headers',
    ];

    /**
     * Get the webhook logs for this setting
     */
    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    /**
     * Check if a specific event is enabled
     */
    public function isEventEnabled(string $event): bool
    {
        return in_array($event, $this->enabled_events ?? []);
    }

    /**
     * Get the authentication headers for the webhook
     */
    public function getAuthHeaders(): array
    {
        $headers = [];

        switch ($this->auth_type) {
            case 'basic':
                if (isset($this->auth_credentials['username']) && isset($this->auth_credentials['password'])) {
                    $credentials = base64_encode($this->auth_credentials['username'] . ':' . $this->auth_credentials['password']);
                    $headers['Authorization'] = 'Basic ' . $credentials;
                }
                break;

            case 'bearer':
                if (isset($this->auth_credentials['token'])) {
                    $headers['Authorization'] = 'Bearer ' . $this->auth_credentials['token'];
                }
                break;

            case 'custom':
                if (isset($this->auth_credentials['headers']) && is_array($this->auth_credentials['headers'])) {
                    $headers = array_merge($headers, $this->auth_credentials['headers']);
                }
                break;
        }

        return $headers;
    }

    /**
     * Generate HMAC signature for webhook payload
     */
    public function generateSignature(string $payload): ?string
    {
        if (!$this->secret_key) {
            return null;
        }

        return hash_hmac('sha256', $payload, $this->secret_key);
    }

    /**
     * Record a successful webhook delivery
     */
    public function recordSuccess(): void
    {
        $this->update([
            'last_successful_delivery' => now(),
            'consecutive_failures' => 0,
        ]);
    }

    /**
     * Record a failed webhook delivery
     */
    public function recordFailure(): void
    {
        $this->increment('consecutive_failures');
        $this->update(['last_failed_delivery' => now()]);

        // Disable webhook after too many consecutive failures
        if ($this->consecutive_failures >= 10) {
            $this->update(['is_active' => false]);
        }
    }

    /**
     * Get the next retry delay in seconds
     */
    public function getNextRetryDelay(int $attemptNumber): int
    {
        $delays = $this->retry_delays ?? [60, 300, 900]; // Default: 1min, 5min, 15min
        $index = min($attemptNumber - 1, count($delays) - 1);
        
        return $delays[$index] ?? 900; // Default to 15 minutes if not specified
    }

    /**
     * Check if webhook should be retried
     */
    public function shouldRetry(int $attemptNumber): bool
    {
        return $this->is_active && $attemptNumber < $this->max_retry_attempts;
    }

    /**
     * Scope to get active webhook settings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get webhook settings for a specific event
     */
    public function scopeForEvent($query, string $event)
    {
        return $query->whereJsonContains('enabled_events', $event);
    }

    /**
     * Scope to order by priority
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority')->orderBy('name');
    }

    /**
     * Get all active webhook settings for a specific event, ordered by priority
     */
    public static function getActiveForEvent(string $event)
    {
        return static::active()
            ->forEvent($event)
            ->byPriority()
            ->get();
    }

    /**
     * Get webhook settings grouped by enabled events
     */
    public static function getGroupedByEvents()
    {
        $webhooks = static::active()->get();
        $grouped = [];

        foreach (static::AVAILABLE_EVENTS as $eventKey => $eventLabel) {
            $grouped[$eventKey] = [
                'label' => $eventLabel,
                'webhooks' => $webhooks->filter(function ($webhook) use ($eventKey) {
                    return $webhook->isEventEnabled($eventKey);
                })->values()
            ];
        }

        return $grouped;
    }

    /**
     * Validate webhook configuration
     */
    public function validate(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors[] = 'Name is required';
        }

        if (empty($this->url)) {
            $errors[] = 'URL is required';
        } elseif (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            $errors[] = 'URL must be a valid URL';
        }

        if (empty($this->enabled_events)) {
            $errors[] = 'At least one event must be enabled';
        }

        if ($this->auth_type === 'basic') {
            if (empty($this->auth_credentials['username']) || empty($this->auth_credentials['password'])) {
                $errors[] = 'Username and password are required for basic authentication';
            }
        } elseif ($this->auth_type === 'bearer') {
            if (empty($this->auth_credentials['token'])) {
                $errors[] = 'Token is required for bearer authentication';
            }
        }

        return $errors;
    }
}
