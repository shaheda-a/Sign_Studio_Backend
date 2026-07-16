<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadPipelineHistoryResource extends JsonResource
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
            'lead_id'         => $this->lead_id,
            'from_stage_id'   => $this->from_stage_id,
            'from_stage_name' => $this->relationLoaded('fromStage') ? $this->fromStage?->name : ($this->fromStage?->name ?? null),
            'to_stage_id'     => $this->to_stage_id,
            'to_stage_name'   => $this->relationLoaded('toStage') ? $this->toStage?->name : ($this->toStage?->name ?? null),
            'changed_by'      => $this->changed_by,
            'changer_name'    => $this->relationLoaded('changer') ? $this->changer?->name : ($this->changer?->name ?? null),
            'notes'           => $this->notes,
            'created_at'      => $this->created_at?->toDateTimeString(),
        ];
    }
}
