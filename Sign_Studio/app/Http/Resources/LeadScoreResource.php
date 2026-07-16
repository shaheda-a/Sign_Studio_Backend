<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadScoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'lead_id'     => $this->lead_id,
            'criteria'    => $this->criteria,
            'score'       => (int) $this->score,
            'scored_by'   => $this->scored_by,
            'scorer_name' => $this->relationLoaded('scoringUser') ? $this->scoringUser?->name : ($this->scoringUser?->name ?? null),
            'created_by'  => $this->created_by,
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
