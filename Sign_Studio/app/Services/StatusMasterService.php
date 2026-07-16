<?php

namespace App\Services;

use App\Models\StatusMaster;
use Illuminate\Support\Facades\Auth;

class StatusMasterService
{
    /**
     * Get status listings with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getStatuses(array $filters = [], int $perPage = 15): mixed
    {
        $query = StatusMaster::query();

        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($perPage === -1) {
            return $query->orderBy('sort_order', 'asc')->get();
        }

        return $query->orderBy('sort_order', 'asc')->paginate($perPage);
    }

    /**
     * Create a status.
     *
     * @param array $data
     * @return StatusMaster
     */
    public function createStatus(array $data): StatusMaster
    {
        $data['created_by'] = Auth::id();
        return StatusMaster::create($data);
    }

    /**
     * Get status by ID.
     *
     * @param int $id
     * @return StatusMaster
     */
    public function getStatusById(int $id): StatusMaster
    {
        return StatusMaster::findOrFail($id);
    }

    /**
     * Update a status.
     *
     * @param int $id
     * @param array $data
     * @return StatusMaster
     */
    public function updateStatus(int $id, array $data): StatusMaster
    {
        $status = StatusMaster::findOrFail($id);
        $status->update($data);
        return $status;
    }

    /**
     * Delete a status.
     *
     * @param int $id
     * @return bool
     */
    public function deleteStatus(int $id): bool
    {
        $status = StatusMaster::findOrFail($id);
        return $status->delete();
    }

    /**
     * Restore a deleted status.
     *
     * @param int $id
     * @return StatusMaster
     */
    public function restoreStatus(int $id): StatusMaster
    {
        $status = StatusMaster::withTrashed()->findOrFail($id);
        $status->restore();
        return $status;
    }
}
