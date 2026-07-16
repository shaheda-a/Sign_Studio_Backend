<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArchitectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'firm_name'             => $this->firm_name,
            'email'                 => $this->email,
            'phone'                 => $this->phone,
            'address'               => $this->address,
            'commission_percentage' => (double) $this->commission_percentage,
            'is_active'             => (int) $this->is_active,
            'created_by'            => $this->created_by,
            'created_at'            => $this->created_at?->toDateTimeString(),
            'updated_at'            => $this->updated_at?->toDateTimeString(),
            'deleted_at'            => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
