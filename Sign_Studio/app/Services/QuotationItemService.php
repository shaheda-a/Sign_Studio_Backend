<?php

namespace App\Services;

use App\Models\QuotationItem;

class QuotationItemService
{
    public function getItems(array $filters = [], int $perPage = 15): mixed
    {
        $query = QuotationItem::query();

        if (!empty($filters['quotation_id'])) {
            $query->where('quotation_id', $filters['quotation_id']);
        }

        return $perPage === -1 ? $query->orderBy('sort_order')->get() : $query->orderBy('sort_order')->paginate($perPage);
    }

    public function createItem(array $data): QuotationItem
    {
        $data['created_by'] = auth()->id();

        // Auto-calculate tax and total
        $qty             = $data['qty'] ?? 1;
        $unitPrice       = $data['unit_price'] ?? 0;
        $discountPct     = $data['discount_percent'] ?? 0;
        $taxRate         = $data['tax_rate'] ?? 18;
        $lineBase        = ($qty * $unitPrice) * (1 - ($discountPct / 100));
        $data['tax_amount'] = round($lineBase * ($taxRate / 100), 2);
        $data['total']      = round($lineBase + $data['tax_amount'], 2);

        return QuotationItem::create($data);
    }

    public function getItemById(int $id): QuotationItem
    {
        return QuotationItem::findOrFail($id);
    }

    public function updateItem(int $id, array $data): QuotationItem
    {
        $item = QuotationItem::findOrFail($id);

        // Recalculate on update
        $qty         = $data['qty']            ?? $item->qty;
        $unitPrice   = $data['unit_price']     ?? $item->unit_price;
        $discountPct = $data['discount_percent'] ?? $item->discount_percent;
        $taxRate     = $data['tax_rate']        ?? $item->tax_rate;
        $lineBase    = ($qty * $unitPrice) * (1 - ($discountPct / 100));

        $data['tax_amount'] = round($lineBase * ($taxRate / 100), 2);
        $data['total']      = round($lineBase + $data['tax_amount'], 2);

        $item->update($data);

        return $item->fresh();
    }

    public function deleteItem(int $id): void
    {
        QuotationItem::findOrFail($id)->delete();
    }
}
