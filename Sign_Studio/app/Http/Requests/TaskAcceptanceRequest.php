<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskAcceptanceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id'          => 'required|integer|exists:tasks,id',
            'user_id'          => 'nullable|integer|exists:users,id',
            'status'           => 'required|string|in:accepted,rejected',
            'rejection_reason' => 'nullable|string|max:1000|required_if:status,rejected',
            'responded_at'     => 'nullable|date',
        ];
    }
}
