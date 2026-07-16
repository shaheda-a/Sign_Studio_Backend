<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskEscalationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'task_id'        => $this->task_id,
            'escalated_from' => $this->escalated_from,
            'escalated_to'   => $this->escalated_to,
            'reason'         => $this->reason,
            'level'          => $this->level,
            'status'         => $this->status,
            'resolved_at'    => $this->resolved_at,
            'created_by'     => $this->created_by,
            'created_at'     => $this->created_at,
        ];
    }
}
