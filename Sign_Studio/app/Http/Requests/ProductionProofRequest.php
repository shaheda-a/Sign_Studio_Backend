<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionProofRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'production_plan_id' => 'required|integer|exists:production_plans,id',
            'stage_id'           => 'nullable|integer|exists:production_stages,id',
            'file_path'          => 'required|string|max:500',
            'file_type'          => 'nullable|string|max:50',
            'notes'              => 'nullable|string|max:1000',
        ];
    }
}
