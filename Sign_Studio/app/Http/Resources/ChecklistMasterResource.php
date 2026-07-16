<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistMasterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'module'       => $this->module,
            'item_name'    => $this->item_name,
            'is_mandatory' => (int) $this->is_mandatory,
            'is_active'    => (int) $this->is_active,
            'created_by'   => $this->created_by,
            'created_at'   => $this->created_at?->toDateTimeString(),
            'updated_at'   => $this->updated_at?->toDateTimeString(),
            'deleted_at'   => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
