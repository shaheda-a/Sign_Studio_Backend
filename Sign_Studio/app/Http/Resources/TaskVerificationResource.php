<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskVerificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'task_id'     => $this->task_id,
            'verified_by' => $this->verified_by,
            'status'      => $this->status,
            'remarks'     => $this->remarks,
            'verified_at' => $this->verified_at,
            'created_by'  => $this->created_by,
            'created_at'  => $this->created_at,
        ];
    }
}
