<?php

namespace App\Services;

use App\Models\ChecklistMaster;
use Illuminate\Support\Facades\Auth;

class ChecklistMasterService
{
    /**
     * Get checklist items with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getChecklists(array $filters = [], int $perPage = 15): mixed
    {
        $query = ChecklistMaster::query();

        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'asc')->get();
        }

        return $query->orderBy('id', 'asc')->paginate($perPage);
    }

    /**
     * Create a checklist item.
     *
     * @param array $data
     * @return ChecklistMaster
     */
    public function createChecklist(array $data): ChecklistMaster
    {
        $data['created_by'] = Auth::id();
        return ChecklistMaster::create($data);
    }

    /**
     * Get checklist item by ID.
     *
     * @param int $id
     * @return ChecklistMaster
     */
    public function getChecklistById(int $id): ChecklistMaster
    {
        return ChecklistMaster::findOrFail($id);
    }

    /**
     * Update a checklist item.
     *
     * @param int $id
     * @param array $data
     * @return ChecklistMaster
     */
    public function updateChecklist(int $id, array $data): ChecklistMaster
    {
        $checklist = ChecklistMaster::findOrFail($id);
        $checklist->update($data);
        return $checklist;
    }

    /**
     * Delete a checklist item.
     *
     * @param int $id
     * @return bool
     */
    public function deleteChecklist(int $id): bool
    {
        $checklist = ChecklistMaster::findOrFail($id);
        return $checklist->delete();
    }

    /**
     * Restore a deleted checklist item.
     *
     * @param int $id
     * @return ChecklistMaster
     */
    public function restoreChecklist(int $id): ChecklistMaster
    {
        $checklist = ChecklistMaster::withTrashed()->findOrFail($id);
        $checklist->restore();
        return $checklist;
    }
}
