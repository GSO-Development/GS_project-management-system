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
        Schema::create('wbs_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->integer('version_number');
            $table->text('change_summary')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->enum('status', [
                'draft',
                'pending_approval',
                'approved',
                'rejected',
                'revision_required',
            ])->default('draft');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_comments')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'version_number']);
            $table->index('project_id');
        });

        Schema::create('wbs_baselines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('wbs_version_id')->constrained('wbs_versions')->restrictOnDelete();
            $table->integer('baseline_number');
            $table->date('original_start_date')->nullable();
            $table->date('original_deadline')->nullable();
            $table->json('baseline_data')->comment('Snapshot of all WBS items');
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->unique(['project_id', 'baseline_number']);
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wbs_baselines');
        Schema::dropIfExists('wbs_versions');
    }
};
