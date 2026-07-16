<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'quotation_id'         => $this->quotation_id,
            'customer_id'          => $this->customer_id,
            'branch_id'            => $this->branch_id,
            'order_number'         => $this->order_number,
            'total_amount'         => $this->total_amount,
            'advance_received'     => $this->advance_received,
            'balance_amount'       => $this->balance_amount,
            'delivery_date'        => $this->delivery_date,
            'status'               => $this->status,
            'is_commercial_locked' => $this->is_commercial_locked,
            'commercial_locked_at' => $this->commercial_locked_at,
            'commercial_locked_by' => $this->commercial_locked_by,
            'created_by'           => $this->created_by,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
