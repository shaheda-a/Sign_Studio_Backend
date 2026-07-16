<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadActivityRequest extends FormRequest
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
            'lead_id'        => ['required', 'integer', 'exists:leads,id'],
            'user_id'        => ['nullable', 'integer', 'exists:users,id'],
            'type'           => ['required', 'string', 'max:50'],
            'description'    => ['nullable', 'string'],
            'outcome'        => ['nullable', 'string', 'max:100'],
            'next_follow_up' => ['nullable', 'date'],
        ];
    }
}
