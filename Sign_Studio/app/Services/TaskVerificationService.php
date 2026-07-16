<?php

namespace App\Services;

use App\Models\TaskVerification;

class TaskVerificationService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = TaskVerification::query();
        if (!empty($filters['task_id'])) $query->where('task_id', $filters['task_id']);
        if (!empty($filters['status']))  $query->where('status', $filters['status']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): TaskVerification
    {
        $data['verified_by'] = auth()->id();
        $data['verified_at'] = now();
        $data['created_by']  = auth()->id();
        return TaskVerification::create($data);
    }

    public function delete(int $id): void
    {
        TaskVerification::findOrFail($id)->delete();
    }

    public function find(int $id): TaskVerification
    {
        return TaskVerification::with(['task', 'verifiedBy'])->findOrFail($id);
    }
}
