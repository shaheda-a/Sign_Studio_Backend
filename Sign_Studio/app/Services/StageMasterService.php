<?php

namespace App\Services;

use App\Models\StageMaster;
use Illuminate\Support\Facades\Auth;

class StageMasterService
{
    /**
     * Get stages with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getStages(array $filters = [], int $perPage = 15): mixed
    {
        $query = StageMaster::query();

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
     * Create a stage.
     *
     * @param array $data
     * @return StageMaster
     */
    public function createStage(array $data): StageMaster
    {
        $data['created_by'] = Auth::id();
        return StageMaster::create($data);
    }

    /**
     * Get stage by ID.
     *
     * @param int $id
     * @return StageMaster
     */
    public function getStageById(int $id): StageMaster
    {
        return StageMaster::findOrFail($id);
    }

    /**
     * Update a stage.
     *
     * @param int $id
     * @param array $data
     * @return StageMaster
     */
    public function updateStage(int $id, array $data): StageMaster
    {
        $stage = StageMaster::findOrFail($id);
        $stage->update($data);
        return $stage;
    }

    /**
     * Delete a stage.
     *
     * @param int $id
     * @return bool
     */
    public function deleteStage(int $id): bool
    {
        $stage = StageMaster::findOrFail($id);
        return $stage->delete();
    }

    /**
     * Restore a deleted stage.
     *
     * @param int $id
     * @return StageMaster
     */
    public function restoreStage(int $id): StageMaster
    {
        $stage = StageMaster::withTrashed()->findOrFail($id);
        $stage->restore();
        return $stage;
    }
}
