<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'order_id'           => $this->order_id,
            'department_id'      => $this->department_id,
            'assigned_to'        => $this->assigned_to,
            'task_number'        => $this->task_number,
            'title'              => $this->title,
            'description'        => $this->description,
            'priority'           => $this->priority,
            'planned_start'      => $this->planned_start,
            'planned_end'        => $this->planned_end,
            'actual_start'       => $this->actual_start,
            'actual_end'         => $this->actual_end,
            'planned_time_hours' => $this->planned_time_hours,
            'actual_time_hours'  => $this->actual_time_hours,
            'tat_duration_hours' => $this->tat_duration_hours,
            'status'             => $this->status,
            'created_by'         => $this->created_by,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
