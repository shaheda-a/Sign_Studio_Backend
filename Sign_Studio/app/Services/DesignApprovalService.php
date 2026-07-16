<?php

namespace App\Services;

use App\Models\DesignApproval;

class DesignApprovalService
{
    public function getApprovals(array $filters = [], int $perPage = 15): mixed
    {
        $query = DesignApproval::query();

        if (!empty($filters['design_id'])) {
            $query->where('design_id', $filters['design_id']);
        }

        if ($perPage === -1) {
            return $query->latest()->get();
        }

        return $query->latest()->paginate($perPage);
    }

    public function createApproval(array $data): DesignApproval
    {
        $data['created_by'] = auth()->id();

        return DesignApproval::create($data);
    }

    public function getApprovalById(int $id): DesignApproval
    {
        return DesignApproval::with(['design', 'revision', 'approvedBy'])->findOrFail($id);
    }

    public function updateApproval(int $id, array $data): DesignApproval
    {
        $approval = DesignApproval::findOrFail($id);
        $approval->update($data);

        return $approval->fresh();
    }

    public function deleteApproval(int $id): void
    {
        DesignApproval::findOrFail($id)->delete();
    }
}
