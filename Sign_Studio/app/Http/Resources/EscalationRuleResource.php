<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EscalationRuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'module'                => $this->module,
            'trigger_after_hours'   => (int) $this->trigger_after_hours,
            'escalate_to_role_id'   => $this->escalate_to_role_id,
            'escalate_to_role_name' => $this->relationLoaded('escalateToRole') ? $this->escalateToRole?->name : ($this->escalateToRole?->name ?? null),
            'notify_user_id'        => $this->notify_user_id,
            'notify_user_name'      => $this->relationLoaded('notifyUser') ? $this->notifyUser?->name : ($this->notifyUser?->name ?? null),
            'is_active'             => (int) $this->is_active,
            'created_by'            => $this->created_by,
            'created_at'            => $this->created_at?->toDateTimeString(),
            'updated_at'            => $this->updated_at?->toDateTimeString(),
            'deleted_at'            => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
