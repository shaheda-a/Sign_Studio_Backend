<?php

namespace App\Services;

use App\Models\ProductionProof;

class ProductionProofService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = ProductionProof::query();
        if (!empty($filters['production_plan_id'])) $query->where('production_plan_id', $filters['production_plan_id']);
        if (!empty($filters['stage_id']))           $query->where('stage_id', $filters['stage_id']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): ProductionProof
    {
        $data['uploaded_by'] = auth()->id();
        $data['created_by']  = auth()->id();
        return ProductionProof::create($data);
    }

    public function delete(int $id): void
    {
        ProductionProof::findOrFail($id)->delete();
    }

    public function restore(int $id): ProductionProof
    {
        $record = ProductionProof::withTrashed()->findOrFail($id);
        $record->restore();
        return $record;
    }

    public function find(int $id): ProductionProof
    {
        return ProductionProof::with(['plan', 'stage'])->findOrFail($id);
    }
}
