<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'order_id'           => 'required|exists:orders,id',
            'department_id'      => 'nullable|exists:departments,id',
            'assigned_to'        => 'nullable|exists:users,id',
            'title'              => 'required|string|max:300',
            'description'        => 'nullable|string',
            'priority'           => 'nullable|string|in:low,normal,high,urgent',
            'planned_start'      => 'nullable|date',
            'planned_end'        => 'nullable|date',
            'planned_time_hours' => 'nullable|numeric|min:0',
            'status'             => 'nullable|string|in:pending,in_progress,paused,completed,cancelled',
        ];
    }
}
