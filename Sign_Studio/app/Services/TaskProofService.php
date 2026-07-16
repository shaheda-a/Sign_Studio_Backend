<?php

namespace App\Services;

use App\Models\TaskProof;

class TaskProofService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = TaskProof::query();
        if (!empty($filters['task_id'])) $query->where('task_id', $filters['task_id']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): TaskProof
    {
        $data['created_by'] = auth()->id();
        return TaskProof::create($data);
    }

    public function delete(int $id): void
    {
        TaskProof::findOrFail($id)->delete();
    }

    public function restore(int $id): TaskProof
    {
        $record = TaskProof::withTrashed()->findOrFail($id);
        $record->restore();
        return $record;
    }

    public function find(int $id): TaskProof
    {
        return TaskProof::with('task')->findOrFail($id);
    }
}
