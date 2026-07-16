<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EscalationRuleRequest extends FormRequest
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
            'module'              => ['required', 'string', 'max:100'],
            'trigger_after_hours' => ['required', 'integer', 'min:1'],
            'escalate_to_role_id' => ['required', 'integer', 'exists:roles,id'],
            'notify_user_id'      => ['nullable', 'integer', 'exists:users,id'],
            'is_active'           => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
