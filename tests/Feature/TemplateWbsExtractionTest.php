<?php

namespace Tests\Feature;

use App\Enums\ItemType;
use App\Livewire\WbsTree;
use App\Models\Project;
use App\Models\ProjectTemplate;
use App\Models\TemplateTask;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\TemplateWbsGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TemplateWbsExtractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_template_tasks_with_and_without_subtasks_extract_correctly(): void
    {
        $role = \Spatie\Permission\Models\Role::findOrCreate('super_admin', 'web');
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $this->actingAs($user);

        $subsidiary = \App\Models\Subsidiary::create(['name' => 'Tech Corp', 'code' => 'TC']);
        $project = Project::create([
            'name' => 'Alpha Project',
            'code' => 'ALP-001',
            'subsidiary_id' => $subsidiary->id,
            'project_manager_id' => $user->id,
            'created_by' => $user->id,
            'pm_accepted' => true,
            'status' => 'in_progress',
            'start_date' => '2026-10-01',
            'target_deadline' => '2026-12-31',
        ]);

        // Create a template with:
        // 1. Task A (has 2 subtasks)
        // 2. Task B (has NO subtasks)
        $template = ProjectTemplate::create([
            'name' => 'Custom Delivery Template',
            'description' => 'Test blueprint',
        ]);

        $taskA = TemplateTask::create([
            'project_template_id' => $template->id,
            'parent_id' => null,
            'name' => 'Phase A - With Subtasks',
            'duration' => 5,
            'unit' => 'days',
            'order_index' => 1,
        ]);

        TemplateTask::create([
            'project_template_id' => $template->id,
            'parent_id' => $taskA->id,
            'name' => 'Subtask A.1',
            'duration' => 2,
            'unit' => 'days',
            'order_index' => 1,
        ]);

        TemplateTask::create([
            'project_template_id' => $template->id,
            'parent_id' => $taskA->id,
            'name' => 'Subtask A.2',
            'duration' => 3,
            'unit' => 'days',
            'order_index' => 2,
        ]);

        $taskB = TemplateTask::create([
            'project_template_id' => $template->id,
            'parent_id' => null,
            'name' => 'Task B - Standalone Without Subtasks',
            'duration' => 4,
            'unit' => 'days',
            'order_index' => 2,
        ]);

        // Generate WBS items
        $service = app(TemplateWbsGeneratorService::class);
        $count = $service->generate($project, $template->id);

        $this->assertEquals(4, $count); // Task A, Subtask A.1, Subtask A.2, Task B

        // Verify Task A
        $wbsA = WbsItem::where('project_id', $project->id)
            ->where('title', 'Phase A - With Subtasks')
            ->first();
        $this->assertNotNull($wbsA);
        $this->assertEquals(ItemType::PHASE, $wbsA->item_type);
        $this->assertNull($wbsA->parent_id);

        // Verify subtasks of A
        $subtasksA = WbsItem::where('project_id', $project->id)
            ->where('parent_id', $wbsA->id)
            ->get();
        $this->assertCount(2, $subtasksA);
        foreach ($subtasksA as $sub) {
            $this->assertEquals(ItemType::SUBTASK, $sub->item_type);
        }

        // Verify Task B (standalone)
        $wbsB = WbsItem::where('project_id', $project->id)
            ->where('title', 'Task B - Standalone Without Subtasks')
            ->first();
        $this->assertNotNull($wbsB);
        $this->assertEquals(ItemType::TASK, $wbsB->item_type);
        $this->assertNull($wbsB->parent_id);

        // Verify Task B has NO child subtasks
        $subtasksB = WbsItem::where('project_id', $project->id)
            ->where('parent_id', $wbsB->id)
            ->get();
        $this->assertCount(0, $subtasksB);
    }

    public function test_wbs_tree_component_applies_template_via_modal(): void
    {
        $role = \Spatie\Permission\Models\Role::findOrCreate('super_admin', 'web');
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $this->actingAs($user);

        $subsidiary = \App\Models\Subsidiary::create(['name' => 'Beta Corp', 'code' => 'BC']);
        $project = Project::create([
            'name' => 'Beta Project',
            'code' => 'BET-001',
            'subsidiary_id' => $subsidiary->id,
            'project_manager_id' => $user->id,
            'created_by' => $user->id,
            'pm_accepted' => true,
            'status' => 'in_progress',
            'start_date' => '2026-10-01',
            'target_deadline' => '2026-12-31',
        ]);

        $template = ProjectTemplate::create([
            'name' => 'Standard Blueprint',
        ]);

        $rootTask = TemplateTask::create([
            'project_template_id' => $template->id,
            'parent_id' => null,
            'name' => 'Planning Phase',
            'duration' => 5,
            'unit' => 'days',
            'order_index' => 1,
        ]);

        TemplateTask::create([
            'project_template_id' => $template->id,
            'parent_id' => $rootTask->id,
            'name' => 'Scope Definition',
            'duration' => 2,
            'unit' => 'days',
            'order_index' => 1,
        ]);

        Livewire::test(WbsTree::class, ['project' => $project])
            ->call('openTemplateModal')
            ->assertSet('showTemplateModal', true)
            ->call('selectTemplate', $template->id)
            ->assertSet('selectedTemplateId', $template->id)
            ->call('applyTemplate')
            ->assertSet('showTemplateModal', false)
            ->assertDispatched('toast')
            ->assertDispatched('wbsUpdated');

        $this->assertEquals(2, WbsItem::where('project_id', $project->id)->count());
        $this->assertEquals($template->id, $project->fresh()->template_id);
    }

    public function test_wbs_tree_applies_template_with_replace_option(): void
    {
        $role = \Spatie\Permission\Models\Role::findOrCreate('super_admin', 'web');
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $this->actingAs($user);

        $subsidiary = \App\Models\Subsidiary::create(['name' => 'Gamma Corp', 'code' => 'GC']);
        $project = Project::create([
            'name' => 'Gamma Project',
            'code' => 'GAM-001',
            'subsidiary_id' => $subsidiary->id,
            'project_manager_id' => $user->id,
            'created_by' => $user->id,
            'pm_accepted' => true,
            'status' => 'in_progress',
            'start_date' => '2026-10-01',
            'target_deadline' => '2026-12-31',
        ]);

        // Create an existing task in this project
        WbsItem::create([
            'project_id' => $project->id,
            'title' => 'Old Legacy Task',
            'status' => 'not_started',
            'sort_order' => 1,
            'item_type' => 'task',
        ]);
        $this->assertEquals(1, WbsItem::where('project_id', $project->id)->count());

        $template = ProjectTemplate::create(['name' => 'New Blueprint']);
        TemplateTask::create([
            'project_template_id' => $template->id,
            'name' => 'Fresh Blueprint Task',
            'duration' => 3,
            'unit' => 'days',
            'order_index' => 1,
        ]);

        // Test with replaceExistingTasksOnTemplate = true
        Livewire::test(WbsTree::class, ['project' => $project])
            ->call('openTemplateModal')
            ->call('selectTemplate', $template->id)
            ->set('replaceExistingTasksOnTemplate', true)
            ->call('applyTemplate');

        // Only the fresh task should exist now
        $items = WbsItem::where('project_id', $project->id)->get();
        $this->assertCount(1, $items);
        $this->assertEquals('Fresh Blueprint Task', $items->first()->title);
        $this->assertFalse(WbsItem::where('project_id', $project->id)->where('title', 'Old Legacy Task')->exists());
    }
}
