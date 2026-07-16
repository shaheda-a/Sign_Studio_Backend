<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->date('reminder_date')->nullable();
            $table->string('channel', 30)->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            // DBML lacks softDeletes for payment_reminders, but let's add for consistency if required, I will follow DBML
            // DBML doesn't have deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_reminders');
    }
};
