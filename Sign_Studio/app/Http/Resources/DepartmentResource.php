<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'branch_id'    => $this->branch_id,
            'branch'       => new BranchResource($this->whenLoaded('branch')),
            'name'         => $this->name,
            'code'         => $this->code,
            'head_user_id' => $this->head_user_id,
            'head_user'    => new UserResource($this->whenLoaded('headUser')),
            'is_active'    => (int) $this->is_active,
            'created_by'   => $this->created_by,
            'created_at'   => $this->created_at?->toDateTimeString(),
            'updated_at'   => $this->updated_at?->toDateTimeString(),
            'deleted_at'   => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
