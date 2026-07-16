<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSiteRequest extends FormRequest
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
            'customer_id'    => ['required', 'integer', 'exists:customers,id'],
            'site_name'      => ['required', 'string', 'max:200'],
            'site_address'   => ['nullable', 'string'],
            'site_city'      => ['nullable', 'string', 'max:100'],
            'site_state'     => ['nullable', 'string', 'max:100'],
            'site_pincode'   => ['nullable', 'string', 'max:20'],
            'contact_person' => ['nullable', 'string', 'max:200'],
            'contact_phone'  => ['nullable', 'string', 'max:20'],
        ];
    }
}
