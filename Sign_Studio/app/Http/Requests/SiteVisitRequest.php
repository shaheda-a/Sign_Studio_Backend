<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lead_id'          => 'required|exists:leads,id',
            'customer_site_id' => 'nullable|exists:customer_sites,id',
            'assigned_to'      => 'nullable|exists:users,id',
            'scheduled_at'     => 'nullable|date',
            'check_in_at'      => 'nullable|date',
            'check_out_at'     => 'nullable|date|after_or_equal:check_in_at',
            'check_in_gps'     => 'nullable|string|max:100',
            'check_out_gps'    => 'nullable|string|max:100',
            'site_readiness'   => 'nullable|string|max:50',
            'status'           => 'nullable|string|in:scheduled,in_progress,completed,cancelled',
            'remarks'          => 'nullable|string',
        ];
    }
}
