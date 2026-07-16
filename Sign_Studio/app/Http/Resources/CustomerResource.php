<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'branch_id'       => $this->branch_id,
            'branch'          => new BranchResource($this->whenLoaded('branch')),
            'name'            => $this->name,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'alternate_phone' => $this->alternate_phone,
            'gstin'           => $this->gstin,
            'billing_address' => $this->billing_address,
            'billing_city'    => $this->billing_city,
            'billing_state'   => $this->billing_state,
            'billing_pincode' => $this->billing_pincode,
            'is_active'       => (int) $this->is_active,
            'created_by'      => $this->created_by,
            'contacts'        => CustomerContactResource::collection($this->whenLoaded('contacts')),
            'sites'           => CustomerSiteResource::collection($this->whenLoaded('sites')),
            'referrals'       => CustomerReferralResource::collection($this->whenLoaded('referrals')),
            'created_at'      => $this->created_at?->toDateTimeString(),
            'updated_at'      => $this->updated_at?->toDateTimeString(),
            'deleted_at'      => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
