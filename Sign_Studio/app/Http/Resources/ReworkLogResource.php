<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReworkLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'production_plan_id' => $this->production_plan_id,
            'stage_id'           => $this->stage_id,
            'reason'             => $this->reason,
            'cost_incurred'      => $this->cost_incurred,
            'time_hours'         => $this->time_hours,
            'assigned_to'        => $this->assigned_to,
            'status'             => $this->status,
            'created_by'         => $this->created_by,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
