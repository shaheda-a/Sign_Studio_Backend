<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskBottleneckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'task_id'          => $this->task_id,
            'bottleneck_type'  => $this->bottleneck_type,
            'description'      => $this->description,
            'identified_by'    => $this->identified_by,
            'resolved_by'      => $this->resolved_by,
            'resolved_at'      => $this->resolved_at,
            'created_by'       => $this->created_by,
            'created_at'       => $this->created_at,
        ];
    }
}
