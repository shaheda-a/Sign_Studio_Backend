<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskBottleneckRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id'         => 'required|integer|exists:tasks,id',
            'bottleneck_type' => 'required|string|max:100',
            'description'     => 'required|string|max:2000',
        ];
    }
}
