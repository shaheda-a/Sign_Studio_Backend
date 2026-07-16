<?php

namespace App\Services;

use App\Models\VendorPayment;

class VendorPaymentService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = VendorPayment::with(['vendor', 'purchaseOrder', 'approvedBy']);
        if (!empty($filters['vendor_id'])) {
            $query->where('vendor_id', $filters['vendor_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return VendorPayment::create($data);
    }

    public function findById($id)
    {
        return VendorPayment::with(['vendor', 'purchaseOrder', 'approvedBy'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $payment = $this->findById($id);
        $payment->update($data);
        return $payment;
    }

    public function delete($id)
    {
        $payment = $this->findById($id);
        $payment->delete();
        return true;
    }
}
