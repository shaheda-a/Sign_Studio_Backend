<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PipelineStageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:200'],
            'code'                => ['nullable', 'string', 'max:50'],
            'sort_order'          => ['nullable', 'integer'],
            'probability_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_active'           => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
