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
        Schema::create('project_status_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('title');
            $table->text('summary');
            $table->string('reporting_period')->nullable()->comment('e.g. "Week 1 - 2025-01-01 to 2025-01-07"');
            $table->integer('current_progress')->default(0);
            $table->text('work_completed')->nullable();
            $table->text('current_blockers')->nullable();
            $table->text('current_risks')->nullable();
            $table->text('next_steps')->nullable();
            $table->enum('updated_status', [
                'draft',
                'not_started',
                'planning',
                'in_progress',
                'on_hold',
                'under_review',
                'completed',
                'delayed',
                'cancelled',
            ])->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index('project_id');
        });

        Schema::create('project_risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('category')->nullable()->comment('Technical, Financial, Schedule, Resource, External');
            $table->enum('probability', ['low', 'medium', 'high'])->default('medium');
            $table->enum('impact', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->integer('risk_score')->default(0)->comment('probability_score * impact_score');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('mitigation_plan')->nullable();
            $table->text('contingency_plan')->nullable();
            $table->enum('status', ['open', 'monitoring', 'mitigated', 'closed'])->default('open');
            $table->timestamps();

            $table->index('project_id');
        });

        Schema::create('task_blockers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wbs_item_id')->constrained('wbs_items')->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->restrictOnDelete();
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('resolution')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved'])->default('open');
            $table->timestamps();

            $table->index('wbs_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_blockers');
        Schema::dropIfExists('project_risks');
        Schema::dropIfExists('project_status_updates');
    }
};
