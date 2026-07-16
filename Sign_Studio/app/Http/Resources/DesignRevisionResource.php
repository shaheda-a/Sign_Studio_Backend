<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignRevisionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'design_id'       => $this->design_id,
            'revision_number' => $this->revision_number,
            'feedback'        => $this->feedback,
            'requested_by'    => $this->requested_by,
            'due_date'        => $this->due_date,
            'status'          => $this->status,
            'created_by'      => $this->created_by,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
