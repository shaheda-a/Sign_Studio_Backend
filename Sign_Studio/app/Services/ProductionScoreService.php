<?php

namespace App\Services;

use App\Models\ProductionScore;

class ProductionScoreService
{
    public function getAll(array $filters = [], int $perPage = 15): mixed
    {
        $query = ProductionScore::query();
        if (!empty($filters['production_plan_id'])) $query->where('production_plan_id', $filters['production_plan_id']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function create(array $data): ProductionScore
    {
        // Compute overall score as average of the three individual scores
        $overall = round(
            (($data['quality_score'] ?? 0) + ($data['efficiency_score'] ?? 0) + ($data['on_time_score'] ?? 0)) / 3,
            2
        );
        $data['overall_score'] = $overall;
        $data['scored_by']     = auth()->id();
        $data['created_by']    = auth()->id();

        return ProductionScore::create($data);
    }

    public function update(int $id, array $data): ProductionScore
    {
        $record = ProductionScore::findOrFail($id);

        $quality    = $data['quality_score']    ?? $record->quality_score;
        $efficiency = $data['efficiency_score'] ?? $record->efficiency_score;
        $onTime     = $data['on_time_score']    ?? $record->on_time_score;

        $data['overall_score'] = round(($quality + $efficiency + $onTime) / 3, 2);
        $record->update($data);

        return $record->fresh();
    }

    public function delete(int $id): void
    {
        ProductionScore::findOrFail($id)->delete();
    }

    public function find(int $id): ProductionScore
    {
        return ProductionScore::with(['plan', 'scoredBy'])->findOrFail($id);
    }
}
