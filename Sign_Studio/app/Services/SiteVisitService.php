<?php

namespace App\Services;

use App\Models\SiteVisit;
use Illuminate\Support\Str;

class SiteVisitService
{
    /**
     * Get paginated site visits with optional filters.
     */
    public function getSiteVisits(array $filters = [], int $perPage = 15): mixed
    {
        $query = SiteVisit::query();

        if (!empty($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if ($perPage === -1) {
            return $query->latest()->get();
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new site visit.
     */
    public function createSiteVisit(array $data): SiteVisit
    {
        $data['visit_number'] = $data['visit_number'] ?? 'SV-' . strtoupper(Str::random(6));
        $data['created_by']   = auth()->id();

        return SiteVisit::create($data);
    }

    /**
     * Retrieve a site visit by ID.
     */
    public function getSiteVisitById(int $id): SiteVisit
    {
        return SiteVisit::with(['lead', 'customerSite', 'assignedTo', 'measurements', 'photos', 'checklists', 'visitProofs'])
            ->findOrFail($id);
    }

    /**
     * Update an existing site visit.
     */
    public function updateSiteVisit(int $id, array $data): SiteVisit
    {
        $visit = SiteVisit::findOrFail($id);
        $visit->update($data);

        return $visit->fresh();
    }

    /**
     * Soft-delete a site visit.
     */
    public function deleteSiteVisit(int $id): void
    {
        SiteVisit::findOrFail($id)->delete();
    }

    /**
     * Restore a soft-deleted site visit.
     */
    public function restoreSiteVisit(int $id): SiteVisit
    {
        $visit = SiteVisit::withTrashed()->findOrFail($id);
        $visit->restore();

        return $visit;
    }
}
