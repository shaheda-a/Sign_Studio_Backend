<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerReferralRequest extends FormRequest
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
            'customer_id'          => ['required', 'integer', 'exists:customers,id'],
            'referred_customer_id' => ['required', 'integer', 'exists:customers,id', 'different:customer_id'],
            'referral_date'        => ['nullable', 'date'],
            'status'               => ['nullable', 'string', 'max:50'],
            'points_earned'        => ['nullable', 'integer', 'min:0'],
        ];
    }
}
