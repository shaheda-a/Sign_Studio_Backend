<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallLogRequest extends FormRequest
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
            'lead_id'          => ['nullable', 'integer', 'exists:leads,id'],
            'customer_id'      => ['nullable', 'integer', 'exists:customers,id'],
            'user_id'          => ['nullable', 'integer', 'exists:users,id'],
            'call_type'        => ['nullable', 'string', 'max:20'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'recording_path'   => ['nullable', 'string', 'max:500'],
            'outcome'          => ['nullable', 'string', 'max:100'],
            'notes'            => ['nullable', 'string'],
            'called_at'        => ['nullable', 'date'],
        ];
    }
}
