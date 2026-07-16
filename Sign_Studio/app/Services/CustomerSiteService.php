<?php

namespace App\Services;

use App\Models\CustomerSite;
use Illuminate\Support\Facades\Auth;

class CustomerSiteService
{
    /**
     * Get sites with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getSites(array $filters = [], int $perPage = 15): mixed
    {
        $query = CustomerSite::query()->with('customer');

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if ($perPage === -1) {
            return $query->orderBy('site_name', 'asc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a site record.
     *
     * @param array $data
     * @return CustomerSite
     */
    public function createSite(array $data): CustomerSite
    {
        $data['created_by'] = Auth::id();
        return CustomerSite::create($data);
    }

    /**
     * Get site by ID.
     *
     * @param int $id
     * @return CustomerSite
     */
    public function getSiteById(int $id): CustomerSite
    {
        return CustomerSite::with('customer')->findOrFail($id);
    }

    /**
     * Update a site record.
     *
     * @param int $id
     * @param array $data
     * @return CustomerSite
     */
    public function updateSite(int $id, array $data): CustomerSite
    {
        $site = CustomerSite::findOrFail($id);
        $site->update($data);
        return $site;
    }

    /**
     * Delete a site record.
     *
     * @param int $id
     * @return bool
     */
    public function deleteSite(int $id): bool
    {
        $site = CustomerSite::findOrFail($id);
        return $site->delete();
    }

    /**
     * Restore a deleted site record.
     *
     * @param int $id
     * @return CustomerSite
     */
    public function restoreSite(int $id): CustomerSite
    {
        $site = CustomerSite::withTrashed()->findOrFail($id);
        $site->restore();
        return $site;
    }
}
