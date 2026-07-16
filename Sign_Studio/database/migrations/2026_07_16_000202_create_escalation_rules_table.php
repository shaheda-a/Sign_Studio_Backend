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
        $tableNames = config('permission.table_names');
        $rolesTable = $tableNames['roles'] ?? 'roles';

        Schema::create('escalation_rules', function (Blueprint $table) use ($rolesTable) {
            $table->id();
            $table->string('module', 100);
            $table->integer('trigger_after_hours');
            $table->foreignId('escalate_to_role_id')->constrained($rolesTable)->onDelete('cascade');
            $table->foreignId('notify_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('escalation_rules');
    }
};
