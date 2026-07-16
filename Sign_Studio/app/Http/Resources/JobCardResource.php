<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'order_id'        => $this->order_id,
            'job_card_number' => $this->job_card_number,
            'qr_code_data'    => $this->qr_code_data,
            'qr_code_path'    => $this->qr_code_path,
            'is_scope_locked' => $this->is_scope_locked,
            'scope_locked_at' => $this->scope_locked_at,
            'scope_locked_by' => $this->scope_locked_by,
            'notes'           => $this->notes,
            'created_by'      => $this->created_by,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
