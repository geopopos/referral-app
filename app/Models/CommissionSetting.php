<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'commission_percentage',
        'quick_close_bonus',
        'quick_close_days',
        'is_active',
        'description',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'commission_percentage' => 'decimal:2',
        'quick_close_bonus' => 'decimal:2',
        'quick_close_days' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created this commission setting.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the currently active commission setting.
     */
    public static function getActive(): ?self
    {
        return self::where('is_active', true)->latest()->first();
    }

    /**
     * Calculate commission amount for a given sale amount.
     */
    public function calculateCommission(float $saleAmount, bool $isQuickClose = false): float
    {
        $commission = $saleAmount * ($this->commission_percentage / 100);
        
        if ($isQuickClose) {
            $commission += $this->quick_close_bonus;
        }
        
        return round($commission, 2);
    }

    /**
     * Check if a lead qualifies for quick close bonus.
     */
    public function isQuickClose(\DateTime $leadCreated, \DateTime $saleClosed): bool
    {
        $daysDiff = $leadCreated->diff($saleClosed)->days;
        return $daysDiff <= $this->quick_close_days;
    }

    /**
     * Scope to get only active settings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Deactivate all other commission settings when this one is activated.
     */
    public function activate(): void
    {
        // Deactivate all other settings
        self::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this setting
        $this->update(['is_active' => true]);
    }
}
