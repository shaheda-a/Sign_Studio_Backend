<?php

namespace App\Services;

use App\Models\VendorQuotation;

class VendorQuotationService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = VendorQuotation::with(['vendor', 'item']);
        if (!empty($filters['vendor_id'])) {
            $query->where('vendor_id', $filters['vendor_id']);
        }
        if (!empty($filters['item_id'])) {
            $query->where('item_id', $filters['item_id']);
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return VendorQuotation::create($data);
    }

    public function findById($id)
    {
        return VendorQuotation::with(['vendor', 'item'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $quotation = $this->findById($id);
        $quotation->update($data);
        return $quotation;
    }

    public function delete($id)
    {
        $quotation = $this->findById($id);
        $quotation->delete();
        return true;
    }
}
