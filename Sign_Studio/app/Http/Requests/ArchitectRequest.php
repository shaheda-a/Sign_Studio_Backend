<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArchitectRequest extends FormRequest
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
            'name'                  => ['required', 'string', 'max:200'],
            'firm_name'             => ['nullable', 'string', 'max:200'],
            'email'                 => ['nullable', 'email', 'max:150'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'address'               => ['nullable', 'string'],
            'commission_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_active'             => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
