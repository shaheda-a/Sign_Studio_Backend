<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('dispatch_number', 50)->unique();
            $table->string('vehicle_number', 30)->nullable();
            $table->string('driver_name', 200)->nullable();
            $table->string('driver_phone', 20)->nullable();
            $table->string('transport_company', 200)->nullable();
            $table->text('transport_details')->nullable();
            $table->string('lr_number', 100)->nullable();
            $table->timestamp('packed_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->date('expected_delivery')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('status', 30)->default('packing');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
