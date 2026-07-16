<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskDelayRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id'          => 'required|integer|exists:tasks,id',
            'delay_reason'     => 'required|string|max:2000',
            'escalation_level' => 'nullable|integer|min:1|max:5',
            'escalated_to'     => 'nullable|integer|exists:users,id',
        ];
    }
}
