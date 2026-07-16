<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteMeasurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'site_visit_id' => $this->site_visit_id,
            'sign_position' => $this->sign_position,
            'width'         => $this->width,
            'height'        => $this->height,
            'sq_ft'         => $this->sq_ft,
            'depth'         => $this->depth,
            'unit'          => $this->unit,
            'notes'         => $this->notes,
            'created_by'    => $this->created_by,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
