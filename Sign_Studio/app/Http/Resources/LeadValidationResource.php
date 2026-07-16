<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadValidationResource extends JsonResource
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
            'lead_id'        => $this->lead_id,
            'validated_by'   => $this->validated_by,
            'validator_name' => $this->relationLoaded('validator') ? $this->validator?->name : ($this->validator?->name ?? null),
            'status'         => $this->status,
            'remarks'        => $this->remarks,
            'validated_at'   => $this->validated_at?->toDateTimeString(),
            'created_by'      => $this->created_by,
            'created_at'     => $this->created_at?->toDateTimeString(),
        ];
    }
}
