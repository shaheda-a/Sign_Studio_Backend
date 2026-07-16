<?php

namespace App\Services;

use App\Models\MaterialConsumption;

class MaterialConsumptionService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = MaterialConsumption::with(['order', 'plan', 'item', 'stage', 'consumedBy']);
        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }
        if (!empty($filters['production_plan_id'])) {
            $query->where('production_plan_id', $filters['production_plan_id']);
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        $consumption = MaterialConsumption::create($data);
        
        $inventoryService = app(InventoryTransactionService::class);
        $inventoryService->create([
            'item_id' => $data['item_id'],
            'type' => 'out',
            'qty' => $data['consumed_qty'] + ($data['wastage_qty'] ?? 0),
            'balance_qty' => 0,
            'order_id' => $data['order_id'] ?? null,
            'reference_type' => 'MaterialConsumption',
            'reference_id' => $consumption->id,
            'date' => now(),
            'notes' => 'Material Consumed'
        ]);
        
        return $consumption;
    }

    public function findById($id)
    {
        return MaterialConsumption::with(['order', 'plan', 'item', 'stage', 'consumedBy'])->findOrFail($id);
    }
}
