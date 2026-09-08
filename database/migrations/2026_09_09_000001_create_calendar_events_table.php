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
        if (!Schema::hasTable('calendar_events')) {
            Schema::create('calendar_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
                $table->foreignId('wbs_item_id')->nullable()->constrained('wbs_items')->nullOnDelete();
                $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('event_type')->default('meeting'); // meeting, review, milestone, task, deadline
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->boolean('is_all_day')->default(false);
                $table->string('location')->nullable();
                $table->string('meeting_link')->nullable();
                $table->json('attendees')->nullable(); // Array of user IDs
                $table->string('priority')->default('medium'); // low, medium, high, critical
                $table->string('microsoft_event_id')->nullable()->index();
                $table->timestamp('synced_to_microsoft_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
