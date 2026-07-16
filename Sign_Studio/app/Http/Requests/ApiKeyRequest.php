<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiKeyRequest extends FormRequest
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
            'name'        => ['required', 'string', 'max:200'],
            'permissions' => ['nullable', 'array'],
            'expires_at'  => ['nullable', 'date_format:Y-m-d H:i:s'],
            'is_active'   => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
