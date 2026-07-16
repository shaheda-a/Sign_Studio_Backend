<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
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
            'user_id'        => $this->user_id,
            'user_name'      => $this->relationLoaded('user') ? $this->user?->name : ($this->user?->name ?? null),
            'event'          => $this->event,
            'auditable_type' => $this->auditable_type,
            'auditable_id'   => (int) $this->auditable_id,
            'old_values'     => $this->old_values,
            'new_values'     => $this->new_values,
            'ip_address'     => $this->ip_address,
            'user_agent'     => $this->user_agent,
            'created_at'     => $this->created_at,
        ];
    }
}
