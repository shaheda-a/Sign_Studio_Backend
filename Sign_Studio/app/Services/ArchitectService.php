<?php

namespace App\Services;

use App\Models\Architect;
use Illuminate\Support\Facades\Auth;

class ArchitectService
{
    /**
     * Get architects with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getArchitects(array $filters = [], int $perPage = 15): mixed
    {
        $query = Architect::query();

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
     * Create an architect.
     *
     * @param array $data
     * @return Architect
     */
    public function createArchitect(array $data): Architect
    {
        $data['created_by'] = Auth::id();
        return Architect::create($data);
    }

    /**
     * Get architect by ID.
     *
     * @param int $id
     * @return Architect
     */
    public function getArchitectById(int $id): Architect
    {
        return Architect::findOrFail($id);
    }

    /**
     * Update an architect.
     *
     * @param int $id
     * @param array $data
     * @return Architect
     */
    public function updateArchitect(int $id, array $data): Architect
    {
        $architect = Architect::findOrFail($id);
        $architect->update($data);
        return $architect;
    }

    /**
     * Delete an architect.
     *
     * @param int $id
     * @return bool
     */
    public function deleteArchitect(int $id): bool
    {
        $architect = Architect::findOrFail($id);
        return $architect->delete();
    }

    /**
     * Restore a deleted architect.
     *
     * @param int $id
     * @return Architect
     */
    public function restoreArchitect(int $id): Architect
    {
        $architect = Architect::withTrashed()->findOrFail($id);
        $architect->restore();
        return $architect;
    }
}
