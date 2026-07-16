<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadFollowupRequest extends FormRequest
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
            'assigned_to'    => ['nullable', 'integer', 'exists:users,id'],
            'follow_up_date' => ['required', 'date'],
            'follow_up_type' => ['nullable', 'string', 'max:50'],
            'notes'          => ['nullable', 'string'],
            'status'         => ['nullable', 'string', 'max:30'],
            'completed_at'   => ['nullable', 'date'],
        ];
    }
}
