<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('receipt_number', 50)->unique();
            $table->decimal('amount_received', 15, 2)->default(0);
            $table->string('payment_mode', 50)->nullable();
            $table->string('transaction_ref', 200)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('cheque_number', 50)->nullable();
            $table->date('date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
