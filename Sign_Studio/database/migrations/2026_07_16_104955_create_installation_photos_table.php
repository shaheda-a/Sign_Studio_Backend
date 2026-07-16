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
        Schema::create('installation_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained('installations')->cascadeOnDelete();
            $table->string('type', 30);
            $table->string('file_path', 500);
            $table->text('caption')->nullable();
            $table->timestamp('uploaded_at')->nullable();
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
        Schema::dropIfExists('installation_photos');
    }
};
