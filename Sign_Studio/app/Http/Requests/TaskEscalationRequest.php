<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskEscalationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id'        => 'required|integer|exists:tasks,id',
            'escalated_from' => 'nullable|integer|exists:users,id',
            'escalated_to'   => 'required|integer|exists:users,id',
            'reason'         => 'required|string|max:2000',
            'level'          => 'required|integer|min:1|max:5',
            'status'         => 'nullable|string|in:open,resolved',
        ];
    }
}
