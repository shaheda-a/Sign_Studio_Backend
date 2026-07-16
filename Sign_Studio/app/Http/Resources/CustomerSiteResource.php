<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerSiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'customer_id'    => $this->customer_id,
            'site_name'      => $this->site_name,
            'site_address'   => $this->site_address,
            'site_city'      => $this->site_city,
            'site_state'     => $this->site_state,
            'site_pincode'   => $this->site_pincode,
            'contact_person' => $this->contact_person,
            'contact_phone'  => $this->contact_phone,
            'created_by'     => $this->created_by,
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
            'deleted_at'     => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
