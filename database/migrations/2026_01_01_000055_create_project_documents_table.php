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
        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('wbs_item_id')->nullable()->constrained('wbs_items')->nullOnDelete();
            $table->string('file_name')->comment('Secure generated filename');
            $table->string('original_name')->comment('Original uploaded filename');
            $table->string('mime_type');
            $table->bigInteger('file_size');
            $table->string('storage_path')->comment('Private storage path');
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->integer('version')->default(1);
            $table->foreignId('parent_document_id')->nullable()->constrained('project_documents')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('wbs_item_id');
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_documents');
    }
};
