<?php

namespace App\Services;

use App\Models\Design;
use App\Models\DesignStatusHistory;
use Illuminate\Support\Str;

class DesignService
{
    /**
     * Get paginated designs with optional filters.
     */
    public function getDesigns(array $filters = [], int $perPage = 15): mixed
    {
        $query = Design::query();

        if (!empty($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if ($perPage === -1) {
            return $query->latest()->get();
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new design.
     */
    public function createDesign(array $data): Design
    {
        $data['design_number'] = $data['design_number'] ?? 'DS-' . strtoupper(Str::random(6));
        $data['created_by']    = auth()->id();

        $design = Design::create($data);

        DesignStatusHistory::create([
            'design_id'  => $design->id,
            'from_status' => null,
            'to_status'  => $design->status,
            'changed_by' => auth()->id(),
            'notes'      => 'Design created',
        ]);

        return $design;
    }

    /**
     * Retrieve a design by ID.
     */
    public function getDesignById(int $id): Design
    {
        return Design::with(['lead', 'assignedTo', 'revisions', 'files', 'approvals', 'sampleApprovals', 'materialApprovals', 'finishApprovals', 'statusHistory'])
            ->findOrFail($id);
    }

    /**
     * Update an existing design.
     */
    public function updateDesign(int $id, array $data): Design
    {
        $design = Design::findOrFail($id);
        $oldStatus = $design->status;

        $design->update($data);

        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            DesignStatusHistory::create([
                'design_id'   => $design->id,
                'from_status' => $oldStatus,
                'to_status'   => $data['status'],
                'changed_by'  => auth()->id(),
                'notes'       => $data['status_notes'] ?? null,
            ]);
        }

        return $design->fresh();
    }

    /**
     * Soft-delete a design.
     */
    public function deleteDesign(int $id): void
    {
        Design::findOrFail($id)->delete();
    }

    /**
     * Restore a soft-deleted design.
     */
    public function restoreDesign(int $id): Design
    {
        $design = Design::withTrashed()->findOrFail($id);
        $design->restore();

        return $design;
    }

    /**
     * Lock a design.
     */
    public function lockDesign(int $id): Design
    {
        $design = Design::findOrFail($id);
        $design->update([
            'is_locked' => 1,
            'locked_at' => now(),
            'locked_by' => auth()->id(),
        ]);

        return $design->fresh();
    }
}
