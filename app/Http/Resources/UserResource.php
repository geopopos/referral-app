<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'role' => $this->role,
            'referral_code' => $this->referral_code,
            'referral_url' => $this->referral_url,
            'payout_method' => $this->payout_method,
            'payout_details' => $this->payout_details,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Computed fields (only include when not in nested relationships)
            'total_leads' => $this->when(!$this->isNested(), function () {
                return $this->leads()->count();
            }),
            'total_commissions' => $this->when(!$this->isNested(), function () {
                return $this->commissions()->sum('amount');
            }),
        ];
    }
    
    /**
     * Check if this resource is being used in a nested relationship
     */
    private function isNested(): bool
    {
        return request()->route() && str_contains(request()->route()->getName(), 'leads.') || str_contains(request()->route()->getName(), 'commissions.');
    }
}
