<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionScoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'production_plan_id' => 'required|integer|exists:production_plans,id',
            'quality_score'      => 'required|integer|min:0|max:100',
            'efficiency_score'   => 'required|integer|min:0|max:100',
            'on_time_score'      => 'required|integer|min:0|max:100',
        ];
    }
}
