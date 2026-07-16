<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionStageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'production_plan_id' => $this->production_plan_id,
            'stage_master_id'    => $this->stage_master_id,
            'stage_name'         => $this->stage_name,
            'sort_order'         => $this->sort_order,
            'planned_start'      => $this->planned_start,
            'planned_end'        => $this->planned_end,
            'actual_start'       => $this->actual_start,
            'actual_end'         => $this->actual_end,
            'status'             => $this->status,
            'assigned_to'        => $this->assigned_to,
            'created_by'         => $this->created_by,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
