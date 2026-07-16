<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PipelineStageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'code'                => $this->code,
            'sort_order'          => (int) $this->sort_order,
            'probability_percent' => (int) $this->probability_percent,
            'is_active'           => (int) $this->is_active,
            'created_by'          => $this->created_by,
            'created_at'          => $this->created_at?->toDateTimeString(),
            'updated_at'          => $this->updated_at?->toDateTimeString(),
            'deleted_at'          => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
