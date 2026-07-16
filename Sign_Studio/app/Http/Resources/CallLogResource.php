<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CallLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'lead_id'          => $this->lead_id,
            'customer_id'      => $this->customer_id,
            'customer_name'    => $this->relationLoaded('customer') ? $this->customer?->name : ($this->customer?->name ?? null),
            'user_id'          => $this->user_id,
            'user_name'        => $this->relationLoaded('user') ? $this->user?->name : ($this->user?->name ?? null),
            'call_type'        => $this->call_type,
            'duration_seconds' => (int) $this->duration_seconds,
            'recording_path'   => $this->recording_path,
            'outcome'          => $this->outcome,
            'notes'            => $this->notes,
            'called_at'        => $this->called_at?->toDateTimeString(),
            'created_by'       => $this->created_by,
            'created_at'       => $this->created_at?->toDateTimeString(),
        ];
    }
}
