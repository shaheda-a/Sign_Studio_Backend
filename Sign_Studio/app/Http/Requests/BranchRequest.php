<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
        $branchId = $this->route('branch') ? $this->route('branch') : null;

        return [
            'name'      => ['required', 'string', 'max:200'],
            'code'      => [
                'required',
                'string',
                'max:20',
                $branchId ? 'unique:branches,code,' . $branchId : 'unique:branches,code',
            ],
            'address'   => ['nullable', 'string'],
            'gstin'     => ['nullable', 'string', 'max:20'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:100'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
