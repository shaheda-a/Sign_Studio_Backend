<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionProofResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'production_plan_id' => $this->production_plan_id,
            'stage_id'           => $this->stage_id,
            'file_path'          => $this->file_path,
            'file_type'          => $this->file_type,
            'notes'              => $this->notes,
            'uploaded_by'        => $this->uploaded_by,
            'created_by'         => $this->created_by,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
