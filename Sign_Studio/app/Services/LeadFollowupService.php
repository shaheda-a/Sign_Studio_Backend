<?php

namespace App\Services;

use App\Models\LeadFollowup;
use Illuminate\Support\Facades\Auth;

class LeadFollowupService
{
    /**
     * Get follow-up schedules.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getFollowups(array $filters = [], int $perPage = 15): mixed
    {
        $query = LeadFollowup::query()->with(['lead', 'assignedUser']);

        if (!empty($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if ($perPage === -1) {
            return $query->orderBy('follow_up_date', 'asc')->get();
        }

        return $query->orderBy('follow_up_date', 'asc')->paginate($perPage);
    }

    /**
     * Create a follow-up.
     *
     * @param array $data
     * @return LeadFollowup
     */
    public function createFollowup(array $data): LeadFollowup
    {
        $data['created_by'] = Auth::id();
        return LeadFollowup::create($data);
    }

    /**
     * Get follow-up by ID.
     *
     * @param int $id
     * @return LeadFollowup
     */
    public function getFollowupById(int $id): LeadFollowup
    {
        return LeadFollowup::with(['lead', 'assignedUser'])->findOrFail($id);
    }

    /**
     * Update a follow-up.
     *
     * @param int $id
     * @param array $data
     * @return LeadFollowup
     */
    public function updateFollowup(int $id, array $data): LeadFollowup
    {
        $followup = LeadFollowup::findOrFail($id);
        $followup->update($data);
        return $followup;
    }

    /**
     * Delete a follow-up.
     *
     * @param int $id
     * @return bool
     */
    public function deleteFollowup(int $id): bool
    {
        $followup = LeadFollowup::findOrFail($id);
        return $followup->delete();
    }

    /**
     * Restore a deleted follow-up.
     *
     * @param int $id
     * @return LeadFollowup
     */
    public function restoreFollowup(int $id): LeadFollowup
    {
        $followup = LeadFollowup::withTrashed()->findOrFail($id);
        $followup->restore();
        return $followup;
    }
}
