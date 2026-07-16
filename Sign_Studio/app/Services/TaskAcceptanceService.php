<?php

namespace App\Services;

use App\Models\TaskAcceptance;
use App\Models\Task;

class TaskAcceptanceService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = TaskAcceptance::query();
        if (!empty($filters['task_id'])) $query->where('task_id', $filters['task_id']);
        if (!empty($filters['user_id']))  $query->where('user_id', $filters['user_id']);
        if (!empty($filters['status']))   $query->where('status', $filters['status']);

        return $perPage === -1 ? $query->latest('created_at')->get() : $query->latest('created_at')->paginate($perPage);
    }

    public function create(array $data): TaskAcceptance
    {
        $data['user_id'] = $data['user_id'] ?? auth()->id();

        // If accepted, update task status to in_progress
        $record = TaskAcceptance::create($data);

        if ($data['status'] === 'accepted') {
            Task::where('id', $data['task_id'])->update(['status' => 'in_progress']);
        }

        if ($data['status'] === 'rejected') {
            Task::where('id', $data['task_id'])->update(['status' => 'rejected']);
        }

        return $record;
    }

    public function find(int $id): TaskAcceptance
    {
        return TaskAcceptance::with(['task', 'user'])->findOrFail($id);
    }
}
