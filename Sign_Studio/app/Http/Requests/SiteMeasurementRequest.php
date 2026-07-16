<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_visit_id' => 'required|exists:site_visits,id',
            'sign_position' => 'nullable|string|max:200',
            'width'         => 'nullable|numeric|min:0',
            'height'        => 'nullable|numeric|min:0',
            'sq_ft'         => 'nullable|numeric|min:0',
            'depth'         => 'nullable|numeric|min:0',
            'unit'          => 'nullable|string|in:feet,inches,meters,cm',
            'notes'         => 'nullable|string',
        ];
    }
}
