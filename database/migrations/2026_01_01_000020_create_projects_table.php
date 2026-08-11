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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('subsidiary_id')->constrained('subsidiaries')->cascadeOnDelete();
            $table->string('category')->nullable();
            $table->foreignId('project_manager_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', [
                'draft',
                'not_started',
                'planning',
                'in_progress',
                'on_hold',
                'under_review',
                'completed',
                'delayed',
                'cancelled',
                'archived',
            ])->default('draft');
            $table->enum('health', ['on_track', 'at_risk', 'delayed', 'critical'])->default('on_track');
            $table->integer('overall_progress')->default(0)->comment('0-100');
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->decimal('actual_cost', 15, 2)->default(0);
            $table->decimal('estimated_hours', 10, 2)->nullable();
            $table->decimal('actual_hours', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('subsidiary_id');
            $table->index('project_manager_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
