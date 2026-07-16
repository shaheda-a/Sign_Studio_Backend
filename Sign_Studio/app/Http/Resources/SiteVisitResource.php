<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteVisitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'lead_id'          => $this->lead_id,
            'customer_site_id' => $this->customer_site_id,
            'assigned_to'      => $this->assigned_to,
            'visit_number'     => $this->visit_number,
            'scheduled_at'     => $this->scheduled_at,
            'check_in_at'      => $this->check_in_at,
            'check_out_at'     => $this->check_out_at,
            'check_in_gps'     => $this->check_in_gps,
            'check_out_gps'    => $this->check_out_gps,
            'site_readiness'   => $this->site_readiness,
            'status'           => $this->status,
            'remarks'          => $this->remarks,
            'created_by'       => $this->created_by,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
