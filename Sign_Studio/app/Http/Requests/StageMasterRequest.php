<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StageMasterRequest extends FormRequest
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
            'module'     => ['required', 'string', 'max:100'],
            'stage_name' => ['required', 'string', 'max:200'],
            'stage_code' => ['required', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_active'  => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
