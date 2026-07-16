<?php

namespace App\Services;

use App\Models\TaskBottleneck;

class TaskBottleneckService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = TaskBottleneck::query();
        if (!empty($filters['task_id'])) $query->where('task_id', $filters['task_id']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): TaskBottleneck
    {
        $data['identified_by'] = auth()->id();
        $data['created_by']    = auth()->id();
        return TaskBottleneck::create($data);
    }

    public function resolve(int $id): TaskBottleneck
    {
        $record = TaskBottleneck::findOrFail($id);
        $record->update(['resolved_by' => auth()->id(), 'resolved_at' => now()]);
        return $record->fresh();
    }

    public function delete(int $id): void
    {
        TaskBottleneck::findOrFail($id)->delete();
    }

    public function find(int $id): TaskBottleneck
    {
        return TaskBottleneck::with(['task', 'identifiedBy', 'resolvedBy'])->findOrFail($id);
    }
}
