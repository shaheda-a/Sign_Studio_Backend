<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionPlanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'order_id'    => 'required|exists:orders,id',
            'job_card_id' => 'nullable|exists:job_cards,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => 'nullable|string|in:planned,in_progress,on_hold,completed,cancelled',
            'notes'       => 'nullable|string',
        ];
    }
}
