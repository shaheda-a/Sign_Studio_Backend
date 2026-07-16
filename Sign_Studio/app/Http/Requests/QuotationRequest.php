<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'lead_id'           => 'required|exists:leads,id',
            'customer_id'       => 'required|exists:customers,id',
            'design_id'         => 'nullable|exists:designs,id',
            'validity_days'     => 'nullable|integer|min:1',
            'terms_conditions'  => 'nullable|string',
            'notes'             => 'nullable|string',
            'status'            => 'nullable|string|in:draft,sent,approved,rejected,expired',
            'discount_amount'   => 'nullable|numeric|min:0',
        ];
    }
}
