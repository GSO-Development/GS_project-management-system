<?php

namespace Tests\Feature;

use App\Enums\ApprovalStatus;
use App\Enums\ApprovalType;
use App\Livewire\ProjectApprovalActions;
use App\Livewire\ProjectIndex;
use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProjectApprovalIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pm;
    private Subsidiary $subsidiary;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Notification::fake();
        foreach (['super_admin', 'pmo_admin', 'project_manager', 'team_member'] as $role) {
            Role::findOrCreate($role, 'web');
        }
        $this->subsidiary = Subsidiary::create(['code' => 'APP', 'name' => 'Approval Entity']);
        $this->admin = User::factory()->create(['subsidiary_id' => $this->subsidiary->id, 'is_active' => true]);
        $this->admin->assignRole('super_admin');
        $this->pm = User::factory()->create(['subsidiary_id' => $this->subsidiary->id, 'is_active' => true]);
        $this->pm->assignRole('project_manager');
    }

    private function project(array $attributes = []): Project
    {
        return Project::create(array_merge([
            'code' => 'APP-'.(Project::count() + 1),
            'name' => 'Approval Project '.(Project::count() + 1),
            'subsidiary_id' => $this->subsidiary->id,
            'project_manager_id' => $this->pm->id,
            'created_by' => $this->admin->id,
            'priority' => 'medium',
            'status' => 'planning',
            'start_date' => '2026-11-01',
            'deadline' => '2026-12-01',
            'pm_accepted' => false,
        ], $attributes));
    }

    private function requestFor(Project $project, array $attributes = []): ApprovalRequest
    {
        return ApprovalRequest::create(array_merge([
            'project_id' => $project->id,
            'request_type' => ApprovalType::NEW_PROJECT_PLAN,
            'requested_by' => $this->admin->id,
            'requested_value' => ['assigned_pm_id' => $project->project_manager_id],
            'reason' => 'Please review this assignment.',
            'status' => ApprovalStatus::PENDING,
            'submitted_at' => now(),
        ], $attributes));
    }

    public function test_projects_table_displays_assignment_statuses_and_rejection_reason(): void
    {
        $this->project(['name' => 'Approved assignment', 'pm_accepted' => true]);
        $this->project(['name' => 'Pending assignment']);
        $this->project(['name' => 'Rejected assignment', 'pm_rejection_reason' => 'The delivery dates conflict with an existing assignment.']);

        Livewire::actingAs($this->admin)->test(ProjectIndex::class)
            ->assertSee('Approved assignment')->assertSee('Approved')
            ->assertSee('Pending assignment')->assertSee('Pending')
            ->assertSee('Rejected assignment')->assertSee('Rejected')
            ->assertSee('The delivery dates conflict with an existing assignment.')
            ->assertSee('Reassign PM')->assertSee('Review');

        $this->actingAs($this->admin)->get('/projects')->assertOk()->assertDontSee('Approvals Hub');
        $this->get('/approvals')->assertRedirect(route('projects.index', ['viewMode' => 'table']));
    }

    public function test_approval_filter_preserves_project_visibility(): void
    {
        $pending = $this->project(['name' => 'Waiting for decision']);
        $this->project(['name' => 'Already signed off', 'pm_accepted' => true]);
        $this->project(['name' => 'Other manager private project', 'project_manager_id' => $this->admin->id]);

        Livewire::actingAs($this->pm)->test(ProjectIndex::class)
            ->set('approvalFilter', 'pending')
            ->assertSee($pending->name)
            ->assertDontSee('Already signed off')
            ->assertDontSee('Other manager private project');
    }

    public function test_assigned_manager_can_approve_from_the_project_table_workflow(): void
    {
        $project = $this->project();
        $request = $this->requestFor($project);

        Livewire::actingAs($this->pm)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id, 'accept')
            ->assertDispatched('project-approval-updated');

        $this->assertTrue($project->fresh()->pm_accepted);
        $this->assertSame(ApprovalStatus::APPROVED, $request->fresh()->status);
        $this->assertSame($this->pm->id, $request->fresh()->reviewed_by);
    }

    public function test_rejection_requires_a_reason_and_is_saved_to_both_records(): void
    {
        $project = $this->project();
        $request = $this->requestFor($project);
        $form = Livewire::actingAs($this->pm)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id, 'reject')
            ->call('submitDeclineProject')
            ->assertHasErrors(['declineReason']);

        $this->assertSame(ApprovalStatus::PENDING, $request->fresh()->status);
        $form->set('declineReason', 'I cannot cover the proposed project schedule.')
            ->call('submitDeclineProject')
            ->assertHasNoErrors()
            ->assertSet('showDeclineModal', false)
            ->assertDispatched('project-approval-updated');

        $this->assertSame(ApprovalStatus::REJECTED, $request->fresh()->status);
        $this->assertSame('I cannot cover the proposed project schedule.', $project->fresh()->pm_rejection_reason);
        $this->assertSame($project->fresh()->pm_rejection_reason, $request->fresh()->review_comment);
    }

    public function test_reassignment_preserves_rejection_history_and_existing_custom_roles(): void
    {
        $project = $this->project(['pm_rejection_reason' => 'Resource conflict.', 'pm_rejected_at' => now()]);
        $request = $this->requestFor($project, ['status' => ApprovalStatus::REJECTED, 'review_comment' => 'Resource conflict.']);
        $newPm = User::factory()->create(['subsidiary_id' => $this->subsidiary->id, 'is_active' => true]);
        $customMember = User::factory()->create(['subsidiary_id' => $this->subsidiary->id]);
        $project->members()->sync([$this->pm->id => ['role' => 'lead'], $customMember->id => ['role' => 'quality_reviewer']]);

        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id, 'reassign')
            ->assertSee('Resource conflict.')
            ->set('reassignPmId', $newPm->id)
            ->set('reassignCategory', 'Business Operations')
            ->call('submitReassignPm')
            ->assertHasNoErrors()
            ->assertSet('showReassignModal', false)
            ->assertDispatched('project-approval-updated');

        $this->assertSame($newPm->id, $project->fresh()->project_manager_id);
        $this->assertSame('Business Operations', $project->fresh()->category);
        $this->assertFalse($project->fresh()->pm_accepted);
        $this->assertNull($project->fresh()->pm_rejection_reason);
        $this->assertSame(ApprovalStatus::REJECTED, $request->fresh()->status);
        $this->assertSame('Resource conflict.', $request->fresh()->review_comment);
        $latest = $project->approvalRequests()->orderByDesc('id')->first();
        $this->assertNotSame($request->id, $latest->id);
        $this->assertSame(ApprovalStatus::PENDING, $latest->status);
        $this->assertSame($newPm->id, $latest->requested_value['assigned_pm_id']);
        $this->assertDatabaseHas('project_members', ['project_id' => $project->id, 'user_id' => $customMember->id, 'role' => 'quality_reviewer']);
        $this->assertDatabaseHas('project_members', ['project_id' => $project->id, 'user_id' => $newPm->id, 'role' => 'lead']);
    }

    public function test_non_admin_cannot_reassign_even_by_calling_the_action_directly(): void
    {
        $project = $this->project(['pm_rejection_reason' => 'Unavailable.']);
        Livewire::actingAs($this->pm)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id)
            ->set('reassignProjectId', $project->id)
            ->set('reassignPmId', $this->admin->id)
            ->call('submitReassignPm')->assertForbidden();
        $this->assertSame($this->pm->id, $project->fresh()->project_manager_id);
    }

    public function test_unrelated_user_cannot_open_project_approval_details(): void
    {
        $project = $this->project();
        $outsider = User::factory()->create();
        Livewire::actingAs($outsider)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id)->assertForbidden();
    }

    public function test_already_processed_assignment_cannot_be_overwritten(): void
    {
        $project = $this->project(['pm_accepted' => true]);
        Livewire::actingAs($this->pm)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id, 'accept')->assertStatus(409);
        $this->assertTrue($project->fresh()->pm_accepted);
    }

    public function test_formal_deadline_request_can_still_be_reviewed_and_applies_the_change(): void
    {
        $project = $this->project(['pm_accepted' => true]);
        $request = $this->requestFor($project, [
            'request_type' => ApprovalType::DEADLINE_EXTENSION,
            'requested_by' => $this->pm->id,
            'requested_value' => ['new_deadline' => '2027-01-10'],
        ]);

        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id)
            ->assertSee('Deadline Extension')
            ->call('review', $request->id)
            ->call('approveRequest')
            ->assertHasNoErrors()->assertDispatched('project-approval-updated');

        $this->assertSame(ApprovalStatus::APPROVED, $request->fresh()->status);
        $this->assertSame('2027-01-10', $project->fresh()->deadline->toDateString());
    }

    public function test_formal_rejection_requires_a_reason_and_does_not_change_deadline(): void
    {
        $project = $this->project(['pm_accepted' => true]);
        $request = $this->requestFor($project, [
            'request_type' => ApprovalType::DEADLINE_EXTENSION, 'requested_by' => $this->pm->id,
            'requested_value' => ['new_deadline' => '2027-01-10'],
        ]);
        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id)->call('review', $request->id)
            ->call('rejectRequest')->assertHasErrors(['reviewComment'])
            ->set('reviewComment', 'The existing deadline must be maintained.')
            ->call('rejectRequest')->assertHasNoErrors();
        $this->assertSame(ApprovalStatus::REJECTED, $request->fresh()->status);
        $this->assertSame('2026-12-01', $project->fresh()->deadline->toDateString());
        Livewire::actingAs($this->admin)->test(ProjectIndex::class)
            ->assertSee('The existing deadline must be maintained.');
    }

    public function test_request_from_another_project_cannot_be_reviewed_in_current_context(): void
    {
        $first = $this->project(['pm_accepted' => true]);
        $second = $this->project(['pm_accepted' => true]);
        $request = $this->requestFor($second);
        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $first->id)
            ->call('review', $request->id)->assertNotFound();
    }

    public function test_pmo_admin_can_view_but_cannot_approve_another_managers_assignment(): void
    {
        $pmo = User::factory()->create();
        $pmo->assignRole('pmo_admin');
        $project = $this->project();
        $request = $this->requestFor($project);
        Livewire::actingAs($pmo)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id, 'accept')->assertForbidden();
        $this->assertFalse($project->fresh()->pm_accepted);

        $notification = new \App\Notifications\ApprovalStatusNotification($request, 'approved');
        $this->assertSame(route('projects.index', ['approval_project' => $project->id]), $notification->toArray($this->pm)['url']);
        $this->actingAs($pmo)->get(route('projects.index', ['approval_project' => $project->id]))
            ->assertOk()->assertSee('Project approvals');
    }

    public function test_new_formal_requests_stay_scoped_to_the_selected_project(): void
    {
        $project = $this->project(['pm_accepted' => true]);
        $privateProject = $this->project(['project_manager_id' => $this->admin->id]);
        $form = Livewire::actingAs($this->pm)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id)->call('openCreateModal')
            ->set('requestType', 'budget_change')->set('newBudget', '5000')
            ->set('reason', 'Additional approved scope needs more resources.')
            ->call('createRequest')->assertHasNoErrors();
        $this->assertDatabaseHas('approval_requests', ['project_id' => $project->id, 'request_type' => 'budget_change', 'status' => 'pending']);
        $form->call('openCreateModal')->set('projectId', $privateProject->id)->call('createRequest')->assertForbidden();
        $this->assertDatabaseMissing('approval_requests', ['project_id' => $privateProject->id]);
    }

    public function test_failed_reassignment_keeps_the_rejected_assignment_unchanged(): void
    {
        $project = $this->project(['pm_rejection_reason' => 'Unavailable for this schedule.']);
        $request = $this->requestFor($project, ['status' => ApprovalStatus::REJECTED, 'review_comment' => 'Unavailable for this schedule.']);
        $duplicate = $this->project();
        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id, 'reassign')
            ->set('reassignCode', strtolower($duplicate->code))
            ->set('reassignPmId', $this->admin->id)
            ->call('submitReassignPm')->assertHasErrors(['reassignCode']);
        $this->assertSame($this->pm->id, $project->fresh()->project_manager_id);
        $this->assertSame(ApprovalStatus::REJECTED, $request->fresh()->status);
        $this->assertSame(1, $project->approvalRequests()->count());
    }

    public function test_manager_cannot_approve_their_own_formal_request(): void
    {
        $project = $this->project(['pm_accepted' => true]);
        $request = $this->requestFor($project, [
            'request_type' => ApprovalType::BUDGET_CHANGE, 'requested_by' => $this->pm->id,
            'requested_value' => ['new_budget' => 50000],
        ]);
        Livewire::actingAs($this->pm)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id)->call('review', $request->id)
            ->call('approveRequest')->assertForbidden();
        $this->assertSame(ApprovalStatus::PENDING, $request->fresh()->status);
    }

    public function test_self_reassignment_is_approved_and_keeps_the_original_rejection(): void
    {
        $project = $this->project(['pm_rejection_reason' => 'No available capacity.']);
        $request = $this->requestFor($project, ['status' => ApprovalStatus::REJECTED, 'review_comment' => 'No available capacity.']);
        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id, 'reassign')
            ->set('reassignPmId', $this->admin->id)->call('submitReassignPm')->assertHasNoErrors();
        $this->assertTrue($project->fresh()->pm_accepted);
        $this->assertSame(ApprovalStatus::REJECTED, $request->fresh()->status);
        $this->assertSame(ApprovalStatus::APPROVED, $project->approvalRequests()->orderByDesc('id')->first()->status);
    }

    public function test_super_admin_cannot_approve_another_managers_assignment_from_table_or_review(): void
    {
        $project = $this->project();
        $request = $this->requestFor($project);

        Livewire::actingAs($this->admin)->test(ProjectIndex::class)
            ->assertDontSee("action: 'accept'", false)
            ->assertDontSee("action: 'reject'", false);
        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id)
            ->assertDontSee('Approve assignment')
            ->assertDontSee('wire:click="openDeclineProjectModal', false)
            ->assertSee('Awaiting Project Manager decision')
            ->call('review', $request->id)
            ->assertDontSee('wire:click="approveRequest"', false)
            ->assertDontSee('wire:click="rejectRequest"', false)
            ->call('approveRequest')->assertForbidden();
        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id, 'accept')->assertForbidden();
        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id, 'reject')->assertForbidden();

        $this->assertFalse($project->fresh()->pm_accepted);
        $this->assertSame(ApprovalStatus::PENDING, $request->fresh()->status);
    }

    public function test_model_denies_administrators_and_other_managers_without_changing_the_assignment(): void
    {
        $project = $this->project();
        $request = $this->requestFor($project);
        $pmo = User::factory()->create();
        $pmo->assignRole('pmo_admin');
        $otherPm = User::factory()->create();
        $otherPm->assignRole('project_manager');

        foreach ([$this->admin, $pmo, $otherPm] as $actor) {
            $this->assertFalse($project->acceptByPm($actor));
            $this->assertFalse($project->rejectByPm($actor, 'Not authorized to decide this assignment.'));
            $this->assertFalse($project->fresh()->pm_accepted);
            $this->assertNull($project->fresh()->pm_rejection_reason);
            $this->assertSame(ApprovalStatus::PENDING, $request->fresh()->status);
        }
        $this->assertDatabaseMissing('activity_logs', ['action' => 'accepted_project_assignment', 'record_id' => $project->id]);
        Notification::assertNothingSent();
    }

    public function test_request_service_cannot_bypass_the_assigned_manager_rule(): void
    {
        $project = $this->project();
        $request = $this->requestFor($project);
        $pmo = User::factory()->create();
        $pmo->assignRole('pmo_admin');
        $otherPm = User::factory()->create();
        $otherPm->assignRole('project_manager');

        foreach ([$this->admin, $pmo, $otherPm] as $actor) {
            try {
                app(\App\Services\ApprovalService::class)->approve($request, $actor);
                $this->fail('An unassigned reviewer must not approve project leadership.');
            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                $this->assertSame(403, $e->getStatusCode());
            }
            try {
                app(\App\Services\ApprovalService::class)->reject($request, $actor, 'Not authorized to reject this assignment.');
                $this->fail('An unassigned reviewer must not reject project leadership.');
            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                $this->assertSame(403, $e->getStatusCode());
            }
        }
        $this->assertFalse($project->fresh()->pm_accepted);
        $this->assertSame(ApprovalStatus::PENDING, $request->fresh()->status);
        Notification::assertNothingSent();
    }

    public function test_assigned_manager_can_approve_via_request_review_and_service(): void
    {
        $project = $this->project();
        $request = $this->requestFor($project);
        Livewire::actingAs($this->pm)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id)
            ->assertSee('Approve assignment')
            ->call('review', $request->id)
            ->assertSee('wire:click="approveRequest"', false)
            ->call('approveRequest')->assertHasNoErrors();
        $this->assertTrue($project->fresh()->pm_accepted);
        $this->assertSame($this->pm->id, $request->fresh()->reviewed_by);

        $second = $this->project();
        $secondRequest = $this->requestFor($second);
        app(\App\Services\ApprovalService::class)->approve($secondRequest, $this->pm, 'Accepted by the assigned manager.');
        $this->assertTrue($second->fresh()->pm_accepted);
        $this->assertSame(ApprovalStatus::APPROVED, $secondRequest->fresh()->status);
        $this->assertSame($this->pm->id, $secondRequest->fresh()->reviewed_by);
        $this->assertSame('Accepted by the assigned manager.', $secondRequest->fresh()->review_comment);
    }

    public function test_super_admin_can_approve_when_they_are_the_assigned_manager(): void
    {
        $project = $this->project(['project_manager_id' => $this->admin->id]);
        $request = $this->requestFor($project);
        Livewire::actingAs($this->admin)->test(ProjectIndex::class)
            ->assertSee("action: 'accept'", false);
        Livewire::actingAs($this->admin)->test(ProjectApprovalActions::class)
            ->call('openProjectApprovals', $project->id)
            ->assertSee('Approve assignment')
            ->call('review', $request->id)
            ->call('approveRequest')->assertHasNoErrors();
        $this->assertTrue($project->fresh()->pm_accepted);
        $this->assertSame($this->admin->id, $request->fresh()->reviewed_by);
    }

    public function test_reassignment_revokes_the_previous_managers_approval_even_with_stale_models(): void
    {
        $project = $this->project();
        $request = $this->requestFor($project);
        $staleProject = Project::findOrFail($project->id);
        $request->load('project');
        $newPm = User::factory()->create();
        $project->update(['project_manager_id' => $newPm->id]);

        $this->assertFalse($staleProject->acceptByPm($this->pm));
        try {
            app(\App\Services\ApprovalService::class)->approve($request, $this->pm);
            $this->fail('The previous manager must not approve a reassigned project.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }
        $this->assertFalse($project->fresh()->pm_accepted);
        $this->assertSame(ApprovalStatus::PENDING, $request->fresh()->status);
    }

    public function test_workspace_and_notifications_cannot_approve_another_managers_assignment(): void
    {
        $project = $this->project();
        $this->requestFor($project);
        Livewire::actingAs($this->admin)->test(\App\Livewire\ProjectWorkspace::class, ['project' => $project])
            ->assertDontSee('wire:click="acceptAssignment"', false)
            ->call('acceptAssignment')->assertForbidden();
        Livewire::actingAs($this->admin)->test(\App\Livewire\NotificationManager::class)
            ->call('acceptProjectAssignment', '00000000-0000-0000-0000-000000000001', $project->id)
            ->assertForbidden();
        $this->assertFalse($project->fresh()->pm_accepted);
    }

    public function test_executive_dashboard_cannot_approve_another_managers_assignment(): void
    {
        $project = $this->project();
        $request = $this->requestFor($project);
        Livewire::actingAs($this->admin)->test(\App\Livewire\SuperAdminDashboard::class)
            ->call('openReviewModal', $request->id)
            ->assertDontSee('wire:click="submitReview(true)"', false)
            ->call('approveRequest', $request->id)->assertForbidden();
        $this->assertFalse($project->fresh()->pm_accepted);
        $this->assertSame(ApprovalStatus::PENDING, $request->fresh()->status);
    }

}
