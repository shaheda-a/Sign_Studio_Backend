<?php

namespace App\Services;

use App\Models\Vendor;

class VendorService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = Vendor::query();
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return Vendor::create($data);
    }

    public function findById($id)
    {
        return Vendor::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $vendor = $this->findById($id);
        $vendor->update($data);
        return $vendor;
    }

    public function delete($id)
    {
        $vendor = $this->findById($id);
        $vendor->delete();
        return true;
    }

    public function restore($id)
    {
        $vendor = Vendor::withTrashed()->findOrFail($id);
        $vendor->restore();
        return $vendor;
    }
}
