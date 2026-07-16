<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReworkLogRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'production_plan_id' => 'required|integer|exists:production_plans,id',
            'stage_id'           => 'nullable|integer|exists:production_stages,id',
            'reason'             => 'required|string|max:2000',
            'cost_incurred'      => 'nullable|numeric|min:0',
            'time_hours'         => 'nullable|numeric|min:0',
            'assigned_to'        => 'nullable|integer|exists:users,id',
            'status'             => 'nullable|string|in:pending,in_progress,completed',
        ];
    }
}
