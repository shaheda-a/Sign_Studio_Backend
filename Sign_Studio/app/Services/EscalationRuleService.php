<?php

namespace App\Services;

use App\Models\EscalationRule;
use Illuminate\Support\Facades\Auth;

class EscalationRuleService
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
        $query = EscalationRule::query()->with(['escalateToRole', 'notifyUser']);

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
     * @return EscalationRule
     */
    public function createRule(array $data): EscalationRule
    {
        $data['created_by'] = Auth::id();
        return EscalationRule::create($data);
    }

    /**
     * Get rule by ID.
     *
     * @param int $id
     * @return EscalationRule
     */
    public function getRuleById(int $id): EscalationRule
    {
        return EscalationRule::with(['escalateToRole', 'notifyUser'])->findOrFail($id);
    }

    /**
     * Update a rule.
     *
     * @param int $id
     * @param array $data
     * @return EscalationRule
     */
    public function updateRule(int $id, array $data): EscalationRule
    {
        $rule = EscalationRule::findOrFail($id);
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
        $rule = EscalationRule::findOrFail($id);
        return $rule->delete();
    }

    /**
     * Restore a deleted rule.
     *
     * @param int $id
     * @return EscalationRule
     */
    public function restoreRule(int $id): EscalationRule
    {
        $rule = EscalationRule::withTrashed()->findOrFail($id);
        $rule->restore();
        return $rule;
    }
}
