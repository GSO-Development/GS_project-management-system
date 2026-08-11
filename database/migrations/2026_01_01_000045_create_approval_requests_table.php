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
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->enum('request_type', [
                'new_project_plan',
                'updated_wbs_plan',
                'wbs_baseline',
                'deadline_extension',
                'budget_change',
                'scope_change',
                'project_completion',
                'project_cancellation',
            ]);
            $table->foreignId('requested_by')->constrained('users')->restrictOnDelete();
            $table->json('current_value')->nullable();
            $table->json('requested_value')->nullable();
            $table->text('reason');
            $table->enum('status', [
                'draft',
                'pending',
                'approved',
                'rejected',
                'revision_required',
                'cancelled',
            ])->default('draft');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_comment')->nullable();
            $table->timestamps();

            $table->index('project_id');
            $table->index('status');
            $table->index('requested_by');
        });

        Schema::create('approval_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_request_id')->constrained('approval_requests')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('original_name');
            $table->string('storage_path');
            $table->bigInteger('file_size');
            $table->string('mime_type');
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_documents');
        Schema::dropIfExists('approval_requests');
    }
};
