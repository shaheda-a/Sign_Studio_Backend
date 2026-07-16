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
        Schema::create('installation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained('installations')->cascadeOnDelete();
            $table->integer('quality_score')->nullable();
            $table->integer('timeliness_score')->nullable();
            $table->integer('customer_satisfaction')->nullable();
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->foreignId('scored_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installation_scores');
    }
};
