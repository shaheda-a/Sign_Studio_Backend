<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskProofRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id'   => 'required|integer|exists:tasks,id',
            'file_path' => 'required|string|max:500',
            'file_type' => 'nullable|string|max:50',
            'notes'     => 'nullable|string|max:1000',
        ];
    }
}
