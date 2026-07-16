<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Models\StockAlert;

class InventoryTransactionService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = InventoryTransaction::with(['item', 'order', 'purchaseOrder']);
        if (!empty($filters['item_id'])) {
            $query->where('item_id', $filters['item_id']);
        }
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        $transaction = InventoryTransaction::create($data);
        
        // Update Item Stock
        $item = Item::find($data['item_id']);
        if ($item) {
            $qty = floatval($data['qty']);
            if ($data['type'] === 'in') {
                $item->current_stock += $qty;
            } elseif ($data['type'] === 'out') {
                $item->current_stock -= $qty;
            }
            $item->save();

            // Check Stock Alert
            if ($item->current_stock <= $item->reorder_level) {
                StockAlert::firstOrCreate([
                    'item_id' => $item->id,
                    'is_resolved' => false
                ], [
                    'current_qty' => $item->current_stock,
                    'reorder_level' => $item->reorder_level,
                    'alert_type' => 'low_stock'
                ]);
            }
        }

        return $transaction;
    }

    public function findById($id)
    {
        return InventoryTransaction::with(['item', 'order', 'purchaseOrder'])->findOrFail($id);
    }
}
