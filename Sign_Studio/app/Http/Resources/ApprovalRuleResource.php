<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovalRuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'module'             => $this->module,
            'action'             => $this->action,
            'approver_role_id'   => $this->approver_role_id,
            'approver_role_name' => $this->relationLoaded('approverRole') ? $this->approverRole?->name : ($this->approverRole?->name ?? null),
            'is_active'          => (int) $this->is_active,
            'created_by'         => $this->created_by,
            'created_at'         => $this->created_at?->toDateTimeString(),
            'updated_at'         => $this->updated_at?->toDateTimeString(),
            'deleted_at'         => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
