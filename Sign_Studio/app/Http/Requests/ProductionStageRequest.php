<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionStageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'production_plan_id' => 'required|integer|exists:production_plans,id',
            'stage_master_id'    => 'nullable|integer|exists:stage_masters,id',
            'stage_name'         => 'required|string|max:200',
            'sort_order'         => 'required|integer|min:1',
            'planned_start'      => 'nullable|date',
            'planned_end'        => 'nullable|date|after_or_equal:planned_start',
            'actual_start'       => 'nullable|date',
            'actual_end'         => 'nullable|date|after_or_equal:actual_start',
            'status'             => 'nullable|string|in:pending,in_progress,completed,on_hold',
            'assigned_to'        => 'nullable|integer|exists:users,id',
        ];
    }
}
