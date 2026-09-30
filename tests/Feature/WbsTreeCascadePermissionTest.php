<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\WbsItem;
use App\Livewire\WbsTree;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WbsTreeCascadePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_assigned_project_manager_can_confirm_cascade_reschedule()
    {
        $pm = User::create([
            'name' => 'Project Manager User',
            'email' => 'pm_test@georgesteuart.com',
            'password' => bcrypt('password'),
        ]);

        $sub = \App\Models\Subsidiary::create([
            'name' => 'GS Test Subsidiary',
            'code' => 'GST',
            'is_active' => true,
        ]);

        $project = Project::create([
            'code' => 'GST-PRJ-001',
            'name' => 'Test Project',
            'subsidiary_id' => $sub->id,
            'project_manager_id' => $pm->id,
            'pm_accepted' => true,
            'status' => 'in_progress',
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(30)->toDateString(),
        ]);

        $task1 = WbsItem::create([
            'project_id' => $project->id,
            'title' => 'Task 1',
            'wbs_code' => '1',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'status' => 'in_progress',
            'progress' => 0,
            'created_by' => $pm->id,
        ]);

        $task2 = WbsItem::create([
            'project_id' => $project->id,
            'title' => 'Task 2',
            'wbs_code' => '2',
            'start_date' => now()->addDays(6)->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'status' => 'not_started',
            'progress' => 0,
            'created_by' => $pm->id,
        ]);

        $this->actingAs($pm);

        Livewire::test(WbsTree::class, ['project' => $project])
            ->set('cascadeSourceTaskId', $task1->id)
            ->set('cascadeDaysDelta', 2)
            ->call('confirmCascadeOnly')
            ->assertDispatched('toast')
            ->assertDispatched('wbsUpdated');
    }

    public function test_project_manager_can_update_single_task_date_without_cascade()
    {
        $pm = User::create([
            'name' => 'Project Manager User 2',
            'email' => 'pm_test2@georgesteuart.com',
            'password' => bcrypt('password'),
        ]);

        $sub = \App\Models\Subsidiary::create([
            'name' => 'GS Test Subsidiary 2',
            'code' => 'GST2',
            'is_active' => true,
        ]);

        $project = Project::create([
            'code' => 'GST-PRJ-002',
            'name' => 'Test Project 2',
            'subsidiary_id' => $sub->id,
            'project_manager_id' => $pm->id,
            'pm_accepted' => true,
            'status' => 'in_progress',
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(30)->toDateString(),
        ]);

        $task1 = WbsItem::create([
            'project_id' => $project->id,
            'title' => 'Task 1',
            'wbs_code' => '1',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'status' => 'not_started',
            'progress' => 0,
            'created_by' => $pm->id,
        ]);

        $this->actingAs($pm);

        Livewire::test(WbsTree::class, ['project' => $project])
            ->call('openEditItemModal', $task1->id)
            ->set('start_date', now()->addDays(10)->toDateString())
            ->set('end_date', now()->addDays(15)->toDateString())
            ->call('saveItem')
            ->call('confirmThisTaskOnly')
            ->assertDispatched('toast')
            ->assertDispatched('wbsUpdated');

        $this->assertEquals(now()->addDays(15)->toDateString(), $task1->fresh()->end_date->toDateString());
    }
}
