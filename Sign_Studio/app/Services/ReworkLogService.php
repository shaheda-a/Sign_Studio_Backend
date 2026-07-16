<?php

namespace App\Services;

use App\Models\ReworkLog;

class ReworkLogService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = ReworkLog::query();
        if (!empty($filters['production_plan_id'])) $query->where('production_plan_id', $filters['production_plan_id']);
        if (!empty($filters['stage_id']))           $query->where('stage_id', $filters['stage_id']);
        if (!empty($filters['status']))             $query->where('status', $filters['status']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): ReworkLog
    {
        $data['created_by'] = auth()->id();
        return ReworkLog::create($data);
    }

    public function update(int $id, array $data): ReworkLog
    {
        $record = ReworkLog::findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    public function delete(int $id): void
    {
        ReworkLog::findOrFail($id)->delete();
    }

    public function restore(int $id): ReworkLog
    {
        $record = ReworkLog::withTrashed()->findOrFail($id);
        $record->restore();
        return $record;
    }

    public function find(int $id): ReworkLog
    {
        return ReworkLog::with(['plan', 'stage', 'assignedTo'])->findOrFail($id);
    }
}
