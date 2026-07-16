<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderService
{
    public function getOrders(array $filters = [], int $perPage = 15): mixed
    {
        $query = Order::query();

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }
        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function createOrder(array $data): Order
    {
        $data['order_number'] = $data['order_number'] ?? 'ORD-' . strtoupper(Str::random(6));
        $data['created_by']   = auth()->id();

        // Set balance = total - advance
        if (isset($data['total_amount'])) {
            $advance = $data['advance_received'] ?? 0;
            $data['balance_amount'] = $data['total_amount'] - $advance;
        }

        return Order::create($data);
    }

    public function getOrderById(int $id): Order
    {
        return Order::with(['quotation', 'customer', 'branch', 'items', 'jobCard', 'validations'])->findOrFail($id);
    }

    public function updateOrder(int $id, array $data): Order
    {
        $order = Order::findOrFail($id);
        $order->update($data);

        // Recalculate balance on update
        if (isset($data['advance_received']) || isset($data['total_amount'])) {
            $order->update([
                'balance_amount' => $order->total_amount - $order->advance_received,
            ]);
        }

        return $order->fresh();
    }

    public function deleteOrder(int $id): void
    {
        Order::findOrFail($id)->delete();
    }

    public function restoreOrder(int $id): Order
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->restore();

        return $order;
    }

    /**
     * Commercially lock an order (freeze financials).
     */
    public function lockCommercial(int $id): Order
    {
        $order = Order::findOrFail($id);
        $order->update([
            'is_commercial_locked'  => 1,
            'commercial_locked_at'  => now(),
            'commercial_locked_by'  => auth()->id(),
        ]);

        return $order->fresh();
    }
}
