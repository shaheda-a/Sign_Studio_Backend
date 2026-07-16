<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadStatusHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'lead_id'      => $this->lead_id,
            'from_status'  => $this->from_status,
            'to_status'    => $this->to_status,
            'changed_by'   => $this->changed_by,
            'changer_name' => $this->relationLoaded('changer') ? $this->changer?->name : ($this->changer?->name ?? null),
            'notes'        => $this->notes,
            'created_at'   => $this->created_at?->toDateTimeString(),
        ];
    }
}
