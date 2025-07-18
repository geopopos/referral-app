<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\WebhookService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'referral_code',
        'payout_method',
        'payout_details',
        'phone',
        'notes',
        // Onboarding fields
        'website_url',
        'paypal_email',
        'payment_method',
        'current_client_count',
        'wants_advertising_help',
        'business_type',
        'years_in_business',
        'average_project_value',
        'primary_services',
        'biggest_challenge',
        'preferred_communication',
        'timezone',
        'referral_source',
        'profile_completed',
        'onboarding_step',
        'onboarding_completed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'onboarding_completed_at' => 'datetime',
            'wants_advertising_help' => 'boolean',
            'profile_completed' => 'boolean',
        ];
    }

    /**
     * Boot the model and generate referral code on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = $user->generateReferralCode();
            }
        });

        static::created(function ($user) {
            $webhookService = app(WebhookService::class);
            
            // Prepare webhook data
            $webhookData = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'referral_code' => $user->referral_code,
                    'created_at' => $user->created_at->toISOString(),
                ]
            ];

            // Add referrer information if user was referred
            if ($user->referral_source && $user->referral_source !== 'direct') {
                $webhookData['referral_source'] = $user->referral_source;
            }

            $webhookService->sendWebhook('user.created', $webhookData);
        });
    }

    /**
     * Generate a unique referral code.
     */
    private function generateReferralCode(): string
    {
        do {
            $code = 'ref_' . Str::random(8);
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Get the leads referred by this user.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'referral_code', 'referral_code');
    }

    /**
     * Get the commissions for this user.
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a partner.
     */
    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }

    /**
     * Get the referral URL for this user.
     */
    public function getReferralUrlAttribute(): string
    {
        return url('/referral?ref=' . $this->referral_code);
    }

    /**
     * Get total commission amount by status.
     * Only counts commissions for leads with "sale" status.
     */
    public function getTotalCommissions(string $status = null): float
    {
        $query = $this->commissions()
            ->whereHas('lead', function ($leadQuery) {
                $leadQuery->where('status', 'sale');
            });
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->sum('amount');
    }

    /**
     * Check if user has completed onboarding.
     */
    public function hasCompletedOnboarding(): bool
    {
        return $this->profile_completed;
    }

    /**
     * Get onboarding completion percentage.
     */
    public function getOnboardingProgress(): int
    {
        $totalSteps = 4;
        return min(100, ($this->onboarding_step / $totalSteps) * 100);
    }

    /**
     * Check if user needs to complete onboarding.
     */
    public function needsOnboarding(): bool
    {
        return $this->isPartner() && !$this->hasCompletedOnboarding();
    }

    /**
     * Mark onboarding as completed.
     */
    public function completeOnboarding(): void
    {
        $this->update([
            'profile_completed' => true,
            'onboarding_step' => 4,
            'onboarding_completed_at' => now(),
        ]);
    }

    /**
     * Get the next onboarding step.
     */
    public function getNextOnboardingStep(): int
    {
        return min(4, $this->onboarding_step + 1);
    }
}
