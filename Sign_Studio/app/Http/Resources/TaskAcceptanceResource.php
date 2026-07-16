<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskAcceptanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'task_id'          => $this->task_id,
            'user_id'          => $this->user_id,
            'status'           => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'responded_at'     => $this->responded_at,
            'created_at'       => $this->created_at,
        ];
    }
}
