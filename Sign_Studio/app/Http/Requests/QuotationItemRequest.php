<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'quotation_id'     => 'required|exists:quotations,id',
            'description'      => 'nullable|string',
            'qty'              => 'required|numeric|min:0.01',
            'uom'              => 'nullable|string|max:30',
            'unit_price'       => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax_rate'         => 'nullable|numeric|min:0|max:100',
            'sort_order'       => 'nullable|integer|min:0',
        ];
    }
}
