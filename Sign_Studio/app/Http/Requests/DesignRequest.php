<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lead_id'     => 'required|exists:leads,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title'       => 'required|string|max:300',
            'status'      => 'nullable|string|in:pending,in_progress,revision,approved,rejected',
            'due_date'    => 'nullable|date',
        ];
    }
}
