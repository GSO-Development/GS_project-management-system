<?php

namespace Tests\Feature;

use App\Livewire\ProjectIndex;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectDeletionModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_open_and_cancel_delete_confirmation_modal(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super_admin');

        $subsidiary = Subsidiary::create(['code' => 'DELTEST', 'name' => 'Delete Test Entity']);
        $project = Project::create([
            'code' => 'PRJ-MODAL-1',
            'name' => 'Test Project Modal',
            'subsidiary_id' => $subsidiary->id,
            'project_manager_id' => $admin->id,
            'created_by' => $admin->id,
            'priority' => 'high',
            'status' => 'planning',
        ]);

        WbsItem::create([
            'project_id' => $project->id,
            'wbs_code' => '1.0',
            'title' => 'Deliverable 1',
            'item_type' => 'task',
            'progress_percentage' => 0,
            'created_by' => $admin->id,
        ]);

        Livewire::actingAs($admin)
            ->test(ProjectIndex::class)
            ->assertSet('showDeleteModal', false)
            ->call('confirmDelete', $project->id)
            ->assertSet('showDeleteModal', true)
            ->assertSet('projectToDeleteId', $project->id)
            ->assertSet('projectToDeleteName', 'Test Project Modal')
            ->assertSet('projectToDeleteCode', 'PRJ-MODAL-1')
            ->assertSet('projectToDeleteTasksCount', 1)
            ->assertSee('Delete Project Permanently?')
            ->assertSee('PRJ-MODAL-1')
            ->call('cancelDelete')
            ->assertSet('showDeleteModal', false)
            ->assertSet('projectToDeleteId', null)
            ->assertDontSee('Delete Project Permanently?');
    }

    public function test_super_admin_can_confirm_deletion_and_purge_project_with_tasks(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super_admin');

        $subsidiary = Subsidiary::create(['code' => 'DELTEST2', 'name' => 'Delete Test Entity 2']);
        $project = Project::create([
            'code' => 'PRJ-PURGE-1',
            'name' => 'Project to Purge',
            'subsidiary_id' => $subsidiary->id,
            'project_manager_id' => $admin->id,
            'created_by' => $admin->id,
            'priority' => 'critical',
            'status' => 'in_progress',
        ]);

        $task = WbsItem::create([
            'project_id' => $project->id,
            'wbs_code' => '1.0',
            'title' => 'Important Task',
            'item_type' => 'task',
            'progress_percentage' => 50,
            'created_by' => $admin->id,
        ]);

        Livewire::actingAs($admin)
            ->test(ProjectIndex::class)
            ->call('confirmDelete', $project->id)
            ->assertSet('showDeleteModal', true)
            ->call('deleteProject')
            ->assertSet('showDeleteModal', false)
            ->assertDispatched('toast');

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('wbs_items', ['id' => $task->id]);
    }

    public function test_edit_project_details_modal_renders_properly(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super_admin');

        $subsidiary = Subsidiary::create(['code' => 'WSMOD', 'name' => 'Workspace Modal Sub']);
        $project = Project::create([
            'code' => 'WS-MOD-01',
            'name' => 'Workspace Modal Project',
            'subsidiary_id' => $subsidiary->id,
            'project_manager_id' => $admin->id,
            'created_by' => $admin->id,
            'priority' => 'high',
            'status' => 'in_progress',
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\ProjectWorkspace::class, ['project' => $project])
            ->set('showEditProjectModal', true)
            ->assertSee('Edit Project Details &amp; Specifications', false)
            ->assertSee('Project Identity &amp; Classification', false)
            ->assertSee('Save All Project Details');
    }
}
