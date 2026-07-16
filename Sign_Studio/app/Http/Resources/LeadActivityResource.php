<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadActivityResource extends JsonResource
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
            'user_id'        => $this->user_id,
            'user_name'      => $this->relationLoaded('user') ? $this->user?->name : ($this->user?->name ?? null),
            'type'           => $this->type,
            'description'    => $this->description,
            'outcome'        => $this->outcome,
            'next_follow_up' => $this->next_follow_up?->toDateTimeString(),
            'created_by'     => $this->created_by,
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
            'deleted_at'     => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
