<?php

namespace App\Services;

use App\Models\ProductionPlan;
use Illuminate\Support\Str;

class ProductionPlanService
{
    public function getPlans(array $filters = [], int $perPage = 15): mixed
    {
        $query = ProductionPlan::query();
        if (!empty($filters['order_id']))   $query->where('order_id', $filters['order_id']);
        if (!empty($filters['status']))     $query->where('status', $filters['status']);

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function createPlan(array $data): ProductionPlan
    {
        $data['plan_number'] = $data['plan_number'] ?? 'PP-' . strtoupper(Str::random(6));
        $data['created_by']  = auth()->id();
        return ProductionPlan::create($data);
    }

    public function getPlanById(int $id): ProductionPlan
    {
        return ProductionPlan::with([
            'order', 'jobCard', 'stages', 'proofs', 'delays',
            'scores', 'qcChecklists', 'reworkLogs',
        ])->findOrFail($id);
    }

    public function updatePlan(int $id, array $data): ProductionPlan
    {
        $plan = ProductionPlan::findOrFail($id);
        $plan->update($data);
        return $plan->fresh();
    }

    public function deletePlan(int $id): void
    {
        ProductionPlan::findOrFail($id)->delete();
    }

    public function restorePlan(int $id): ProductionPlan
    {
        $plan = ProductionPlan::withTrashed()->findOrFail($id);
        $plan->restore();
        return $plan;
    }

    /** Compute overall QC pass rate and update status if all stages done */
    public function computeQcSummary(int $id): array
    {
        $plan      = ProductionPlan::with('qcChecklists')->findOrFail($id);
        $total     = $plan->qcChecklists->count();
        $passed    = $plan->qcChecklists->where('is_passed', 1)->count();
        $passRate  = $total > 0 ? round(($passed / $total) * 100, 2) : 0;
        $reworkReq = $plan->qcChecklists->where('rework_required', 1)->count();

        return [
            'total_items'   => $total,
            'passed'        => $passed,
            'failed'        => $total - $passed,
            'rework_needed' => $reworkReq,
            'pass_rate_pct' => $passRate,
        ];
    }
}
