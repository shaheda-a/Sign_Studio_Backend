<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'lead_id'          => $this->lead_id,
            'customer_id'      => $this->customer_id,
            'design_id'        => $this->design_id,
            'quote_number'     => $this->quote_number,
            'version'          => $this->version,
            'sub_total'        => $this->sub_total,
            'discount_amount'  => $this->discount_amount,
            'tax_amount'       => $this->tax_amount,
            'grand_total'      => $this->grand_total,
            'validity_days'    => $this->validity_days,
            'terms_conditions' => $this->terms_conditions,
            'notes'            => $this->notes,
            'status'           => $this->status,
            'sent_at'          => $this->sent_at,
            'approved_at'      => $this->approved_at,
            'created_by'       => $this->created_by,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
