<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'quotation_id'   => 'nullable|exists:quotations,id',
            'customer_id'    => 'required|exists:customers,id',
            'branch_id'      => 'required|exists:branches,id',
            'delivery_date'  => 'nullable|date',
            'status'         => 'nullable|string|in:pending,confirmed,in_production,dispatched,delivered,cancelled',
            'advance_received' => 'nullable|numeric|min:0',
        ];
    }
}
