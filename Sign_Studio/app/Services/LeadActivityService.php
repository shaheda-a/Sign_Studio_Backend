<?php

namespace App\Services;

use App\Models\LeadActivity;
use Illuminate\Support\Facades\Auth;

class LeadActivityService
{
    /**
     * Get activities with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getActivities(array $filters = [], int $perPage = 15): mixed
    {
        $query = LeadActivity::query()->with(['lead', 'user']);

        if (!empty($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'desc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create an activity log.
     *
     * @param array $data
     * @return LeadActivity
     */
    public function createActivity(array $data): LeadActivity
    {
        $data['created_by'] = Auth::id();
        if (empty($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }
        return LeadActivity::create($data);
    }

    /**
     * Get activity by ID.
     *
     * @param int $id
     * @return LeadActivity
     */
    public function getActivityById(int $id): LeadActivity
    {
        return LeadActivity::with(['lead', 'user'])->findOrFail($id);
    }

    /**
     * Update an activity log.
     *
     * @param int $id
     * @param array $data
     * @return LeadActivity
     */
    public function updateActivity(int $id, array $data): LeadActivity
    {
        $activity = LeadActivity::findOrFail($id);
        $activity->update($data);
        return $activity;
    }

    /**
     * Delete an activity log.
     *
     * @param int $id
     * @return bool
     */
    public function deleteActivity(int $id): bool
    {
        $activity = LeadActivity::findOrFail($id);
        return $activity->delete();
    }

    /**
     * Restore a deleted activity log.
     *
     * @param int $id
     * @return LeadActivity
     */
    public function restoreActivity(int $id): LeadActivity
    {
        $activity = LeadActivity::withTrashed()->findOrFail($id);
        $activity->restore();
        return $activity;
    }
}
