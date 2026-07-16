<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lead_id'         => ['required', 'integer', 'exists:leads,id'],
            'customer_id'     => ['required', 'integer', 'exists:customers,id'],
            'token_number'    => ['required', 'string', 'max:50'],
            'amount'          => ['required', 'numeric', 'min:0'],
            'payment_mode'    => ['nullable', 'string', 'max:50'],
            'transaction_ref' => ['nullable', 'string', 'max:200'],
            'status'          => ['nullable', 'string', 'max:30'],
            'received_by'     => ['nullable', 'integer', 'exists:users,id'],
            'received_at'     => ['nullable', 'date'],
        ];
    }
}
