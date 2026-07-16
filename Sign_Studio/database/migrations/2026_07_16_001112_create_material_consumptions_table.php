<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('production_plan_id')->nullable()->constrained('production_plans')->onDelete('set null');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->decimal('consumed_qty', 10, 3)->default(0);
            $table->decimal('wastage_qty', 10, 3)->default(0);
            $table->foreignId('stage_id')->nullable()->constrained('production_stages')->onDelete('set null');
            $table->unsignedBigInteger('consumed_by')->nullable();
            $table->timestamp('consumed_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_consumptions');
    }
};
