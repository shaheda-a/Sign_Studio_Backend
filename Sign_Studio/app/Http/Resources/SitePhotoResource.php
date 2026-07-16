<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SitePhotoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'site_visit_id' => $this->site_visit_id,
            'file_path'     => $this->file_path,
            'file_type'     => $this->file_type,
            'caption'       => $this->caption,
            'uploaded_at'   => $this->uploaded_at,
            'created_by'    => $this->created_by,
            'created_at'    => $this->created_at,
        ];
    }
}
