<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class DepartmentService
{
    /**
     * Get list of all departments with optional filtering and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getDepartments(array $filters = [], int $perPage = 15): mixed
    {
        $query = Department::query()->with(['branch']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['with_trashed']) && $filters['with_trashed'] === 'true') {
            $query->withTrashed();
        }

        if ($perPage === -1) {
            return $query->orderBy('name', 'asc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a new department.
     *
     * @param array $data
     * @return Department
     */
    public function createDepartment(array $data): Department
    {
        $data['created_by'] = Auth::id();
        return Department::create($data);
    }

    /**
     * Get department by ID.
     *
     * @param int $id
     * @return Department
     */
    public function getDepartmentById(int $id): Department
    {
        return Department::with(['branch', 'headUser'])->findOrFail($id);
    }

    /**
     * Update an existing department.
     *
     * @param int $id
     * @param array $data
     * @return Department
     */
    public function updateDepartment(int $id, array $data): Department
    {
        $department = Department::findOrFail($id);
        $department->update($data);
        return $department;
    }

    /**
     * Soft delete a department.
     *
     * @param int $id
     * @return bool
     */
    public function deleteDepartment(int $id): bool
    {
        $department = Department::findOrFail($id);
        return $department->delete();
    }

    /**
     * Restore a soft deleted department.
     *
     * @param int $id
     * @return Department
     */
    public function restoreDepartment(int $id): Department
    {
        $department = Department::withTrashed()->findOrFail($id);
        $department->restore();
        return $department;
    }
}
