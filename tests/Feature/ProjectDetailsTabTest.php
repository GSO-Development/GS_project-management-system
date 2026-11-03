<?php

use App\Livewire\ProjectWorkspace;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->manager = User::factory()->create(['is_active' => true]);
    $this->manager->assignRole('project_manager');
    $this->detailsProject = Project::create([
        'code' => 'DTL-001',
        'name' => 'Details Tab Project',
        'description' => 'Detailed confidential project scope.',
        'subsidiary_id' => Subsidiary::create(['code' => 'DTL', 'name' => 'Details Tab Entity'])->id,
        'project_manager_id' => $this->manager->id,
        'created_by' => $this->manager->id,
        'pm_accepted' => true,
        'status' => 'planning',
        'priority' => 'medium',
        'estimated_budget' => 200000,
        'actual_cost' => 45000,
    ]);
});

test('project manager can open the complete read only details tab by URL', function () {
    $this->actingAs($this->manager)
        ->get(route('projects.show', ['project' => $this->detailsProject, 'tab' => 'details']))
        ->assertOk()
        ->assertSee('aria-labelledby="project-details-title"', false)
        ->assertSee('Detailed confidential project scope.')
        ->assertSee('Rs. 200,000.00')
        ->assertSee('Rs. 45,000.00');

    Livewire::actingAs($this->manager)
        ->test(ProjectWorkspace::class, ['project' => $this->detailsProject])
        ->set('activeTab', 'details')
        ->assertSee('Detailed confidential project scope.')
        ->assertDontSee('wire:click="openEditProjectModal"', false)
        ->assertSet('showEditProjectModal', false)
        ->assertSet('activeTab', 'details');
});

test('admin viewer can read the details tab without editing controls', function () {
    $viewer = User::factory()->create(['is_active' => true]);
    $viewer->assignRole('admin_viewer');

    Livewire::actingAs($viewer)
        ->test(ProjectWorkspace::class, ['project' => $this->detailsProject])
        ->call('setTab', 'details')
        ->assertSet('activeTab', 'details')
        ->assertSee('Detailed confidential project scope.')
        ->assertDontSee('wire:click="openEditProjectModal"', false)
        ->assertDontSee('role="dialog"', false)
        ->call('setTab', 'wbs')
        ->call('openProjectDetailsModal')
        ->assertSet('showProjectDetailsModal', true)
        ->assertSee('Detailed confidential project scope.')
        ->assertSee('role="dialog"', false)
        ->call('closeProjectDetailsModal')
        ->assertSet('showProjectDetailsModal', false);
});

test('member without project details permission cannot select or deep link to the details tab', function () {
    $member = User::factory()->create(['is_active' => true]);
    $member->assignRole('team_member');
    $this->detailsProject->members()->attach($member->id, ['role' => 'member']);

    Livewire::actingAs($member)
        ->test(ProjectWorkspace::class, ['project' => $this->detailsProject])
        ->assertDontSee('<span>Project Details</span>', false)
        ->call('setTab', 'details')
        ->assertSet('activeTab', 'wbs')
        ->set('activeTab', 'details')
        ->assertSet('activeTab', 'wbs')
        ->assertDontSee('Detailed confidential project scope.');

    $this->actingAs($member)
        ->get(route('projects.show', ['project' => $this->detailsProject, 'tab' => 'details']))
        ->assertOk()
        ->assertDontSee('aria-labelledby="project-details-title"', false)
        ->assertDontSee('<span>Project Details</span>', false);
});

test('details overview includes assignment blueprint and governance participants without form controls', function () {
    $template = \App\Models\ProjectTemplate::create([
        'name' => 'Full Delivery Blueprint',
        'description' => 'Delivery blueprint working calendar.',
        'work_start_time' => '09:00',
        'work_end_time' => '17:00',
        'work_days' => ['monday', 'tuesday', 'wednesday'],
        'divide_by_hours' => true,
    ]);
    $this->detailsProject->update([
        'category' => 'Business Operations',
        'start_date' => '2026-11-01',
        'deadline' => '2026-11-15',
        'estimated_hours' => 120,
        'actual_hours' => 42.5,
        'actual_cost' => 225000,
        'template_id' => $template->id,
        'wbs_breakdown_type' => 'template',
        'pm_accepted_at' => now(),
    ]);
    foreach (['sponsor' => 'Sponsor Representative', 'owner' => 'Business Owner', 'steering_committee' => 'Steering Representative', 'member' => 'Delivery Participant'] as $role => $name) {
        $participant = User::factory()->create(['name' => $name]);
        $this->detailsProject->members()->attach($participant->id, ['role' => $role]);
    }

    $component = Livewire::actingAs($this->manager)
        ->test(ProjectWorkspace::class, ['project' => $this->detailsProject->fresh()])
        ->call('setTab', 'details')
        ->assertDontSee('<dt>Category</dt>', false)
        ->assertDontSee('<dt>Planned Duration</dt>', false)
        ->assertDontSee('<dt>Estimated Hours</dt>', false)
        ->assertDontSee('<dt>Actual Hours</dt>', false)
        ->assertSee('Created By')
        ->assertSee($this->manager->name)
        ->assertSee('Accepted On')
        ->assertSee('112.5%')
        ->assertSee('Rs. -25,000.00')
        ->assertSee('Full Delivery Blueprint')
        ->assertSee('Delivery blueprint working calendar.')
        ->assertSee('9:00 AM')
        ->assertSee('5:00 PM')
        ->assertSee('Monday, Tuesday, Wednesday')
        ->assertSee('Sponsor Representative')
        ->assertSee('Business Owner')
        ->assertSee('Steering Representative')
        ->assertSee('Delivery Participant');

    $document = new DOMDocument();
    $previousErrors = libxml_use_internal_errors(true);
    $document->loadHTML($component->html());
    libxml_clear_errors();
    libxml_use_internal_errors($previousErrors);
    $xpath = new DOMXPath($document);
    $controls = $xpath->query('//section[@aria-labelledby="project-details-title"]//*[self::input or self::textarea or self::select or self::form or self::button]');
    expect($controls->length)->toBe(0);
});

test('read only details handles an unset or zero budget without inventing a budget utilization', function ($budget) {
    $this->detailsProject->update(['estimated_budget' => $budget, 'actual_cost' => 0]);

    Livewire::actingAs($this->manager)
        ->test(ProjectWorkspace::class, ['project' => $this->detailsProject->fresh()])
        ->call('setTab', 'details')
        ->assertSee('Budget Utilization')
        ->assertSee('Not applicable')
        ->assertSee('Rs. 0.00')
        ->assertDontSee('wire:click="openEditProjectModal"', false);
})->with([null, 0]);
