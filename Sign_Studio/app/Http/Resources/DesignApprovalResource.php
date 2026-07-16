<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignApprovalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'design_id'               => $this->design_id,
            'revision_id'             => $this->revision_id,
            'type'                    => $this->type,
            'status'                  => $this->status,
            'customer_approved'       => $this->customer_approved,
            'approved_by'             => $this->approved_by,
            'customer_signature_path' => $this->customer_signature_path,
            'remarks'                 => $this->remarks,
            'approved_at'             => $this->approved_at,
            'created_by'              => $this->created_by,
            'created_at'              => $this->created_at,
        ];
    }
}
