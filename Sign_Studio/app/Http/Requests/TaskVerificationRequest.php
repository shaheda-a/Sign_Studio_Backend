<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskVerificationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id' => 'required|integer|exists:tasks,id',
            'status'  => 'required|string|in:approved,rejected,pending',
            'remarks' => 'nullable|string|max:2000',
        ];
    }
}
