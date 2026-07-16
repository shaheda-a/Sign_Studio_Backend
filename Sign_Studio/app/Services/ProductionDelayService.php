<?php

namespace App\Services;

use App\Models\ProductionDelay;

class ProductionDelayService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = ProductionDelay::query();
        if (!empty($filters['production_plan_id'])) $query->where('production_plan_id', $filters['production_plan_id']);
        if (!empty($filters['stage_id']))           $query->where('stage_id', $filters['stage_id']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): ProductionDelay
    {
        $data['reported_by'] = auth()->id();
        $data['created_by']  = auth()->id();
        return ProductionDelay::create($data);
    }

    public function delete(int $id): void
    {
        ProductionDelay::findOrFail($id)->delete();
    }

    public function find(int $id): ProductionDelay
    {
        return ProductionDelay::with(['plan', 'stage', 'reportedBy'])->findOrFail($id);
    }
}
