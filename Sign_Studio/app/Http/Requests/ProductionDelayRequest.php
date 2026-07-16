<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionDelayRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'production_plan_id' => 'required|integer|exists:production_plans,id',
            'stage_id'           => 'nullable|integer|exists:production_stages,id',
            'delay_reason'       => 'required|string|max:2000',
            'delay_hours'        => 'required|numeric|min:0',
        ];
    }
}
