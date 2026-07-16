<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'design_id'               => 'required|exists:designs,id',
            'revision_id'             => 'nullable|exists:design_revisions,id',
            'type'                    => 'nullable|string|max:50',
            'status'                  => 'required|string|in:pending,approved,rejected',
            'customer_approved'       => 'nullable|boolean',
            'approved_by'             => 'nullable|exists:users,id',
            'customer_signature_path' => 'nullable|string|max:500',
            'remarks'                 => 'nullable|string',
            'approved_at'             => 'nullable|date',
        ];
    }
}
