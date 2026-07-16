<?php

namespace App\Services;

use App\Models\PipelineStage;
use Illuminate\Support\Facades\Auth;

class PipelineStageService
{
    /**
     * Get pipeline stages with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPipelineStages(array $filters = [], int $perPage = 15): mixed
    {
        $query = PipelineStage::query();

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($perPage === -1) {
            return $query->orderBy('sort_order', 'asc')->get();
        }

        return $query->orderBy('sort_order', 'asc')->paginate($perPage);
    }

    /**
     * Create a pipeline stage.
     *
     * @param array $data
     * @return PipelineStage
     */
    public function createPipelineStage(array $data): PipelineStage
    {
        $data['created_by'] = Auth::id();
        return PipelineStage::create($data);
    }

    /**
     * Get pipeline stage by ID.
     *
     * @param int $id
     * @return PipelineStage
     */
    public function getPipelineStageById(int $id): PipelineStage
    {
        return PipelineStage::findOrFail($id);
    }

    /**
     * Update a pipeline stage.
     *
     * @param int $id
     * @param array $data
     * @return PipelineStage
     */
    public function updatePipelineStage(int $id, array $data): PipelineStage
    {
        $stage = PipelineStage::findOrFail($id);
        $stage->update($data);
        return $stage;
    }

    /**
     * Delete a pipeline stage.
     *
     * @param int $id
     * @return bool
     */
    public function deletePipelineStage(int $id): bool
    {
        $stage = PipelineStage::findOrFail($id);
        return $stage->delete();
    }

    /**
     * Restore a deleted pipeline stage.
     *
     * @param int $id
     * @return PipelineStage
     */
    public function restorePipelineStage(int $id): PipelineStage
    {
        $stage = PipelineStage::withTrashed()->findOrFail($id);
        $stage->restore();
        return $stage;
    }
}
