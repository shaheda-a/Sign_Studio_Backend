<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerReferralResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'customer_id'          => $this->customer_id,
            'referrer_name'        => $this->relationLoaded('referrer') ? $this->referrer?->name : ($this->referrer?->name ?? null),
            'referred_customer_id' => $this->referred_customer_id,
            'referred_name'        => $this->relationLoaded('referred') ? $this->referred?->name : ($this->referred?->name ?? null),
            'referral_date'        => $this->referral_date?->toDateString(),
            'status'               => $this->status,
            'points_earned'        => (int) $this->points_earned,
            'created_by'           => $this->created_by,
            'created_at'           => $this->created_at?->toDateTimeString(),
            'updated_at'           => $this->updated_at?->toDateTimeString(),
            'deleted_at'           => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
