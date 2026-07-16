<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SitePhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_visit_id' => 'required|exists:site_visits,id',
            'file_path'     => 'required|string|max:500',
            'file_type'     => 'nullable|string|max:50',
            'caption'       => 'nullable|string',
            'uploaded_at'   => 'nullable|date',
        ];
    }
}
