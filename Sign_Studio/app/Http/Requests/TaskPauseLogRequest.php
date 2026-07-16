<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskPauseLogRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id'      => 'required|integer|exists:tasks,id',
            'pause_reason' => 'required|string|max:1000',
        ];
    }
}
