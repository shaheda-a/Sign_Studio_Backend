<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadScoreRequest extends FormRequest
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
            'lead_id'   => ['required', 'integer', 'exists:leads,id'],
            'criteria'  => ['required', 'string', 'max:200'],
            'score'     => ['required', 'integer'],
            'scored_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
