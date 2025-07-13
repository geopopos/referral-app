<?php

namespace App\Models;

use App\Services\WebhookService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'lead_id',
        'type',
        'amount',
        'status',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the user who owns this commission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lead associated with this commission.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Scope to filter commissions by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter commissions by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark commission as paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark commission as approved.
     */
    public function markAsApproved(): void
    {
        $this->update(['status' => 'approved']);
    }

    /**
     * Boot the model and set up event listeners for webhooks.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($commission) {
            $webhookService = app(WebhookService::class);
            $webhookService->sendWebhook('commission.created', [
                'commission' => $commission->toArray(),
                'user' => $commission->user ? $commission->user->only(['id', 'name', 'email', 'referral_code']) : null,
                'lead' => $commission->lead ? $commission->lead->toArray() : null,
            ]);
        });

        static::updated(function ($commission) {
            $webhookService = app(WebhookService::class);
            
            // Send general update webhook
            $webhookService->sendWebhook('commission.updated', [
                'commission' => $commission->toArray(),
                'user' => $commission->user ? $commission->user->only(['id', 'name', 'email', 'referral_code']) : null,
                'lead' => $commission->lead ? $commission->lead->toArray() : null,
                'changes' => $commission->getChanges(),
            ]);

            // Send specific status change webhooks
            if ($commission->wasChanged('status')) {
                $previousStatus = $commission->getOriginal('status');
                $newStatus = $commission->status;

                if ($newStatus === 'approved') {
                    $webhookService->sendWebhook('commission.approved', [
                        'commission' => $commission->toArray(),
                        'user' => $commission->user ? $commission->user->only(['id', 'name', 'email', 'referral_code']) : null,
                        'lead' => $commission->lead ? $commission->lead->toArray() : null,
                        'previous_status' => $previousStatus,
                    ]);
                } elseif ($newStatus === 'paid') {
                    $webhookService->sendWebhook('commission.paid', [
                        'commission' => $commission->toArray(),
                        'user' => $commission->user ? $commission->user->only(['id', 'name', 'email', 'referral_code']) : null,
                        'lead' => $commission->lead ? $commission->lead->toArray() : null,
                        'previous_status' => $previousStatus,
                        'paid_at' => $commission->paid_at,
                    ]);
                }
            }
        });
    }
}
