<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskPauseLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'task_id'      => $this->task_id,
            'paused_by'    => $this->paused_by,
            'pause_reason' => $this->pause_reason,
            'paused_at'    => $this->paused_at,
            'resumed_at'   => $this->resumed_at,
            'created_at'   => $this->created_at,
        ];
    }
}
