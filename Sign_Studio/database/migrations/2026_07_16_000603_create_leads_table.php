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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('source_id')->nullable()->constrained('lead_sources')->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('architect_id')->nullable()->constrained('architects')->onDelete('set null');
            $table->foreignId('contractor_id')->nullable()->constrained('contractors')->onDelete('set null');
            $table->foreignId('pipeline_stage_id')->nullable()->constrained('pipeline_stages')->onDelete('set null');
            $table->string('lead_number', 50)->unique();
            $table->string('title', 300);
            $table->string('status', 50)->default('new');
            $table->integer('lead_score')->default(0);
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->date('expected_close_date')->nullable();
            $table->text('lost_reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
