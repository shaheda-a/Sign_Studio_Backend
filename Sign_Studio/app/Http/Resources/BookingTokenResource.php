<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'lead_id'          => $this->lead_id,
            'customer_id'      => $this->customer_id,
            'customer_name'    => $this->relationLoaded('customer') ? $this->customer?->name : ($this->customer?->name ?? null),
            'token_number'     => $this->token_number,
            'amount'           => (double) $this->amount,
            'payment_mode'     => $this->payment_mode,
            'transaction_ref'  => $this->transaction_ref,
            'status'           => $this->status,
            'received_by'      => $this->received_by,
            'received_by_name' => $this->relationLoaded('receivedByUser') ? $this->receivedByUser?->name : ($this->receivedByUser?->name ?? null),
            'received_at'      => $this->received_at?->toDateTimeString(),
            'created_by'       => $this->created_by,
            'created_at'       => $this->created_at?->toDateTimeString(),
            'updated_at'       => $this->updated_at?->toDateTimeString(),
            'deleted_at'       => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
