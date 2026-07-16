<?php

namespace App\Services;

use App\Models\Quotation;
use Illuminate\Support\Str;

class QuotationService
{
    public function getQuotations(array $filters = [], int $perPage = 15): mixed
    {
        $query = Quotation::query();

        if (!empty($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $perPage === -1 ? $query->latest()->get() : $query->latest()->paginate($perPage);
    }

    public function createQuotation(array $data): Quotation
    {
        $data['quote_number'] = $data['quote_number'] ?? 'QT-' . strtoupper(Str::random(6));
        $data['created_by']   = auth()->id();

        return Quotation::create($data);
    }

    public function getQuotationById(int $id): Quotation
    {
        return Quotation::with(['lead', 'customer', 'design', 'items', 'paymentLinks'])->findOrFail($id);
    }

    public function updateQuotation(int $id, array $data): Quotation
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->update($data);

        return $quotation->fresh();
    }

    public function deleteQuotation(int $id): void
    {
        Quotation::findOrFail($id)->delete();
    }

    public function restoreQuotation(int $id): Quotation
    {
        $quotation = Quotation::withTrashed()->findOrFail($id);
        $quotation->restore();

        return $quotation;
    }

    /**
     * Recalculate totals from line items and update the quotation header.
     */
    public function recalculateTotals(int $id): Quotation
    {
        $quotation = Quotation::with('items')->findOrFail($id);

        $subTotal      = $quotation->items->sum('total');
        $discountAmount = $quotation->discount_amount ?? 0;
        $taxableAmount  = $subTotal - $discountAmount;
        $taxAmount      = $quotation->items->sum('tax_amount');
        $grandTotal     = $taxableAmount + $taxAmount;

        $quotation->update([
            'sub_total'   => $subTotal,
            'tax_amount'  => $taxAmount,
            'grand_total' => $grandTotal,
        ]);

        return $quotation->fresh();
    }

    /**
     * Convert a quotation to the next version (copy & increment version).
     */
    public function newVersion(int $id): Quotation
    {
        $original = Quotation::with('items')->findOrFail($id);

        $newQuotation = $original->replicate();
        $newQuotation->quote_number = 'QT-' . strtoupper(Str::random(6));
        $newQuotation->version      = $original->version + 1;
        $newQuotation->status       = 'draft';
        $newQuotation->sent_at      = null;
        $newQuotation->approved_at  = null;
        $newQuotation->created_by   = auth()->id();
        $newQuotation->save();

        foreach ($original->items as $item) {
            $newItem = $item->replicate();
            $newItem->quotation_id = $newQuotation->id;
            $newItem->save();
        }

        return $newQuotation->fresh();
    }
}
