<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'user_name'     => $this->relationLoaded('user') ? $this->user?->name : ($this->user?->name ?? null),
            'ip_address'    => $this->ip_address,
            'device'        => $this->device,
            'status'        => $this->status,
            'logged_in_at'  => $this->logged_in_at?->toDateTimeString(),
            'logged_out_at' => $this->logged_out_at?->toDateTimeString(),
        ];
    }
}
