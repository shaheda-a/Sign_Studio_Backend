<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user') : null;

        $rules = [
            'branch_id'       => ['nullable', 'integer', 'exists:branches,id'],
            'department_id'   => ['nullable', 'integer', 'exists:departments,id'],
            'employee_code'   => [
                'nullable',
                'string',
                'max:30',
                $userId ? 'unique:users,employee_code,' . $userId : 'unique:users,employee_code',
            ],
            'name'            => ['required', 'string', 'max:200'],
            'email'           => [
                'required',
                'email',
                'max:150',
                $userId ? 'unique:users,email,' . $userId : 'unique:users,email',
            ],
            'phone'           => ['nullable', 'string', 'max:20'],
            'designation'     => ['nullable', 'string', 'max:100'],
            'date_of_joining' => ['nullable', 'date_format:Y-m-d'],
            'is_active'       => ['nullable', 'integer', 'in:0,1'],
            'roles'           => ['nullable', 'array'],
            'roles.*'         => ['string', 'exists:roles,name'],
        ];

        // Password is required on create, optional on update
        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'string', 'min:6', 'max:255'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:6', 'max:255'];
        }

        return $rules;
    }
}
