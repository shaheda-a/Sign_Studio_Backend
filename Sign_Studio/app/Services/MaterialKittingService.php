<?php

namespace App\Services;

use App\Models\MaterialKitting;
use App\Models\InventoryTransaction;

class MaterialKittingService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = MaterialKitting::with(['order', 'plan', 'issuedBy']);
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['kit_number'])) {
            $query->where('kit_number', $filters['kit_number']);
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return MaterialKitting::create($data);
    }

    public function findById($id)
    {
        return MaterialKitting::with(['order', 'plan', 'issuedBy', 'items.item'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $kitting = $this->findById($id);
        $kitting->update($data);
        return $kitting;
    }

    public function delete($id)
    {
        $kitting = $this->findById($id);
        $kitting->delete();
        return true;
    }

    public function issueKit($id, $userId)
    {
        $kitting = $this->findById($id);
        $kitting->update([
            'status' => 'issued',
            'issued_at' => now(),
            'issued_by' => $userId
        ]);
        
        $inventoryService = app(InventoryTransactionService::class);
        foreach ($kitting->items as $item) {
             $inventoryService->create([
                 'item_id' => $item->item_id,
                 'type' => 'out',
                 'qty' => $item->issued_qty,
                 'balance_qty' => 0, // Should be calculated in inventory service
                 'order_id' => $kitting->order_id,
                 'reference_type' => 'MaterialKitting',
                 'reference_id' => $kitting->id,
                 'date' => now(),
                 'notes' => 'Kit Issued'
             ]);
        }
        return $kitting;
    }
}
