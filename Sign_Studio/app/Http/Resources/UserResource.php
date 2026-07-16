<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'branch_id'       => $this->branch_id,
            'branch'          => new BranchResource($this->whenLoaded('branch')),
            'department_id'   => $this->department_id,
            'department'      => new DepartmentResource($this->whenLoaded('department')),
            'employee_code'   => $this->employee_code,
            'name'            => $this->name,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'designation'     => $this->designation,
            'date_of_joining' => $this->date_of_joining?->toDateString(),
            'is_active'       => (int) $this->is_active,
            'created_by'      => $this->created_by,
            'roles'           => $this->relationLoaded('roles') ? $this->roles->pluck('name') : $this->getRoleNames(),
            'permissions'     => $this->relationLoaded('permissions') ? $this->permissions->pluck('name') : $this->getAllPermissions()->pluck('name'),
            'created_at'      => $this->created_at?->toDateTimeString(),
            'updated_at'      => $this->updated_at?->toDateTimeString(),
            'deleted_at'      => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
