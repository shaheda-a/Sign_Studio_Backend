<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskDelayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'task_id'          => $this->task_id,
            'delay_reason'     => $this->delay_reason,
            'escalation_level' => $this->escalation_level,
            'escalated_to'     => $this->escalated_to,
            'created_by'       => $this->created_by,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
