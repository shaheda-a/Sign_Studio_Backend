<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('delegations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delegated_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('delegated_to')->constrained('users')->onDelete('cascade');
            $table->text('task_description');
            $table->string('priority', 20)->default('normal');
            $table->date('due_date')->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('delegations'); }
};
