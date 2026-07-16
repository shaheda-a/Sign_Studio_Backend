<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusMasterRequest extends FormRequest
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
            'module'      => ['required', 'string', 'max:100'],
            'status_name' => ['required', 'string', 'max:200'],
            'status_code' => ['required', 'string', 'max:50'],
            'color'       => ['nullable', 'string', 'max:20'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
