<?php

namespace App\Services;

use App\Models\JobCard;
use Illuminate\Support\Str;

class JobCardService
{
    public function getJobCards(array $filters = [], int $perPage = 15): mixed
    {
        $query = JobCard::query();

        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function createJobCard(array $data): JobCard
    {
        $data['job_card_number'] = $data['job_card_number'] ?? 'JC-' . strtoupper(Str::random(6));
        $data['created_by']      = auth()->id();

        return JobCard::create($data);
    }

    public function getJobCardById(int $id): JobCard
    {
        return JobCard::with(['order', 'scopeLockedBy'])->findOrFail($id);
    }

    public function updateJobCard(int $id, array $data): JobCard
    {
        $jobCard = JobCard::findOrFail($id);
        $jobCard->update($data);

        return $jobCard->fresh();
    }

    public function deleteJobCard(int $id): void
    {
        JobCard::findOrFail($id)->delete();
    }

    public function restoreJobCard(int $id): JobCard
    {
        $jobCard = JobCard::withTrashed()->findOrFail($id);
        $jobCard->restore();

        return $jobCard;
    }

    /**
     * Lock the job card scope (prevent further changes to items).
     */
    public function lockScope(int $id): JobCard
    {
        $jobCard = JobCard::findOrFail($id);
        $jobCard->update([
            'is_scope_locked' => 1,
            'scope_locked_at' => now(),
            'scope_locked_by' => auth()->id(),
        ]);

        return $jobCard->fresh();
    }
}
