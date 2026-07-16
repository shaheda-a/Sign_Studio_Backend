<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobCardRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'order_id'     => 'required|exists:orders,id',
            'qr_code_data' => 'nullable|string',
            'qr_code_path' => 'nullable|string|max:500',
            'notes'        => 'nullable|string',
        ];
    }
}
