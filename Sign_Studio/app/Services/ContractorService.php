<?php

namespace App\Services;

use App\Models\Contractor;
use Illuminate\Support\Facades\Auth;

class ContractorService
{
    /**
     * Get contractors with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getContractors(array $filters = [], int $perPage = 15): mixed
    {
        $query = Contractor::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('firm_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
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
     * Create a contractor.
     *
     * @param array $data
     * @return Contractor
     */
    public function createContractor(array $data): Contractor
    {
        $data['created_by'] = Auth::id();
        return Contractor::create($data);
    }

    /**
     * Get contractor by ID.
     *
     * @param int $id
     * @return Contractor
     */
    public function getContractorById(int $id): Contractor
    {
        return Contractor::findOrFail($id);
    }

    /**
     * Update a contractor.
     *
     * @param int $id
     * @param array $data
     * @return Contractor
     */
    public function updateContractor(int $id, array $data): Contractor
    {
        $contractor = Contractor::findOrFail($id);
        $contractor->update($data);
        return $contractor;
    }

    /**
     * Delete a contractor.
     *
     * @param int $id
     * @return bool
     */
    public function deleteContractor(int $id): bool
    {
        $contractor = Contractor::findOrFail($id);
        return $contractor->delete();
    }

    /**
     * Restore a deleted contractor.
     *
     * @param int $id
     * @return Contractor
     */
    public function restoreContractor(int $id): Contractor
    {
        $contractor = Contractor::withTrashed()->findOrFail($id);
        $contractor->restore();
        return $contractor;
    }
}
