<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QcChecklistRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'production_plan_id' => 'required|integer|exists:production_plans,id',
            'item_name'          => 'required|string|max:300',
            'is_passed'          => 'required|boolean',
            'rework_required'    => 'required|boolean',
            'notes'              => 'nullable|string|max:1000',
            'photo_path'         => 'nullable|string|max:500',
        ];
    }
}
