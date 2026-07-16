<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\Expense;
use App\Models\CustomerLedger;
use App\Models\VendorLedger;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    public function generateInvoice(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['invoice_number'] = 'INV-' . strtoupper(uniqid());
            $invoice = Invoice::create($data);
            
            CustomerLedger::create([
                'customer_id' => $invoice->customer_id,
                'type' => 'debit',
                'reference_type' => 'invoice',
                'reference_id' => $invoice->id,
                'amount' => $invoice->total,
                'description' => 'Invoice ' . $invoice->invoice_number,
                'transaction_date' => $invoice->invoice_date ?? now(),
                'created_by' => auth()->id() ?? 1,
            ]);

            return $invoice;
        });
    }

    public function recordReceipt(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['receipt_number'] = 'RCT-' . strtoupper(uniqid());
            $receipt = Receipt::create($data);

            if ($receipt->invoice_id) {
                $invoice = Invoice::findOrFail($receipt->invoice_id);
                $invoice->amount_paid += $receipt->amount_received;
                $invoice->balance_due = $invoice->total - $invoice->amount_paid;
                $invoice->status = $invoice->balance_due <= 0 ? 'paid' : 'partial';
                $invoice->save();
            }

            CustomerLedger::create([
                'customer_id' => $receipt->customer_id,
                'type' => 'credit',
                'reference_type' => 'receipt',
                'reference_id' => $receipt->id,
                'amount' => $receipt->amount_received,
                'description' => 'Receipt ' . $receipt->receipt_number,
                'transaction_date' => $receipt->date ?? now(),
                'created_by' => auth()->id() ?? 1,
            ]);

            return $receipt;
        });
    }
}
