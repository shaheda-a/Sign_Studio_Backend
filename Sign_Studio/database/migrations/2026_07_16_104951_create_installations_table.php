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
        Schema::create('installations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('dispatch_id')->nullable()->constrained('dispatches')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('installation_number', 50)->unique();
            $table->date('scheduled_date')->nullable();
            $table->date('actual_date')->nullable();
            $table->string('check_in_gps', 100)->nullable();
            $table->string('check_out_gps', 100)->nullable();
            $table->string('customer_otp', 10)->nullable();
            $table->boolean('customer_otp_verified')->default(0);
            $table->text('punch_list')->nullable();
            $table->string('status', 30)->default('pending');
            $table->integer('completion_percentage')->default(0);
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
        Schema::dropIfExists('installations');
    }
};
