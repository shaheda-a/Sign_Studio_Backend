<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionScoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'production_plan_id' => $this->production_plan_id,
            'quality_score'      => $this->quality_score,
            'efficiency_score'   => $this->efficiency_score,
            'on_time_score'      => $this->on_time_score,
            'overall_score'      => $this->overall_score,
            'scored_by'          => $this->scored_by,
            'created_by'         => $this->created_by,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
