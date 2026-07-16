<?php

namespace App\Services;

use App\Models\TaskEscalation;

class TaskEscalationService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = TaskEscalation::query();
        if (!empty($filters['task_id'])) $query->where('task_id', $filters['task_id']);
        if (!empty($filters['status']))  $query->where('status', $filters['status']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): TaskEscalation
    {
        $data['created_by'] = auth()->id();
        return TaskEscalation::create($data);
    }

    public function resolve(int $id): TaskEscalation
    {
        $record = TaskEscalation::findOrFail($id);
        $record->update(['status' => 'resolved', 'resolved_at' => now()]);
        return $record->fresh();
    }

    public function delete(int $id): void
    {
        TaskEscalation::findOrFail($id)->delete();
    }

    public function find(int $id): TaskEscalation
    {
        return TaskEscalation::with(['task', 'escalatedFrom', 'escalatedTo'])->findOrFail($id);
    }
}
