<?php

namespace App\Services;

use App\Models\PurchaseRequest;

class PurchaseRequestService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = PurchaseRequest::with(['order', 'department', 'requestedBy', 'approvedBy']);
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['pr_number'])) {
            $query->where('pr_number', $filters['pr_number']);
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return PurchaseRequest::create($data);
    }

    public function findById($id)
    {
        return PurchaseRequest::with(['order', 'department', 'requestedBy', 'approvedBy', 'items.item'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $pr = $this->findById($id);
        $pr->update($data);
        return $pr;
    }

    public function delete($id)
    {
        $pr = $this->findById($id);
        $pr->delete();
        return true;
    }
}
