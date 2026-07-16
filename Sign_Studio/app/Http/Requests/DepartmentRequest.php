<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'branch_id'    => ['required', 'integer', 'exists:branches,id'],
            'name'         => ['required', 'string', 'max:200'],
            'code'         => ['nullable', 'string', 'max:20'],
            'head_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'is_active'    => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
