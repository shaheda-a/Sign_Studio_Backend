<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'lead_id'            => $this->lead_id,
            'assigned_from'      => $this->assigned_from,
            'assigned_from_name' => $this->relationLoaded('assignedFromUser') ? $this->assignedFromUser?->name : ($this->assignedFromUser?->name ?? null),
            'assigned_to'        => $this->assigned_to,
            'assigned_to_name'   => $this->relationLoaded('assignedToUser') ? $this->assignedToUser?->name : ($this->assignedToUser?->name ?? null),
            'reason'             => $this->reason,
            'assigned_at'        => $this->assigned_at?->toDateTimeString(),
            'created_by'         => $this->created_by,
        ];
    }
}
