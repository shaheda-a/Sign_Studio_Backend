<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_asset_id')->constrained('service_assets')->onDelete('cascade');
            $table->foreignId('ticket_id')->nullable()->constrained('service_tickets')->onDelete('set null');
            $table->string('service_type', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('serviced_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('serviced_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            // No soft delete in DBML, but let's add for safety
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_history');
    }
};
