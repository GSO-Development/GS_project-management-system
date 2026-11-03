<?php

use App\Livewire\AllTasksViewer;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'KPI', 'name' => 'KPI Filter Test']);
    $makeProject = fn ($code) => Project::create([
        'code' => $code,
        'name' => $code,
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $this->user->id,
        'created_by' => $this->user->id,
        'status' => 'planning',
    ]);
    $this->alpha = $makeProject('KPI-ALPHA');
    $this->beta = $makeProject('KPI-BETA');
    $this->emptyProject = $makeProject('KPI-EMPTY');

    $makeTask = fn ($project, $status, $overdue = false) => WbsItem::create([
        'project_id' => $project->id,
        'title' => $project->code.' '.$status,
        'status' => $status,
        'end_date' => $overdue ? today()->subDay() : today()->addWeek(),
    ]);
    $alphaTasks = [];
    foreach (['backlog', 'not_started', 'in_progress', 'under_review', 'at_risk', 'completed', 'on_hold', 'blocked', 'cancelled'] as $status) {
        $alphaTasks[$status] = $makeTask($this->alpha, $status, in_array($status, ['in_progress', 'completed', 'on_hold', 'cancelled']));
    }
    $betaTasks = [];
    foreach (['not_started', 'in_progress', 'completed', 'blocked'] as $status) {
        $betaTasks[$status] = $makeTask($this->beta, $status, $status === 'blocked');
    }
    $closedRiskTask = $makeTask($this->beta, 'not_started');

    foreach ([[$alphaTasks['not_started'], 'open'], [$alphaTasks['cancelled'], 'open'], [$betaTasks['in_progress'], 'monitoring'], [$closedRiskTask, 'closed']] as [$task, $status]) {
        ProjectRisk::create([
            'project_id' => $task->project_id,
            'wbs_item_id' => $task->id,
            'title' => 'Linked risk',
            'description' => 'Verify project-scoped risk counts.',
            'category' => 'schedule',
            'probability' => 'medium',
            'impact' => 'high',
            'status' => $status,
        ]);
    }

    // Neither deleted tasks nor tasks belonging to deleted projects contribute.
    $makeTask($this->alpha, 'at_risk', true)->delete();
    $deletedProject = $makeProject('KPI-DELETED');
    $makeTask($deletedProject, 'at_risk', true);
    $deletedProject->delete();

    $this->alphaKpi = ['total' => 8, 'not_started' => 2, 'in_progress' => 3, 'completed' => 1, 'on_hold' => 2, 'at_risk' => 2, 'overdue' => 2];
    $this->betaKpi = ['total' => 5, 'not_started' => 2, 'in_progress' => 1, 'completed' => 1, 'on_hold' => 1, 'at_risk' => 1, 'overdue' => 1];
    $this->allKpi = ['total' => 13, 'not_started' => 4, 'in_progress' => 4, 'completed' => 2, 'on_hold' => 3, 'at_risk' => 3, 'overdue' => 3];
});

it('updates every KPI when switching projects and restores all-project totals', function () {
    Livewire::actingAs($this->user)->test(AllTasksViewer::class)
        ->assertViewHas('kpi', $this->allKpi)
        ->call('setPage', 2)
        ->set('projectFilter', (string) $this->alpha->id)
        ->assertViewHas('kpi', $this->alphaKpi)
        ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 8 && $tasks->currentPage() === 1 && $tasks->every(fn ($task) => $task->project_id === $this->alpha->id))
        ->set('projectFilter', (string) $this->beta->id)
        ->assertViewHas('kpi', $this->betaKpi)
        ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 5 && $tasks->every(fn ($task) => $task->project_id === $this->beta->id))
        ->set('projectFilter', (string) $this->emptyProject->id)
        ->assertViewHas('kpi', array_fill_keys(array_keys($this->allKpi), 0))
        ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 0)
        ->set('projectFilter', 'all')
        ->assertViewHas('kpi', $this->allKpi);
});

it('keeps the selected project and its KPI totals when drilling into cards', function () {
    Livewire::actingAs($this->user)->test(AllTasksViewer::class)
        ->set('projectFilter', (string) $this->alpha->id)
        ->call('setStatusFilter', 'in_progress')
        ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 3)
        ->call('setAtRiskFilter')
        ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 2)
        ->call('setOverdueFilter')
        ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 2)
        ->call('setStatusFilter', 'all')
        ->assertSet('projectFilter', (string) $this->alpha->id)
        ->assertSet('riskFilter', 'all')
        ->assertSet('dueFilter', 'all')
        ->assertViewHas('kpi', $this->alphaKpi)
        ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 8)
        ->assertSee('wire:click="setStatusFilter(\'all\')"', false)
        ->call('clearFilters')
        ->assertSet('projectFilter', 'all')
        ->assertViewHas('kpi', $this->allKpi);
});

it('scopes stuck task badges and metrics to the project selected in the header', function () {
    Livewire::actingAs($this->user)->test(AllTasksViewer::class)
        ->assertViewHas('totalStuckTasksCount', 4)
        ->set('projectFilter', (string) $this->alpha->id)
        ->assertViewHas('totalStuckTasksCount', 3)
        ->assertViewHas('blockedTasksOnlyCount', 1)
        ->assertViewHas('overdueTasksOnlyCount', 2)
        ->assertViewHas('onHoldTasksCount', 1)
        ->call('setViewMode', 'stuck')
        ->assertViewHas('stuckTasks', fn ($tasks) => $tasks->count() === 3 && $tasks->every(fn ($task) => $task->project_id === $this->alpha->id))
        ->set('projectFilter', (string) $this->beta->id)
        ->assertViewHas('totalStuckTasksCount', 1)
        ->assertViewHas('overdueTasksOnlyCount', 1)
        ->assertViewHas('stuckTasks', fn ($tasks) => $tasks->count() === 1 && $tasks->first()->project_id === $this->beta->id)
        ->set('projectFilter', (string) $this->emptyProject->id)
        ->assertViewHas('totalStuckTasksCount', 0)
        ->assertViewHas('stuckTasks', fn ($tasks) => $tasks->isEmpty());
});
