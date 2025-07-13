<?php

namespace App\Models;

use App\Services\WebhookService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'how_heard_about_us',
        'referral_code',
        'status',
        'appointment_scheduled_at',
        'appointment_completed_at',
        'offer_amount',
        'offer_made_at',
        'sale_amount',
        'sale_closed_at',
        'appointment_notes',
        'offer_notes',
        'sale_notes',
        'lost_reason',
        'commission_override_percentage',
        'commission_override_amount',
        'commission_override_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_scheduled_at' => 'datetime',
        'appointment_completed_at' => 'datetime',
        'offer_made_at' => 'datetime',
        'sale_closed_at' => 'datetime',
        'offer_amount' => 'decimal:2',
        'sale_amount' => 'decimal:2',
        'commission_override_percentage' => 'decimal:2',
        'commission_override_amount' => 'decimal:2',
    ];

    /**
     * Get the user who referred this lead.
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referral_code', 'referral_code');
    }

    /**
     * Get the commissions for this lead.
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Scope to filter leads by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter leads by referral code.
     */
    public function scopeByReferralCode($query, string $referralCode)
    {
        return $query->where('referral_code', $referralCode);
    }

    /**
     * Get the current pipeline stage display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'lead' => 'New Lead',
            'appointment_scheduled' => 'Appointment Scheduled',
            'appointment_completed' => 'Appointment Completed',
            'offer_made' => 'Offer Made',
            'sale' => 'Sale Closed',
            'lost' => 'Lost',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get the next possible statuses for this lead.
     */
    public function getNextStatuses(): array
    {
        return match($this->status) {
            'lead' => ['appointment_scheduled', 'lost'],
            'appointment_scheduled' => ['appointment_completed', 'lost'],
            'appointment_completed' => ['offer_made', 'lost'],
            'offer_made' => ['sale', 'lost'],
            'sale' => [],
            'lost' => [],
            default => []
        };
    }

    /**
     * Move lead to the next status in the pipeline.
     */
    public function moveToStatus(string $status, array $data = []): bool
    {
        if (!in_array($status, $this->getNextStatuses()) && $status !== 'lost') {
            return false;
        }

        $updateData = ['status' => $status];

        // Set timestamps based on status
        switch ($status) {
            case 'appointment_scheduled':
                $updateData['appointment_scheduled_at'] = $data['appointment_scheduled_at'] ?? now();
                break;
            case 'appointment_completed':
                $updateData['appointment_completed_at'] = $data['appointment_completed_at'] ?? now();
                if (isset($data['appointment_notes'])) {
                    $updateData['appointment_notes'] = $data['appointment_notes'];
                }
                break;
            case 'offer_made':
                $updateData['offer_made_at'] = $data['offer_made_at'] ?? now();
                if (isset($data['offer_amount'])) {
                    $updateData['offer_amount'] = $data['offer_amount'];
                }
                if (isset($data['offer_notes'])) {
                    $updateData['offer_notes'] = $data['offer_notes'];
                }
                break;
            case 'sale':
                $updateData['sale_closed_at'] = $data['sale_closed_at'] ?? now();
                if (isset($data['sale_amount'])) {
                    $updateData['sale_amount'] = $data['sale_amount'];
                }
                if (isset($data['sale_notes'])) {
                    $updateData['sale_notes'] = $data['sale_notes'];
                }
                break;
            case 'lost':
                if (isset($data['lost_reason'])) {
                    $updateData['lost_reason'] = $data['lost_reason'];
                }
                break;
        }

        $this->update($updateData);

        // Create commission if lead is marked as sale
        if ($status === 'sale' && $this->referrer) {
            $this->createCommission();
        }

        return true;
    }

    /**
     * Create commission for this lead when it becomes a sale.
     */
    public function createCommission(): void
    {
        if ($this->status !== 'sale' || !$this->referrer || !$this->sale_amount) {
            return;
        }

        // Check if commission already exists
        if ($this->commissions()->exists()) {
            return;
        }

        $commissionSetting = CommissionSetting::getActive();
        if (!$commissionSetting) {
            return;
        }

        // Check if this qualifies for quick close bonus
        $isQuickClose = $commissionSetting->isQuickClose(
            $this->created_at->toDateTime(),
            $this->sale_closed_at->toDateTime()
        );

        // Use override amounts if set, otherwise calculate from settings
        if ($this->commission_override_amount) {
            $commissionAmount = $this->commission_override_amount;
        } elseif ($this->commission_override_percentage) {
            $commissionAmount = $this->sale_amount * ($this->commission_override_percentage / 100);
            if ($isQuickClose) {
                $commissionAmount += $commissionSetting->quick_close_bonus;
            }
        } else {
            $commissionAmount = $commissionSetting->calculateCommission($this->sale_amount, $isQuickClose);
        }

        Commission::create([
            'user_id' => $this->referrer->id,
            'lead_id' => $this->id,
            'type' => 'rev_share',
            'amount' => $commissionAmount,
            'status' => 'pending',
        ]);
    }

    /**
     * Get the commission amount for this lead.
     */
    public function getCommissionAmount(): float
    {
        return $this->commissions()->sum('amount');
    }

    /**
     * Check if this lead qualifies for quick close bonus.
     */
    public function isQuickClose(): bool
    {
        if (!$this->sale_closed_at) {
            return false;
        }

        $commissionSetting = CommissionSetting::getActive();
        if (!$commissionSetting) {
            return false;
        }

        return $commissionSetting->isQuickClose(
            $this->created_at->toDateTime(),
            $this->sale_closed_at->toDateTime()
        );
    }

    /**
     * Get the current value of this lead.
     */
    public function getCurrentValue(): float
    {
        return match($this->status) {
            'sale' => $this->sale_amount ?? 0,
            'offer_made' => $this->offer_amount ?? 0,
            default => 0
        };
    }

    /**
     * Boot the model and set up event listeners for webhooks.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($lead) {
            $webhookService = app(WebhookService::class);
            $webhookService->sendWebhook('lead.created', [
                'lead' => $lead->toArray(),
                'referrer' => $lead->referrer ? $lead->referrer->only(['id', 'name', 'email', 'referral_code']) : null,
            ]);
        });

        static::updated(function ($lead) {
            $webhookService = app(WebhookService::class);
            
            // Send general update webhook
            $webhookService->sendWebhook('lead.updated', [
                'lead' => $lead->toArray(),
                'referrer' => $lead->referrer ? $lead->referrer->only(['id', 'name', 'email', 'referral_code']) : null,
                'changes' => $lead->getChanges(),
            ]);

            // Send specific status change webhook if status changed
            if ($lead->wasChanged('status')) {
                $webhookService->sendWebhook('lead.status_changed', [
                    'lead' => $lead->toArray(),
                    'referrer' => $lead->referrer ? $lead->referrer->only(['id', 'name', 'email', 'referral_code']) : null,
                    'previous_status' => $lead->getOriginal('status'),
                    'new_status' => $lead->status,
                ]);
            }
        });
    }
}
