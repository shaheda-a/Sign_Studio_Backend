<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->string('ticket_number', 50)->unique();
            $table->text('issue_description')->nullable();
            $table->string('issue_type', 100)->nullable();
            $table->string('priority', 20)->default('normal');
            $table->boolean('is_warranty')->default(false);
            $table->foreignId('warranty_card_id')->nullable()->constrained('warranty_cards')->onDelete('set null');
            $table->string('status', 30)->default('open');
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_tickets');
    }
};
