<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code', 50)->unique();
            $table->string('name', 300);
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->string('sub_category', 100)->nullable();
            $table->string('uom', 30)->nullable();
            $table->integer('reorder_level')->default(0);
            $table->decimal('current_stock', 12, 3)->default(0);
            $table->string('hsn_code', 20)->nullable();
            $table->decimal('tax_rate', 5, 2)->default(18);
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
