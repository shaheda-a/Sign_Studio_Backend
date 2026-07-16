<?php

namespace App\Services;

use App\Models\DesignRevision;

class DesignRevisionService
{
    public function getRevisions(array $filters = [], int $perPage = 15): mixed
    {
        $query = DesignRevision::query();

        if (!empty($filters['design_id'])) {
            $query->where('design_id', $filters['design_id']);
        }

        if ($perPage === -1) {
            return $query->latest()->get();
        }

        return $query->latest()->paginate($perPage);
    }

    public function createRevision(array $data): DesignRevision
    {
        $data['created_by'] = auth()->id();

        // Auto-increment revision number for this design
        $lastRevision = DesignRevision::where('design_id', $data['design_id'])->max('revision_number');
        $data['revision_number'] = ($lastRevision ?? 0) + 1;

        return DesignRevision::create($data);
    }

    public function getRevisionById(int $id): DesignRevision
    {
        return DesignRevision::with(['design', 'requestedBy', 'files'])->findOrFail($id);
    }

    public function updateRevision(int $id, array $data): DesignRevision
    {
        $revision = DesignRevision::findOrFail($id);
        $revision->update($data);

        return $revision->fresh();
    }

    public function deleteRevision(int $id): void
    {
        DesignRevision::findOrFail($id)->delete();
    }

    public function restoreRevision(int $id): DesignRevision
    {
        $revision = DesignRevision::withTrashed()->findOrFail($id);
        $revision->restore();

        return $revision;
    }
}
