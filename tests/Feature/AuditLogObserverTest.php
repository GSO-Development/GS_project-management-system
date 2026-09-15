<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogObserverTest extends TestCase
{
    use RefreshDatabase;

    protected Subsidiary $subsidiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subsidiary = Subsidiary::create([
            'name' => 'Test Subsidiary',
            'code' => 'SUB-01',
        ]);
    }

    public function test_project_creation_automatically_records_audit_log(): void
    {
        $user = User::factory()->create(['subsidiary_id' => $this->subsidiary->id]);

        $this->actingAs($user);
        $project = Project::create([
            'subsidiary_id' => $this->subsidiary->id,
            'name' => 'Observed Audit Project',
            'code' => 'AUDIT-001',
            'status' => ProjectStatus::IN_PROGRESS,
        ]);

        $log = ActivityLog::where('record_type', Project::class)
            ->where('record_id', $project->id)
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($user->id, $log->user_id);
        $this->assertEquals('created_project', $log->action);
        $this->assertEquals('projects', $log->module);
        $this->assertEquals('Observed Audit Project', $log->new_values['name']);
    }

    public function test_wbs_item_update_automatically_records_audit_log_with_diff(): void
    {
        $user = User::factory()->create(['subsidiary_id' => $this->subsidiary->id]);

        $project = Project::create([
            'subsidiary_id' => $this->subsidiary->id,
            'name' => 'WBS Parent Project',
            'code' => 'WBS-PRJ',
            'status' => ProjectStatus::IN_PROGRESS,
        ]);

        $task = WbsItem::create([
            'project_id' => $project->id,
            'title' => 'Initial Task Title',
            'wbs_code' => '1.1',
            'status' => 'not_started',
            'progress' => 0,
        ]);

        // Reset log count to isolate update event
        ActivityLog::query()->delete();

        $this->actingAs($user);
        $task->update([
            'status' => 'in_progress',
            'progress' => 50,
        ]);

        $log = ActivityLog::where('record_type', WbsItem::class)
            ->where('record_id', $task->id)
            ->where('action', 'updated_wbs_item')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($user->id, $log->user_id);
        $this->assertEquals('wbs_items', $log->module);
        $this->assertEquals('not_started', $log->previous_values['status']);
        $this->assertEquals('in_progress', $log->new_values['status']);
    }

    public function test_comment_creation_automatically_records_audit_log(): void
    {
        $user = User::factory()->create(['subsidiary_id' => $this->subsidiary->id]);
        $project = Project::create([
            'subsidiary_id' => $this->subsidiary->id,
            'name' => 'Comment Project',
            'code' => 'CMT-001',
            'status' => ProjectStatus::IN_PROGRESS,
        ]);

        $this->actingAs($user);
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_type' => Project::class,
            'commentable_id' => $project->id,
            'content' => 'Audit log verification comment.',
        ]);

        $log = ActivityLog::where('record_type', Comment::class)
            ->where('record_id', $comment->id)
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($user->id, $log->user_id);
        $this->assertEquals('added_comment', $log->action);
    }
}
