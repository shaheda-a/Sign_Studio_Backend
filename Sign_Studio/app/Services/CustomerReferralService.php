<?php

namespace App\Services;

use App\Models\CustomerReferral;
use Illuminate\Support\Facades\Auth;

class CustomerReferralService
{
    /**
     * Get referrals with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getReferrals(array $filters = [], int $perPage = 15): mixed
    {
        $query = CustomerReferral::query()->with(['referrer', 'referred']);

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'desc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a referral mapping.
     *
     * @param array $data
     * @return CustomerReferral
     */
    public function createReferral(array $data): CustomerReferral
    {
        $data['created_by'] = Auth::id();
        return CustomerReferral::create($data);
    }

    /**
     * Get referral by ID.
     *
     * @param int $id
     * @return CustomerReferral
     */
    public function getReferralById(int $id): CustomerReferral
    {
        return CustomerReferral::with(['referrer', 'referred'])->findOrFail($id);
    }

    /**
     * Update a referral mapping.
     *
     * @param int $id
     * @param array $data
     * @return CustomerReferral
     */
    public function updateReferral(int $id, array $data): CustomerReferral
    {
        $referral = CustomerReferral::findOrFail($id);
        $referral->update($data);
        return $referral;
    }

    /**
     * Delete a referral mapping.
     *
     * @param int $id
     * @return bool
     */
    public function deleteReferral(int $id): bool
    {
        $referral = CustomerReferral::findOrFail($id);
        return $referral->delete();
    }

    /**
     * Restore a deleted referral mapping.
     *
     * @param int $id
     * @return CustomerReferral
     */
    public function restoreReferral(int $id): CustomerReferral
    {
        $referral = CustomerReferral::withTrashed()->findOrFail($id);
        $referral->restore();
        return $referral;
    }
}
