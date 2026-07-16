<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadFollowupResource extends JsonResource
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
            'assigned_to'      => $this->assigned_to,
            'assigned_to_name' => $this->relationLoaded('assignedUser') ? $this->assignedUser?->name : ($this->assignedUser?->name ?? null),
            'follow_up_date'   => $this->follow_up_date?->toDateTimeString(),
            'follow_up_type'   => $this->follow_up_type,
            'notes'            => $this->notes,
            'status'           => $this->status,
            'completed_at'     => $this->completed_at?->toDateTimeString(),
            'created_by'       => $this->created_by,
            'created_at'       => $this->created_at?->toDateTimeString(),
            'updated_at'       => $this->updated_at?->toDateTimeString(),
            'deleted_at'       => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
