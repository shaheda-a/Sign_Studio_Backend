<?php

namespace App\Services;

use App\Models\LeadSource;
use Illuminate\Support\Facades\Auth;

class LeadSourceService
{
    /**
     * Get lead sources with filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getLeadSources(array $filters = [], int $perPage = 15): mixed
    {
        $query = LeadSource::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($perPage === -1) {
            return $query->orderBy('name', 'asc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a lead source.
     *
     * @param array $data
     * @return LeadSource
     */
    public function createLeadSource(array $data): LeadSource
    {
        $data['created_by'] = Auth::id();
        return LeadSource::create($data);
    }

    /**
     * Get lead source by ID.
     *
     * @param int $id
     * @return LeadSource
     */
    public function getLeadSourceById(int $id): LeadSource
    {
        return LeadSource::findOrFail($id);
    }

    /**
     * Update a lead source.
     *
     * @param int $id
     * @param array $data
     * @return LeadSource
     */
    public function updateLeadSource(int $id, array $data): LeadSource
    {
        $source = LeadSource::findOrFail($id);
        $source->update($data);
        return $source;
    }

    /**
     * Delete a lead source.
     *
     * @param int $id
     * @return bool
     */
    public function deleteLeadSource(int $id): bool
    {
        $source = LeadSource::findOrFail($id);
        return $source->delete();
    }

    /**
     * Restore a deleted lead source.
     *
     * @param int $id
     * @return LeadSource
     */
    public function restoreLeadSource(int $id): LeadSource
    {
        $source = LeadSource::withTrashed()->findOrFail($id);
        $source->restore();
        return $source;
    }
}
