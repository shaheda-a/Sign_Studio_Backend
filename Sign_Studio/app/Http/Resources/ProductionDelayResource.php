<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionDelayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'production_plan_id' => $this->production_plan_id,
            'stage_id'           => $this->stage_id,
            'delay_reason'       => $this->delay_reason,
            'delay_hours'        => $this->delay_hours,
            'reported_by'        => $this->reported_by,
            'created_by'         => $this->created_by,
            'created_at'         => $this->created_at,
        ];
    }
}
