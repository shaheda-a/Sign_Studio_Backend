<?php

namespace App\Services;

use App\Models\TaskPauseLog;
use App\Models\Task;

class TaskPauseLogService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = TaskPauseLog::query();
        if (!empty($filters['task_id'])) $query->where('task_id', $filters['task_id']);

        return $perPage === -1 ? $query->latest('paused_at')->get() : $query->latest('paused_at')->paginate($perPage);
    }

    /** Pause a task — creates a pause log and sets task status to paused */
    public function pauseTask(array $data): TaskPauseLog
    {
        $data['paused_by'] = auth()->id();
        $data['paused_at'] = now();

        Task::findOrFail($data['task_id'])->update(['status' => 'paused']);

        return TaskPauseLog::create($data);
    }

    /** Resume a task — records resumed_at and sets task back to in_progress */
    public function resumeTask(int $pauseLogId): TaskPauseLog
    {
        $log = TaskPauseLog::findOrFail($pauseLogId);
        $log->update(['resumed_at' => now()]);

        Task::findOrFail($log->task_id)->update(['status' => 'in_progress']);

        return $log->fresh();
    }

    public function find(int $id): TaskPauseLog
    {
        return TaskPauseLog::with(['task', 'pausedBy'])->findOrFail($id);
    }
}
