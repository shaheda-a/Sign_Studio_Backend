<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'quotation_id'     => $this->quotation_id,
            'description'      => $this->description,
            'qty'              => $this->qty,
            'uom'              => $this->uom,
            'unit_price'       => $this->unit_price,
            'discount_percent' => $this->discount_percent,
            'tax_rate'         => $this->tax_rate,
            'tax_amount'       => $this->tax_amount,
            'total'            => $this->total,
            'sort_order'       => $this->sort_order,
            'created_by'       => $this->created_by,
            'created_at'       => $this->created_at,
        ];
    }
}
