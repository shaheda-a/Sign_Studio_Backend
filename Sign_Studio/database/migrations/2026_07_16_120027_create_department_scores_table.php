<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('department_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('efficiency_score', 5, 2)->default(0);
            $table->decimal('output_score', 5, 2)->default(0);
            $table->decimal('overall_score', 5, 2)->default(0);
            $table->foreignId('scored_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('department_scores'); }
};
