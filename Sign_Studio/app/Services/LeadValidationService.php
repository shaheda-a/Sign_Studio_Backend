<?php

namespace App\Services;

use App\Models\LeadValidation;
use Illuminate\Support\Facades\Auth;

class LeadValidationService
{
    /**
     * Get formal validation items.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getValidations(array $filters = [], int $perPage = 15): mixed
    {
        $query = LeadValidation::query()->with(['lead', 'validator']);

        if (!empty($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'desc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create validation record.
     *
     * @param array $data
     * @return LeadValidation
     */
    public function createValidation(array $data): LeadValidation
    {
        $data['created_by'] = Auth::id();
        if (empty($data['validated_by'])) {
            $data['validated_by'] = Auth::id();
        }
        if (empty($data['validated_at'])) {
            $data['validated_at'] = now();
        }
        return LeadValidation::create($data);
    }

    /**
     * Get validation log by ID.
     *
     * @param int $id
     * @return LeadValidation
     */
    public function getValidationById(int $id): LeadValidation
    {
        return LeadValidation::with(['lead', 'validator'])->findOrFail($id);
    }

    /**
     * Delete validation log.
     *
     * @param int $id
     * @return bool
     */
    public function deleteValidation(int $id): bool
    {
        $validation = LeadValidation::findOrFail($id);
        return $validation->delete();
    }
}
