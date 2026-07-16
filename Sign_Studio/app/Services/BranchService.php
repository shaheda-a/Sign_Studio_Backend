<?php

namespace App\Services;

use App\Models\Branch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class BranchService
{
    /**
     * Get list of all branches with optional filtering and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getBranches(array $filters = [], int $perPage = 15): mixed
    {
        $query = Branch::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['with_trashed']) && $filters['with_trashed'] === 'true') {
            $query->withTrashed();
        }

        // Return paginated or all depending on perPage parameter
        if ($perPage === -1) {
            return $query->orderBy('name', 'asc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a new branch.
     *
     * @param array $data
     * @return Branch
     */
    public function createBranch(array $data): Branch
    {
        $data['created_by'] = Auth::id();
        return Branch::create($data);
    }

    /**
     * Get branch by ID.
     *
     * @param int $id
     * @return Branch
     */
    public function getBranchById(int $id): Branch
    {
        return Branch::findOrFail($id);
    }

    /**
     * Update an existing branch.
     *
     * @param int $id
     * @param array $data
     * @return Branch
     */
    public function updateBranch(int $id, array $data): Branch
    {
        $branch = Branch::findOrFail($id);
        $branch->update($data);
        return $branch;
    }

    /**
     * Soft delete a branch.
     *
     * @param int $id
     * @return bool
     */
    public function deleteBranch(int $id): bool
    {
        $branch = Branch::findOrFail($id);
        return $branch->delete();
    }

    /**
     * Restore a soft deleted branch.
     *
     * @param int $id
     * @return Branch
     */
    public function restoreBranch(int $id): Branch
    {
        $branch = Branch::withTrashed()->findOrFail($id);
        $branch->restore();
        return $branch;
    }
}
