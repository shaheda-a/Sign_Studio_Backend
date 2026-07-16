<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    /**
     * Get customers with filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getCustomers(array $filters = [], int $perPage = 15): mixed
    {
        $query = Customer::query()->with(['branch', 'contacts', 'sites']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($perPage === -1) {
            return $query->orderBy('name', 'asc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a customer.
     *
     * @param array $data
     * @return Customer
     */
    public function createCustomer(array $data): Customer
    {
        $data['created_by'] = Auth::id();
        return Customer::create($data);
    }

    /**
     * Get customer by ID.
     *
     * @param int $id
     * @return Customer
     */
    public function getCustomerById(int $id): Customer
    {
        return Customer::with(['branch', 'contacts', 'sites', 'referrals'])->findOrFail($id);
    }

    /**
     * Update a customer.
     *
     * @param int $id
     * @param array $data
     * @return Customer
     */
    public function updateCustomer(int $id, array $data): Customer
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    /**
     * Delete a customer.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCustomer(int $id): bool
    {
        $customer = Customer::findOrFail($id);
        return $customer->delete();
    }

    /**
     * Restore a deleted customer.
     *
     * @param int $id
     * @return Customer
     */
    public function restoreCustomer(int $id): Customer
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        $customer->restore();
        return $customer;
    }
}
