<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QcChecklistResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'production_plan_id' => $this->production_plan_id,
            'item_name'          => $this->item_name,
            'is_passed'          => $this->is_passed,
            'rework_required'    => $this->rework_required,
            'inspected_by'       => $this->inspected_by,
            'notes'              => $this->notes,
            'photo_path'         => $this->photo_path,
            'created_by'         => $this->created_by,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
