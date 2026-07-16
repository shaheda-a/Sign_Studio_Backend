<?php

namespace App\Services;

use App\Models\ProductionStage;

class ProductionStageService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = ProductionStage::query();
        if (!empty($filters['production_plan_id'])) $query->where('production_plan_id', $filters['production_plan_id']);
        if (!empty($filters['status']))             $query->where('status', $filters['status']);
        if (!empty($filters['assigned_to']))        $query->where('assigned_to', $filters['assigned_to']);

        return $perPage === -1 ? $query->orderBy('sort_order')->get() : $query->orderBy('sort_order')->paginate($perPage);
    }

    public function create(array $data): ProductionStage
    {
        $data['created_by'] = auth()->id();
        return ProductionStage::create($data);
    }

    public function find(int $id): ProductionStage
    {
        return ProductionStage::with(['plan', 'stageMaster', 'assignedTo'])->findOrFail($id);
    }

    public function update(int $id, array $data): ProductionStage
    {
        $stage = ProductionStage::findOrFail($id);
        $stage->update($data);
        return $stage->fresh();
    }

    public function delete(int $id): void
    {
        ProductionStage::findOrFail($id)->delete();
    }

    public function restore(int $id): ProductionStage
    {
        $record = ProductionStage::withTrashed()->findOrFail($id);
        $record->restore();
        return $record;
    }

    /** Start a production stage */
    public function startStage(int $id): ProductionStage
    {
        $stage = ProductionStage::findOrFail($id);
        $stage->update(['status' => 'in_progress', 'actual_start' => now()]);
        return $stage->fresh();
    }

    /** Complete a production stage */
    public function completeStage(int $id): ProductionStage
    {
        $stage = ProductionStage::findOrFail($id);
        $stage->update(['status' => 'completed', 'actual_end' => now()]);
        return $stage->fresh();
    }
}
