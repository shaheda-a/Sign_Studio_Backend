<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_escalations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('escalated_from')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('escalated_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reason')->nullable();
            $table->integer('level')->default(1);
            $table->string('status', 30)->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_escalations');
    }
};
