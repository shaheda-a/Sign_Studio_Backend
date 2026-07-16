<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskLog;
use Illuminate\Support\Str;

class TaskService
{
    public function getTasks(array $filters = [], int $perPage = 15): mixed
    {
        $query = Task::query();
        if (!empty($filters['order_id']))     $query->where('order_id', $filters['order_id']);
        if (!empty($filters['assigned_to']))  $query->where('assigned_to', $filters['assigned_to']);
        if (!empty($filters['department_id']))$query->where('department_id', $filters['department_id']);
        if (!empty($filters['status']))       $query->where('status', $filters['status']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function createTask(array $data): Task
    {
        $data['task_number'] = $data['task_number'] ?? 'TSK-' . strtoupper(Str::random(6));
        $data['created_by']  = auth()->id();
        $task = Task::create($data);

        $this->log($task->id, 'created', 'Task created');
        return $task;
    }

    public function getTaskById(int $id): Task
    {
        return Task::with([
            'order', 'department', 'assignedTo', 'logs', 'acceptance',
            'pauseLogs', 'delays', 'escalations', 'bottlenecks', 'verifications', 'proofs',
        ])->findOrFail($id);
    }

    public function updateTask(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $old  = $task->status;
        $task->update($data);

        if (isset($data['status']) && $data['status'] !== $old) {
            $this->log($id, 'status_changed', "Status: {$old} → {$data['status']}");
        }

        return $task->fresh();
    }

    public function deleteTask(int $id): void
    {
        Task::findOrFail($id)->delete();
    }

    public function restoreTask(int $id): Task
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();
        return $task;
    }

    /** Start a task — record actual_start */
    public function startTask(int $id): Task
    {
        $task = Task::findOrFail($id);
        $task->update(['status' => 'in_progress', 'actual_start' => now()]);
        $this->log($id, 'started', 'Task started');
        return $task->fresh();
    }

    /** Complete a task — record actual_end and compute TAT */
    public function completeTask(int $id): Task
    {
        $task = Task::findOrFail($id);
        $actualEnd  = now();
        $tatHours   = $task->actual_start
            ? round($task->actual_start->diffInMinutes($actualEnd) / 60, 2)
            : null;

        $task->update([
            'status'             => 'completed',
            'actual_end'         => $actualEnd,
            'actual_time_hours'  => $tatHours,
            'tat_duration_hours' => $tatHours,
        ]);
        $this->log($id, 'completed', "Task completed. TAT: {$tatHours} hrs");
        return $task->fresh();
    }

    private function log(int $taskId, string $action, string $notes): void
    {
        TaskLog::create([
            'task_id' => $taskId,
            'user_id' => auth()->id(),
            'action'  => $action,
            'notes'   => $notes,
        ]);
    }
}
