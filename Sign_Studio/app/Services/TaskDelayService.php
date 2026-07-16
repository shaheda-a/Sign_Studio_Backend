<?php

namespace App\Services;

use App\Models\TaskDelay;

class TaskDelayService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = TaskDelay::query();
        if (!empty($filters['task_id'])) $query->where('task_id', $filters['task_id']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): TaskDelay
    {
        $data['created_by'] = auth()->id();
        return TaskDelay::create($data);
    }

    public function update(int $id, array $data): TaskDelay
    {
        $record = TaskDelay::findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    public function delete(int $id): void
    {
        TaskDelay::findOrFail($id)->delete();
    }

    public function find(int $id): TaskDelay
    {
        return TaskDelay::with(['task', 'escalatedTo', 'createdBy'])->findOrFail($id);
    }
}
