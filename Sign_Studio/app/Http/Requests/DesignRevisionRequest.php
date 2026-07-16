<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignRevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'design_id'    => 'required|exists:designs,id',
            'feedback'     => 'nullable|string',
            'requested_by' => 'nullable|exists:users,id',
            'due_date'     => 'nullable|date',
            'status'       => 'nullable|string|in:pending,in_progress,completed',
        ];
    }
}
