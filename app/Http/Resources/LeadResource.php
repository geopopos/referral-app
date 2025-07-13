<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
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
            'status' => $this->status,
            'notes' => $this->notes,
            'referrer_id' => $this->referrer_id,
            'source' => $this->source,
            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,
            'utm_term' => $this->utm_term,
            'utm_content' => $this->utm_content,
            'pipeline_stage' => $this->pipeline_stage,
            'appointment_date' => $this->appointment_date,
            'proposal_sent_date' => $this->proposal_sent_date,
            'close_date' => $this->close_date,
            'monthly_retainer' => $this->monthly_retainer,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relationships
            'referrer' => new UserResource($this->whenLoaded('referrer')),
            'commissions' => CommissionResource::collection($this->whenLoaded('commissions')),
            
            // Computed fields
            'total_commissions' => $this->whenLoaded('commissions', function () {
                return $this->commissions->sum('amount');
            }),
        ];
    }
}
