<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Single source of truth for WBS hierarchy: Phases, Work Packages, Tasks, Subtasks, Milestones
     */
    public function up(): void
    {
        Schema::create('wbs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('wbs_items')->nullOnDelete();
            $table->string('wbs_code')->nullable()->comment('Auto-generated: 1.1.1.1');
            $table->enum('item_type', ['phase', 'work_package', 'task', 'subtask', 'milestone'])->default('task');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('duration')->nullable()->comment('In working days');
            $table->enum('status', [
                'backlog',
                'not_started',
                'in_progress',
                'blocked',
                'under_review',
                'completed',
                'on_hold',
                'cancelled',
            ])->default('not_started');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->integer('progress')->default(0)->comment('0-100, manual for leaf nodes');
            $table->decimal('estimated_hours', 10, 2)->nullable();
            $table->decimal('actual_hours', 10, 2)->default(0);
            $table->decimal('weight', 8, 4)->default(1)->comment('For weighted progress calculation');
            $table->boolean('is_milestone')->default(false);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('parent_id');
            $table->index('assigned_user_id');
            $table->index(['project_id', 'wbs_code']);
            $table->index(['project_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wbs_items');
    }
};
