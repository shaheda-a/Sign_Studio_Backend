<?php

namespace App\Services;

use App\Models\QcChecklist;

class QcChecklistService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = QcChecklist::query();
        if (!empty($filters['production_plan_id'])) $query->where('production_plan_id', $filters['production_plan_id']);
        if (isset($filters['is_passed']))           $query->where('is_passed', $filters['is_passed']);
        if (isset($filters['rework_required']))     $query->where('rework_required', $filters['rework_required']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): QcChecklist
    {
        $data['inspected_by'] = auth()->id();
        $data['created_by']   = auth()->id();
        return QcChecklist::create($data);
    }

    public function update(int $id, array $data): QcChecklist
    {
        $record = QcChecklist::findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    public function delete(int $id): void
    {
        QcChecklist::findOrFail($id)->delete();
    }

    public function restore(int $id): QcChecklist
    {
        $record = QcChecklist::withTrashed()->findOrFail($id);
        $record->restore();
        return $record;
    }

    public function find(int $id): QcChecklist
    {
        return QcChecklist::with(['plan', 'inspectedBy'])->findOrFail($id);
    }
}
