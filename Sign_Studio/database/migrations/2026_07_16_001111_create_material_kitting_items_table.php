<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_kitting_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kitting_id')->constrained('material_kitting')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->decimal('required_qty', 10, 3)->default(0);
            $table->decimal('issued_qty', 10, 3)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_kitting_items');
    }
};
