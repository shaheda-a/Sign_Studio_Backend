<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
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
            'customer_id'         => ['required', 'integer', 'exists:customers,id'],
            'source_id'           => ['nullable', 'integer', 'exists:lead_sources,id'],
            'assigned_to'         => ['nullable', 'integer', 'exists:users,id'],
            'architect_id'        => ['nullable', 'integer', 'exists:architects,id'],
            'contractor_id'       => ['nullable', 'integer', 'exists:contractors,id'],
            'pipeline_stage_id'   => ['nullable', 'integer', 'exists:pipeline_stages,id'],
            'lead_number'         => ['nullable', 'string', 'max:50'],
            'title'               => ['required', 'string', 'max:300'],
            'status'              => ['nullable', 'string', 'max:50'],
            'lead_score'          => ['nullable', 'integer'],
            'estimated_value'     => ['nullable', 'numeric'],
            'expected_close_date' => ['nullable', 'date'],
            'lost_reason'         => ['nullable', 'string'],
        ];
    }
}
