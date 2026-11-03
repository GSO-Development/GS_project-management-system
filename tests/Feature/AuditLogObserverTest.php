<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Livewire\MyTasks;
use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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
            ->where('action', 'changed_task_status')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($user->id, $log->user_id);
        $this->assertEquals('wbs_items', $log->module);
        $this->assertEquals('not_started', $log->previous_values['status']);
        $this->assertEquals('in_progress', $log->new_values['status']);
        $this->assertArrayNotHasKey('updated_at', $log->new_values);
        $this->assertSame(1, ActivityLog::where('record_type', WbsItem::class)
            ->where('record_id', $task->id)
            ->count());
    }

    public function test_task_progress_and_assignment_changes_have_explicit_actions(): void
    {
        $user = User::factory()->create(['subsidiary_id' => $this->subsidiary->id]);
        $assignee = User::factory()->create(['subsidiary_id' => $this->subsidiary->id]);
        $project = Project::create([
            'subsidiary_id' => $this->subsidiary->id,
            'name' => 'Task Audit Project',
            'code' => 'TASK-AUDIT',
            'status' => ProjectStatus::IN_PROGRESS,
        ]);
        $task = WbsItem::create([
            'project_id' => $project->id,
            'title' => 'Audited Task',
            'wbs_code' => '1.1',
            'status' => 'not_started',
            'progress' => 0,
        ]);

        ActivityLog::query()->delete();
        $this->actingAs($user);

        $task->update(['progress' => 25]);
        $task->update(['assigned_user_id' => $assignee->id]);

        $this->assertDatabaseHas('activity_logs', [
            'record_type' => WbsItem::class,
            'record_id' => $task->id,
            'action' => 'updated_task_progress',
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'record_type' => WbsItem::class,
            'record_id' => $task->id,
            'action' => 'reassigned_task',
        ]);
    }

    public function test_my_tasks_status_change_is_recorded_once_with_actor_and_diff(): void
    {
        $user = User::factory()->create(['subsidiary_id' => $this->subsidiary->id]);
        $project = Project::create([
            'subsidiary_id' => $this->subsidiary->id,
            'name' => 'My Tasks Audit Project',
            'code' => 'MY-AUDIT',
            'status' => ProjectStatus::IN_PROGRESS,
        ]);
        $task = WbsItem::create([
            'project_id' => $project->id,
            'assigned_user_id' => $user->id,
            'title' => 'Change status from My Tasks',
            'wbs_code' => '1.1',
            'status' => 'not_started',
            'progress' => 0,
        ]);

        ActivityLog::query()->delete();

        Livewire::actingAs($user)
            ->test(MyTasks::class)
            ->call('updateStatus', $task->id, 'in_progress')
            ->assertDispatched('wbsUpdated');

        $logs = ActivityLog::where('record_type', WbsItem::class)
            ->where('record_id', $task->id)
            ->get();

        $this->assertCount(1, $logs);
        $this->assertSame('changed_task_status', $logs->first()->action);
        $this->assertSame($user->id, $logs->first()->user_id);
        $this->assertSame('not_started', $logs->first()->previous_values['status']);
        $this->assertSame('in_progress', $logs->first()->new_values['status']);
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
