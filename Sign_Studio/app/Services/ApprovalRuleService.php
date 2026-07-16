<?php

namespace App\Services;

use App\Models\ApprovalRule;
use Illuminate\Support\Facades\Auth;

class ApprovalRuleService
{
    /**
     * Get rules with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getRules(array $filters = [], int $perPage = 15): mixed
    {
        $query = ApprovalRule::query()->with(['approverRole']);

        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'desc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a rule.
     *
     * @param array $data
     * @return ApprovalRule
     */
    public function createRule(array $data): ApprovalRule
    {
        $data['created_by'] = Auth::id();
        return ApprovalRule::create($data);
    }

    /**
     * Get rule by ID.
     *
     * @param int $id
     * @return ApprovalRule
     */
    public function getRuleById(int $id): ApprovalRule
    {
        return ApprovalRule::with(['approverRole'])->findOrFail($id);
    }

    /**
     * Update a rule.
     *
     * @param int $id
     * @param array $data
     * @return ApprovalRule
     */
    public function updateRule(int $id, array $data): ApprovalRule
    {
        $rule = ApprovalRule::findOrFail($id);
        $rule->update($data);
        return $rule;
    }

    /**
     * Delete a rule.
     *
     * @param int $id
     * @return bool
     */
    public function deleteRule(int $id): bool
    {
        $rule = ApprovalRule::findOrFail($id);
        return $rule->delete();
    }

    /**
     * Restore a deleted rule.
     *
     * @param int $id
     * @return ApprovalRule
     */
    public function restoreRule(int $id): ApprovalRule
    {
        $rule = ApprovalRule::withTrashed()->findOrFail($id);
        $rule->restore();
        return $rule;
    }
}
