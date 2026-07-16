<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'lead_id'       => $this->lead_id,
            'assigned_to'   => $this->assigned_to,
            'design_number' => $this->design_number,
            'title'         => $this->title,
            'status'        => $this->status,
            'is_locked'     => $this->is_locked,
            'due_date'      => $this->due_date,
            'locked_at'     => $this->locked_at,
            'locked_by'     => $this->locked_by,
            'created_by'    => $this->created_by,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
