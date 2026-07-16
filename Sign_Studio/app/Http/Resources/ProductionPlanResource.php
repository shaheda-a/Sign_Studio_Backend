<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'order_id'     => $this->order_id,
            'job_card_id'  => $this->job_card_id,
            'plan_number'  => $this->plan_number,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date,
            'actual_start' => $this->actual_start,
            'actual_end'   => $this->actual_end,
            'status'       => $this->status,
            'notes'        => $this->notes,
            'created_by'   => $this->created_by,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
