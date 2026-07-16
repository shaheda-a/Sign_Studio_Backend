<?php

namespace App\Services;

use App\Models\StockAlert;

class StockAlertService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = StockAlert::with(['item']);
        if (isset($filters['is_resolved'])) {
            $query->where('is_resolved', $filters['is_resolved']);
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return StockAlert::create($data);
    }

    public function findById($id)
    {
        return StockAlert::with(['item'])->findOrFail($id);
    }

    public function resolve($id, $userId)
    {
        $alert = $this->findById($id);
        $alert->update([
            'is_resolved' => true,
            'resolved_by' => $userId,
            'resolved_at' => now(),
        ]);
        return $alert;
    }
}
