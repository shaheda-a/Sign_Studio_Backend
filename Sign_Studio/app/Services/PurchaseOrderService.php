<?php

namespace App\Services;

use App\Models\PurchaseOrder;

class PurchaseOrderService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = PurchaseOrder::with(['vendor', 'purchaseRequest', 'approvedBy']);
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['po_number'])) {
            $query->where('po_number', $filters['po_number']);
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return PurchaseOrder::create($data);
    }

    public function findById($id)
    {
        return PurchaseOrder::with(['vendor', 'items.item', 'purchaseRequest', 'approvedBy'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $po = $this->findById($id);
        $po->update($data);
        return $po;
    }

    public function delete($id)
    {
        $po = $this->findById($id);
        $po->delete();
        return true;
    }
}
