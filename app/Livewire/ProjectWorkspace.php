<?php

namespace App\Livewire;

use App\Enums\ApprovalType;
use App\Mail\ProjectAssignedMail;
use App\Models\ApprovalRequest;
use App\Models\Comment;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectRisk;
use App\Models\ProjectStatusUpdate;
use App\Models\User;
use App\Models\WbsBaseline;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectWorkspace extends Component
{
    use WithFileUploads;

    public Project $project;
    public string $activeTab = 'wbs';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    // Status Update Form
    public string $statusTitle = '';
    public string $statusSummary = '';
    public ?string $workCompleted = null;
    public ?string $nextSteps = null;

    // Comment Form
    public string $commentContent = '';

    // Risk Form & Edit State
    public string $riskTitle = '';
    public string $riskDescription = '';
    public string $riskCategory = 'Technical';
    public string $riskProbability = 'medium';
    public string $riskImpact = 'medium';
    public ?int $riskWbsItemId = null;
    public ?string $riskMitigation = null;
    public ?string $riskContingency = null;
    public ?int $riskOwnerId = null;
    public bool $showEditRiskModal = false;
    public ?int $editingRiskId = null;

    // Blocker Resolution Modal
    public bool $showResolveBlockerModal = false;
    public ?int $selectedBlockerId = null;
    public string $blockerResolutionInput = '';

    // Approval Request Form
    public string $reqType = 'deadline_extension';
    public string $reqReason = '';
    public ?string $reqValue = null;

    // Document Upload & Preview
    public $documentFile = null;
    public ?string $docDescription = null;

    public bool $showPreviewModal = false;
    public ?int $previewDocId = null;
    public ?string $previewContent = null;
    public ?string $previewFileType = null;

    // Financial Summary & Hours Modal
    public bool $showFinancialsModal = false;
    public ?float $actualCostInput = null;
    public ?float $estimatedHoursInput = null;
    public ?float $actualHoursInput = null;

    public bool $showTimelineModal = false;
    public ?string $startDateInput = null;
    public ?string $deadlineInput = null;

    public bool $showEditProjectModal = false;
    public ?string $editName = null;
    public ?string $editCode = null;
    public ?int $editSubsidiaryId = null;
    public ?string $editCategory = null;
    public ?int $editProjectManagerId = null;
    public ?string $editDescription = null;
    public ?string $editPriority = null;
    public ?string $editStatus = null;
    public ?string $editHealth = null;
    public ?string $editStartDate = null;
    public ?string $editDeadline = null;
    public ?float $editEstimatedBudget = null;
    public ?float $editActualCost = null;

    public bool $showSetupModal = false;
    public ?string $setupDescription = null;
    public ?string $setupStartDate = null;
    public ?string $setupDeadline = null;
    public ?float $setupEstimatedBudget = null;
    public array $setupCollaboratorIds = [];

    // Collaborators Management Modal Properties
    public bool $showCollaboratorsModal = false;
    public array $selectedCollaboratorIds = [];
    public string $collaboratorSearch = '';
    public bool $showCreateCollaboratorSection = false;
    public string $newCollabName = '';
    public string $newCollabEmail = '';
    public string $newCollabPhone = '';
    public string $newCollabRole = 'team_member';
    public string $newCollabTempPassword = 'Password@123';

    public bool $showRejectionModal = false;
    public string $rejectionReasonInput = '';
    public bool $showReassignModal = false;
    public ?int $newLeaderId = null;

    // Comprehensive Project Details Sheet/Modal
    public bool $showProjectDetailsModal = false;

    public function openProjectDetailsModal(): void
    {
        $this->showProjectDetailsModal = true;
    }

    public function closeProjectDetailsModal(): void
    {
        $this->showProjectDetailsModal = false;
    }


    public function acceptAssignment(): void
    {
        $user = auth()->user();
        if ($this->project->project_manager_id !== $user->id && !$user->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'Unauthorized. Only the designated Project Manager can accept this assignment.', type: 'error');
            return;
        }

        $this->project->acceptByPm($user);
        $this->project->refresh();

        $this->dispatch('toast', message: '🎉 Project leadership accepted! You now have full Project Manager workspace control.', type: 'success');
    }

    public function openRejectionModal(): void
    {
        $this->rejectionReasonInput = '';
        $this->showRejectionModal = true;
    }

    public function submitRejection(): void
    {
        $this->validate([
            'rejectionReasonInput' => 'required|string|min:5|max:1000',
        ], [
            'rejectionReasonInput.required' => 'Please provide the reason or issue for declining this project assignment.',
            'rejectionReasonInput.min' => 'The reason must be at least 5 characters.',
        ]);

        $user = auth()->user();
        if ($this->project->project_manager_id !== $user->id && !$user->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'Unauthorized. Only the designated Project Leader can decline this assignment.', type: 'error');
            return;
        }

        $this->project->rejectByPm($user, $this->rejectionReasonInput);
        $this->project->refresh();
        $this->showRejectionModal = false;

        $this->dispatch('toast', message: 'Project assignment declined. PMO Admin has been notified of your feedback.', type: 'warning');
    }

    public function openReassignModal(): void
    {
        $this->newLeaderId = $this->project->project_manager_id;
        $this->showReassignModal = true;
    }

    public function submitReassign(): void
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin()) {
            $this->dispatch('toast', message: 'Only PMO Admin can reassign project leadership.', type: 'error');
            return;
        }

        $this->validate([
            'newLeaderId' => 'required|exists:users,id',
        ]);

        $this->project->update([
            'project_manager_id' => $this->newLeaderId,
            'pm_accepted' => false,
            'pm_accepted_at' => null,
            'pm_rejection_reason' => null,
            'pm_rejected_at' => null,
        ]);

        // Sync new leader as lead role
        $this->project->members()->syncWithoutDetaching([
            $this->newLeaderId => ['role' => 'lead']
        ]);

        $newLeader = User::find($this->newLeaderId);
        if ($newLeader) {
            $newLeader->notify(new \App\Notifications\ProjectAssignmentNotification($this->project, 'lead'));
        }

        $this->project->refresh();
        $this->showReassignModal = false;
        $this->dispatch('toast', message: "Project reassigned to {$newLeader->name}. Acceptance notification sent.", type: 'success');
    }

    public function saveSetupModal()
    {
        $user = auth()->user();
        if (!$this->project->userCan($user, 'project.edit') && !$this->project->canUserManage($user)) {
            $this->dispatch('toast', message: 'You do not have permission to setup the project workspace.', type: 'error');
            return;
        }

        $this->validate([
            'setupDeadline' => 'required|date',
            'setupDescription' => 'nullable|string',
            'setupEstimatedBudget' => 'nullable|numeric|min:0',
        ]);

        $this->project->description = $this->setupDescription;
        $this->project->start_date = $this->setupStartDate ?: now()->toDateString();
        $this->project->deadline = $this->setupDeadline;
        $this->project->estimated_budget = $this->setupEstimatedBudget ?: 0;
        $this->project->save();

        // Sync collaborators preserving existing roles
        $existingMembers = $this->project->members()->withPivot('role')->get()->keyBy('id');
        $intIds = array_filter(array_map('intval', $this->setupCollaboratorIds));
        $membersToSync = array_unique(array_merge(array_filter([$this->project->project_manager_id]), $intIds));
        
        $syncData = [];
        foreach ($membersToSync as $memberId) {
            if ($memberId == $this->project->project_manager_id) {
                $syncData[$memberId] = ['role' => 'lead'];
            } elseif (isset($existingMembers[$memberId])) {
                $syncData[$memberId] = ['role' => $existingMembers[$memberId]->pivot->role ?? 'member'];
            } else {
                $syncData[$memberId] = ['role' => 'member'];
            }
        }

        $this->project->members()->sync($syncData);
        $this->project->load('members');

        $this->showSetupModal = false;
        $this->dispatch('toast', message: '🚀 Project setup completed successfully! You can now add project tasks.', type: 'success');
    }

    public function openEditProjectModal()
    {
        $user = auth()->user();
        if (!$this->project->userCan($user, 'project.edit') && !$this->project->userCan($user, 'project_details.edit') && !$user->isSuperAdmin() && !$user->isPmoAdmin()) {
            $this->dispatch('toast', message: 'You do not have permission to edit project details.', type: 'error');
            return;
        }

        $this->editName = $this->project->name;
        $this->editCode = $this->project->code;
        $this->editSubsidiaryId = $this->project->subsidiary_id;
        $this->editCategory = $this->project->category ?? 'Corporate Strategy';
        $this->editProjectManagerId = $this->project->project_manager_id;
        $this->editDescription = $this->project->description ?? '';
        $this->editPriority = $this->project->priority ? $this->project->priority->value : 'medium';
        $this->editStatus = $this->project->status ? $this->project->status->value : 'planning';
        $this->editHealth = $this->project->health ? $this->project->health->value : 'on_track';
        $this->editStartDate = $this->project->start_date ? $this->project->start_date->format('Y-m-d') : null;
        $this->editDeadline = $this->project->deadline ? $this->project->deadline->format('Y-m-d') : null;
        $this->editEstimatedBudget = (float) ($this->project->estimated_budget ?? 0);
        $this->editActualCost = (float) ($this->project->actual_cost ?? 0);

        $this->showProjectDetailsModal = false;
        $this->showEditProjectModal = true;
    }

    public function saveProjectDetails()
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'project.edit'), 403, 'Unauthorized to edit project details.');

        $this->validate([
            'editName' => 'required|string|max:255',
            'editCode' => 'nullable|string|max:50',
            'editSubsidiaryId' => 'nullable|exists:subsidiaries,id',
            'editCategory' => 'nullable|string|max:100',
            'editProjectManagerId' => 'nullable|exists:users,id',
            'editDescription' => 'nullable|string',
            'editPriority' => 'required|string',
            'editStatus' => 'required|string',
            'editHealth' => 'required|string',
            'editStartDate' => 'nullable|date',
            'editDeadline' => 'nullable|date',
            'editEstimatedBudget' => 'nullable|numeric|min:0',
            'editActualCost' => 'nullable|numeric|min:0',
        ]);

        $this->project->name = $this->editName;
        if ($this->editCode) {
            $this->project->code = $this->editCode;
        }
        if ($this->editSubsidiaryId) {
            $this->project->subsidiary_id = $this->editSubsidiaryId;
        }
        $this->project->category = $this->editCategory ?: 'Corporate Strategy';
        
        // If Project Manager changed by PMO Admin
        if ($this->editProjectManagerId && $this->editProjectManagerId != $this->project->project_manager_id && ($user->isSuperAdmin() || $user->hasRole('pmo_admin'))) {
            $this->project->project_manager_id = $this->editProjectManagerId;
            if (!$this->project->members()->where('user_id', $this->editProjectManagerId)->exists()) {
                $this->project->members()->attach($this->editProjectManagerId, ['role' => 'lead']);
            }
        }

        $this->project->description = $this->editDescription;
        $this->project->priority = $this->editPriority;
        $this->project->status = $this->editStatus;
        $this->project->health = $this->editHealth;
        $this->project->start_date = $this->editStartDate ?: null;
        $this->project->deadline = $this->editDeadline ?: null;
        $this->project->estimated_budget = $this->editEstimatedBudget ?: 0;
        $this->project->actual_cost = $this->editActualCost ?: 0;
        $this->project->save();

        $this->project->refresh();
        $this->showEditProjectModal = false;
        $this->dispatch('toast', message: 'All project parameters and details updated successfully!', type: 'success');
    }

    public function openTimelineModal()
    {
        $user = auth()->user();
        if (!$this->project->userCan($user, 'project.manage_schedule') && !$this->project->userCan($user, 'schedule.edit')) {
            $this->dispatch('toast', message: 'You do not have permission to edit timeline dates.', type: 'error');
            return;
        }

        $this->startDateInput = $this->project->start_date?->toDateString() ?: now()->toDateString();
        $this->deadlineInput = $this->project->deadline?->toDateString() ?: now()->addMonths(3)->toDateString();
        $this->showTimelineModal = true;
    }

    public function saveTimeline()
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'project.manage_schedule') && !$this->project->userCan($user, 'schedule.edit'), 403, 'Unauthorized to edit project timeline.');

        $this->validate([
            'startDateInput' => 'nullable|date',
            'deadlineInput' => 'required|date|after_or_equal:startDateInput',
        ]);

        $this->project->start_date = $this->startDateInput ?: null;
        $this->project->deadline = $this->deadlineInput;
        $this->project->save();

        $this->showTimelineModal = false;
        $this->dispatch('toast', message: 'Project timeline updated successfully!', type: 'success');
    }

    public function openCollaboratorsModal()
    {
        $user = auth()->user();
        if (!$this->project->userCan($user, 'team.add') && !$this->project->userCan($user, 'team.view')) {
            $this->dispatch('toast', message: 'You do not have permission to manage team members.', type: 'error');
            return;
        }

        $this->selectedCollaboratorIds = array_map('strval', $this->project->members->pluck('id')->toArray());
        $this->showCreateCollaboratorSection = false;
        $this->showCollaboratorsModal = true;
    }

    public function saveCollaborators()
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'team.add'), 403, 'Unauthorized to add or edit project team members.');

        // Track existing member roles before sync so roles like 'sponsor', 'owner', 'steering_committee' are PRESERVED
        $existingMembers = $this->project->members()->withPivot('role')->get()->keyBy('id');
        $existingMemberIds = $existingMembers->keys()->toArray();

        $intIds = array_filter(array_map('intval', $this->selectedCollaboratorIds));
        $membersToSync = array_unique(array_merge(array_filter([$this->project->project_manager_id]), $intIds));
        $syncData = [];
        foreach ($membersToSync as $memberId) {
            if ($memberId == $this->project->project_manager_id) {
                $syncData[$memberId] = ['role' => 'lead'];
            } elseif (isset($existingMembers[$memberId])) {
                $syncData[$memberId] = ['role' => $existingMembers[$memberId]->pivot->role ?? 'member'];
            } else {
                $syncData[$memberId] = ['role' => 'member'];
            }
        }

        $this->project->members()->sync($syncData);
        $this->project->load('members', 'subsidiary');

        // Send mail only to newly added collaborators (not existing, not PM)
        $newMemberIds = array_diff(array_keys($syncData), $existingMemberIds, array_filter([$this->project->project_manager_id]));
        if (!empty($newMemberIds)) {
            try {
                $newMembers = User::whereIn('id', $newMemberIds)->get();
                foreach ($newMembers as $newMember) {
                    $mRole = $syncData[$newMember->id]['role'] ?? 'member';
                    Mail::to($newMember->email)
                        ->send(new ProjectAssignedMail($this->project, $newMember, $mRole));
                }
            } catch (\Throwable $e) {
                \Log::error('ProjectAssignedMail (collaborator) failed: ' . $e->getMessage());
            }
        }

        $this->showCollaboratorsModal = false;
        $this->dispatch('toast', message: 'Project team members updated successfully!', type: 'success');
    }

    public function removeCollaborator(int $userId)
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'team.remove'), 403, 'Unauthorized to remove team members.');

        if ($userId === $this->project->project_manager_id) {
            $this->dispatch('toast', message: 'Cannot remove the designated Project Leader.', type: 'error');
            return;
        }

        $this->project->members()->detach($userId);
        $this->selectedCollaboratorIds = array_values(array_diff($this->selectedCollaboratorIds, [(string) $userId, $userId]));
        $this->project->load('members');
        $this->dispatch('toast', message: 'Team member removed from project.', type: 'info');
    }

    public function deleteUserFromSystem(int $userId)
    {
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && !$user->hasRole('pmo_admin')) {
            $this->dispatch('toast', message: 'Only Administrators can delete users from the system.', type: 'error');
            return;
        }

        if ($userId === auth()->id()) {
            $this->dispatch('toast', message: 'Cannot delete your logged in account.', type: 'error');
            return;
        }

        if ($userId === $this->project->project_manager_id) {
            $this->dispatch('toast', message: 'Cannot delete the active Project Owner.', type: 'error');
            return;
        }

        $targetUser = \App\Models\User::find($userId);
        if (!$targetUser) return;

        // Disassociate user from projects & tasks before deleting
        \App\Models\Project::where('project_manager_id', $targetUser->id)->update(['project_manager_id' => null]);
        \App\Models\Project::where('created_by', $targetUser->id)->update(['created_by' => null]);
        \App\Models\WbsItem::where('assigned_user_id', $targetUser->id)->update(['assigned_user_id' => null]);
        \App\Models\ProjectRisk::where('owner_id', $targetUser->id)->update(['owner_id' => null]);
        \App\Models\ProjectStatusUpdate::where('created_by', $targetUser->id)->update(['created_by' => null]);
        \App\Models\ProjectDocument::where('uploaded_by', $targetUser->id)->update(['uploaded_by' => null]);

        $targetUser->projects()->detach();
        $targetUser->forceDelete();

        // Remove from selected ids array
        $this->selectedCollaboratorIds = array_values(array_diff($this->selectedCollaboratorIds, [(string) $userId, $userId]));

        $this->project->load('members');
        $this->dispatch('toast', message: 'User permanently deleted from system & removed from list.', type: 'info');
    }

    public function quickCreateCollaborator()
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['super_admin', 'pmo_admin', 'project_manager']) && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Leader or Administrator can create team members.', type: 'error');
            return;
        }

        $this->validate([
            'newCollabName' => 'required|string|max:255',
            'newCollabEmail' => 'required|email|max:255|unique:users,email',
            'newCollabPhone' => 'nullable|string|max:50',
            'newCollabRole' => 'required|in:team_member,project_manager',
            'newCollabTempPassword' => 'nullable|string|min:6',
        ]);

        $tempPassword = !empty($this->newCollabTempPassword) ? trim($this->newCollabTempPassword) : 'Password@123';

        $newCollab = \App\Models\User::create([
            'name' => trim($this->newCollabName),
            'email' => trim($this->newCollabEmail),
            'phone_number' => $this->newCollabPhone ? trim($this->newCollabPhone) : null,
            'password' => \Illuminate\Support\Facades\Hash::make($tempPassword),
            'subsidiary_id' => $this->project->subsidiary_id,
            'is_active' => true,
            'must_change_password' => false,
        ]);

        // No system-wide roles assigned to new collaborators
        if (!in_array((string) $newCollab->id, $this->selectedCollaboratorIds)) {
            $this->selectedCollaboratorIds[] = (string) $newCollab->id;
        }

        // Clear search filter so newly created collaborator appears in the selection list
        $this->collaboratorSearch = '';

        $this->saveCollaborators();

        $this->reset(['newCollabName', 'newCollabEmail', 'newCollabPhone', 'showCreateCollaboratorSection']);
        $this->newCollabTempPassword = 'Password@123';
        $this->dispatch('toast', message: "Collaborator {$newCollab->name} created & attached! Temporary Password: {$tempPassword}", type: 'success');
    }

    public function openFinancialsModal()

    {
        $this->actualCostInput = (float) $this->project->actual_cost;
        $this->estimatedHoursInput = (float) $this->project->estimated_hours;
        $this->actualHoursInput = (float) $this->project->actual_hours;
        $this->showFinancialsModal = true;
    }

    public function saveFinancialsAndHours()
    {
        $user = auth()->user();
        if (!$this->project->canUserManage($user)) {
            $this->dispatch('toast', message: 'Only the Project Leader, Sponsors, Owners, Steering Committee or PMO Admin can edit financial metrics.', type: 'error');
            return;
        }

        $this->validate([
            'actualCostInput' => 'required|numeric|min:0',
            'estimatedHoursInput' => 'required|numeric|min:0',
            'actualHoursInput' => 'required|numeric|min:0',
        ]);

        $this->project->actual_cost = $this->actualCostInput;
        $this->project->estimated_hours = $this->estimatedHoursInput;
        $this->project->actual_hours = $this->actualHoursInput;
        $this->project->save();

        $this->showFinancialsModal = false;
        $this->dispatch('toast', message: 'Financial summary & hours updated successfully!', type: 'success');
    }

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->project->load(['members', 'subsidiary', 'projectManager']);
        $user = auth()->user();

        // Security check: Check if user is authorized to view this project
        if ($user && !$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && !$user->isPmoAdmin()) {
            $isGov = $project->isGovernanceMember($user);
            $isPm = ($project->project_manager_id === $user->id) || ($project->getUserRole($user) === 'lead');
            $isMember = $project->members->contains($user->id);
            $hasTask = $project->wbsItems()->where('assigned_user_id', $user->id)->exists();
            
            if (!$isGov && !$isPm && !$isMember && !$hasTask) {
                abort(403, 'Unauthorized project access.');
            }

            // If project is awaiting PM acceptance, only the designated Project Manager can access it
            if (!$project->isPmAccepted() && !$isPm) {
                session()->flash('warning', "Project '{$project->name}' ({$project->code}) is currently awaiting Project Manager acceptance and initialization before opening to the team.");
                return redirect()->route('dashboard');
            }
        }
    }

    public function postComment()
    {
        $this->validate(['commentContent' => 'required|string|max:1000']);

        Comment::create([
            'commentable_id' => $this->project->id,
            'commentable_type' => Project::class,
            'user_id' => auth()->id(),
            'content' => $this->commentContent,
        ]);

        $this->commentContent = '';
        $this->dispatch('toast', message: 'Comment posted!', type: 'success');
    }

    public function updateProjectStatus(string $newStatus)
    {
        $user = auth()->user();
        if (!$this->project->userCan($user, 'project.edit') && !$this->project->userCan($user, 'project_details.edit') && !$user->isSuperAdmin() && !$user->isPmoAdmin()) {
            $this->dispatch('toast', message: 'You do not have permission to edit project details.', type: 'error');
            return;
        }

        $statusEnum = \App\Enums\ProjectStatus::tryFrom($newStatus);
        if ($statusEnum) {
            $this->project->status = $statusEnum;
            if ($newStatus === 'completed') {
                $this->project->overall_progress = 100;
            }
            $this->project->save();
            $this->dispatch('toast', message: 'Project status updated to ' . $statusEnum->label(), type: 'success');
        }
    }

    public function updateProjectHealth(string $newHealth)
    {
        $user = auth()->user();
        if (!$this->project->userCan($user, 'project.edit') && !$this->project->userCan($user, 'project_details.edit') && !$user->isSuperAdmin() && !$user->isPmoAdmin()) {
            $this->dispatch('toast', message: 'You do not have permission to edit project details.', type: 'error');
            return;
        }

        $healthEnum = \App\Enums\ProjectHealth::tryFrom($newHealth);
        if ($healthEnum) {
            $this->project->health = $healthEnum;
            $this->project->save();
            $this->dispatch('toast', message: 'Project health updated to ' . $healthEnum->label(), type: 'success');
        }
    }

    public function publishStatusUpdate()
    {
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && $this->project->project_manager_id !== $user->id && !$this->project->members->contains($user->id)) {
            $this->dispatch('toast', message: 'You are not assigned to this project.', type: 'error');
            return;
        }

        $this->validate([
            'statusTitle' => 'required|string|max:255',
            'statusSummary' => 'required|string',
        ]);

        ProjectStatusUpdate::create([
            'project_id' => $this->project->id,
            'title' => $this->statusTitle,
            'summary' => $this->statusSummary,
            'work_completed' => $this->workCompleted,
            'next_steps' => $this->nextSteps,
            'current_progress' => $this->project->overall_progress,
            'updated_status' => $this->project->status,
            'created_by' => auth()->id(),
        ]);

        $this->reset(['statusTitle', 'statusSummary', 'workCompleted', 'nextSteps']);
        $this->dispatch('toast', message: 'Daily status update published successfully!', type: 'success');
    }

    public function addRisk()
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'risk.create'), 403, 'You do not have permission to create risks.');

        $this->validate([
            'riskTitle' => 'required|string|max:255',
            'riskDescription' => 'required|string',
            'riskWbsItemId' => 'nullable|exists:wbs_items,id',
            'riskMitigation' => 'nullable|string',
            'riskContingency' => 'nullable|string',
            'riskOwnerId' => 'nullable|exists:users,id',
        ]);

        $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
        $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
        $score = ($probScores[$this->riskProbability] ?? 2) * ($impScores[$this->riskImpact] ?? 2);

        ProjectRisk::create([
            'project_id' => $this->project->id,
            'wbs_item_id' => $this->riskWbsItemId ?: null,
            'title' => $this->riskTitle,
            'description' => $this->riskDescription,
            'category' => $this->riskCategory,
            'probability' => $this->riskProbability,
            'impact' => $this->riskImpact,
            'risk_score' => $score,
            'owner_id' => $this->riskOwnerId ?: auth()->id(),
            'mitigation_plan' => $this->riskMitigation,
            'contingency_plan' => $this->riskContingency,
            'status' => 'open',
        ]);

        $this->reset(['riskTitle', 'riskDescription', 'riskWbsItemId', 'riskMitigation', 'riskContingency', 'riskOwnerId']);
        $this->dispatch('toast', message: 'Project risk added successfully!', type: 'success');
    }

    public function openEditRiskModal(int $riskId)
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'risk.edit'), 403, 'You do not have permission to edit risks.');

        $risk = ProjectRisk::where('project_id', $this->project->id)->where('id', $riskId)->first();
        if ($risk) {
            $this->editingRiskId = $risk->id;
            $this->riskTitle = $risk->title;
            $this->riskDescription = $risk->description;
            $this->riskCategory = $risk->category;
            $this->riskProbability = $risk->probability;
            $this->riskImpact = $risk->impact;
            $this->riskWbsItemId = $risk->wbs_item_id;
            $this->riskOwnerId = $risk->owner_id;
            $this->riskMitigation = $risk->mitigation_plan ?? '';
            $this->riskContingency = $risk->contingency_plan ?? '';
            $this->showEditRiskModal = true;
        }
    }

    public function updateRisk()
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'risk.edit'), 403, 'You do not have permission to edit risks.');

        $this->validate([
            'riskTitle' => 'required|string|max:255',
            'riskDescription' => 'required|string',
            'riskWbsItemId' => 'nullable|exists:wbs_items,id',
            'riskMitigation' => 'nullable|string',
            'riskContingency' => 'nullable|string',
            'riskOwnerId' => 'nullable|exists:users,id',
        ]);

        $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
        $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
        $score = ($probScores[$this->riskProbability] ?? 2) * ($impScores[$this->riskImpact] ?? 2);

        $risk = ProjectRisk::where('project_id', $this->project->id)->where('id', $this->editingRiskId)->first();
        if ($risk) {
            $risk->update([
                'wbs_item_id' => $this->riskWbsItemId ?: null,
                'title' => $this->riskTitle,
                'description' => $this->riskDescription,
                'category' => $this->riskCategory,
                'probability' => $this->riskProbability,
                'impact' => $this->riskImpact,
                'risk_score' => $score,
                'owner_id' => $this->riskOwnerId ?: auth()->id(),
                'mitigation_plan' => $this->riskMitigation,
                'contingency_plan' => $this->riskContingency,
            ]);

            $this->showEditRiskModal = false;
            $this->reset(['riskTitle', 'riskDescription', 'riskWbsItemId', 'riskMitigation', 'riskContingency', 'riskOwnerId', 'editingRiskId']);
            $this->dispatch('toast', message: 'Risk record updated successfully!', type: 'success');
        }
    }

    public function updateRiskStatus(int $riskId, string $status)
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'risk.resolve') && !$this->project->userCan($user, 'risk.edit'), 403, 'Unauthorized.');

        $risk = ProjectRisk::where('project_id', $this->project->id)->where('id', $riskId)->first();
        if ($risk) {
            $risk->status = $status;
            $risk->save();
            $this->dispatch('toast', message: 'Risk status updated to ' . ucfirst($status), type: 'success');
        }
    }

    public function deleteRisk(int $riskId)
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'risk.delete'), 403, 'You do not have permission to delete risks.');

        $risk = ProjectRisk::where('project_id', $this->project->id)->where('id', $riskId)->first();
        if ($risk) {
            $risk->delete();
            $this->dispatch('toast', message: 'Risk record removed.', type: 'info');
        }
    }

    public function openResolveBlockerModal(int $blockerId)
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'blocker.resolve'), 403, 'You do not have permission to resolve blockers.');

        $this->selectedBlockerId = $blockerId;
        $this->blockerResolutionInput = '';
        $this->showResolveBlockerModal = true;
    }

    public function saveBlockerResolution()
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'blocker.resolve'), 403, 'You do not have permission to resolve blockers.');

        $this->validate([
            'blockerResolutionInput' => 'required|string|min:3',
        ]);

        $blocker = \App\Models\TaskBlocker::find($this->selectedBlockerId);
        if ($blocker) {
            $blocker->resolution = $this->blockerResolutionInput;
            $blocker->resolved_by = auth()->id();
            $blocker->resolved_at = now();
            $blocker->status = 'resolved';
            $blocker->save();

            $this->showResolveBlockerModal = false;
            $this->dispatch('toast', message: 'Task blocker resolved successfully!', type: 'success');
        }
    }

    public function submitApprovalRequest()
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'approval.submit'), 403, 'You do not have permission to submit approval requests.');

        $rules = ['reqReason' => 'required|string|min:5'];

        if ($this->reqType === 'deadline_extension') {
            $rules['reqValue'] = 'required|date|after:today';
        } elseif ($this->reqType === 'budget_change') {
            $rules['reqValue'] = 'required|numeric|min:1';
        } elseif ($this->reqType === 'scope_change') {
            $rules['reqValue'] = 'required|string|min:5';
        }

        $this->validate($rules);

        $requestedValue = [];
        if ($this->reqType === 'deadline_extension' && $this->reqValue) {
            $requestedValue['new_deadline'] = $this->reqValue;
        } elseif ($this->reqType === 'budget_change' && $this->reqValue) {
            $requestedValue['new_budget'] = (float) $this->reqValue;
        } elseif ($this->reqType === 'scope_change' && $this->reqValue) {
            $requestedValue['scope_description'] = $this->reqValue;
        }

        $approval = ApprovalRequest::create([
            'project_id'      => $this->project->id,
            'request_type'    => $this->reqType,
            'requested_by'    => auth()->id(),
            'reason'          => $this->reqReason,
            'requested_value' => $requestedValue,
            'status'          => 'pending',
            'submitted_at'    => now(),
        ]);

        try {
            $recipients = \App\Models\User::role('super_admin')->where('id', '!=', auth()->id())->get();
            if ($this->project->projectManager && $this->project->projectManager->id !== auth()->id()) {
                $recipients->push($this->project->projectManager);
            }
            $recipients = $recipients->unique('id');
            foreach ($recipients as $recipient) {
                $recipient->notify(new \App\Notifications\ApprovalStatusNotification($approval, 'submitted'));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Approval notification error: ' . $e->getMessage());
        }

        $this->reset(['reqReason', 'reqValue']);
        $this->dispatch('toast', message: '✅ Approval request submitted for review!', type: 'success');
    }

    public function approveRequestInWorkspace(int $id)
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'approval.approve') && !$this->project->userCan($user, 'approval.final_approve'), 403, 'You do not have permission to approve requests.');

        $req = ApprovalRequest::where('project_id', $this->project->id)->findOrFail($id);
        try {
            (new \App\Services\ApprovalService())->approve($req, $user, 'Approved in Project Workspace');
            $this->dispatch('toast', message: '✅ Request APPROVED! Project updated.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function rejectRequestInWorkspace(int $id)
    {
        $user = auth()->user();
        abort_if(!$this->project->userCan($user, 'approval.reject'), 403, 'You do not have permission to reject requests.');

        $req = ApprovalRequest::where('project_id', $this->project->id)->findOrFail($id);
        try {
            (new \App\Services\ApprovalService())->reject($req, $user, 'Rejected in Project Workspace');
            $this->dispatch('toast', message: '❌ Request REJECTED.', type: 'warning');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function cancelRequestInWorkspace(int $id)
    {
        $req = ApprovalRequest::where('project_id', $this->project->id)->findOrFail($id);
        $user = auth()->user();

        if ($req->requested_by !== $user->id && !$user->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'You can only cancel requests submitted by you.', type: 'error');
            return;
        }

        $req->status = \App\Enums\ApprovalStatus::CANCELLED;
        $req->save();
        $this->dispatch('toast', message: 'Approval request cancelled.', type: 'info');
    }

    public function uploadDocument()
    {
        $this->validate([
            'documentFile' => 'required|file|max:10240', // 10MB
        ]);

        $originalName = $this->documentFile->getClientOriginalName();
        $mimeType = $this->documentFile->getClientMimeType();

        $fileSize = 0;
        try {
            $fileSize = $this->documentFile->getSize();
        } catch (\Throwable $e) {
            // Livewire temporary file size retrieval fallback
        }

        $path = $this->documentFile->store('project-documents/' . $this->project->id, 'local');

        if (!$fileSize && Storage::disk('local')->exists($path)) {
            $fileSize = Storage::disk('local')->size($path);
        }

        ProjectDocument::create([
            'project_id'    => $this->project->id,
            'file_name'     => basename($path),
            'original_name' => $originalName,
            'mime_type'     => $mimeType,
            'file_size'     => $fileSize,
            'storage_path'  => $path,
            'uploaded_by'   => auth()->id(),
            'description'   => $this->docDescription,
        ]);

        $this->reset(['documentFile', 'docDescription']);
        $this->dispatch('toast', message: 'Document uploaded to private storage!', type: 'success');
    }

    public function deleteDocument(int $documentId)
    {
        $doc = ProjectDocument::findOrFail($documentId);
        $user = auth()->user();

        if ($doc->uploaded_by !== $user->id && !$user->hasRole('super_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Unauthorized to delete this document.', type: 'error');
            return;
        }

        if (Storage::disk('local')->exists($doc->storage_path)) {
            Storage::disk('local')->delete($doc->storage_path);
        }

        $doc->delete();

        $this->dispatch('toast', message: 'Document deleted successfully!', type: 'info');
    }

    public function openPreview(int $documentId)
    {
        $doc = ProjectDocument::with(['project', 'uploader'])->findOrFail($documentId);
        $this->previewDocId = $doc->id;

        $ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
        $this->previewFileType = $ext;

        if (Storage::disk('local')->exists($doc->storage_path)) {
            $filePath = Storage::disk('local')->path($doc->storage_path);

            if (in_array($ext, ['docx', 'doc'])) {
                $this->previewContent = $this->extractDocxText($filePath);
            } elseif (in_array($ext, ['txt', 'csv', 'json', 'log', 'md', 'xml', 'html'])) {
                $this->previewContent = Storage::disk('local')->get($doc->storage_path);
            } else {
                $this->previewContent = null;
            }
        } else {
            $this->previewContent = null;
        }

        $this->showPreviewModal = true;
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->previewDocId = null;
        $this->previewContent = null;
        $this->previewFileType = null;
    }

    private function extractDocxText(string $filePath): string
    {
        if (!class_exists('ZipArchive')) return '';
        $zip = new \ZipArchive();
        if ($zip->open($filePath) === true) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $data = $zip->getFromIndex($index);
                $zip->close();
                $xml = str_replace(['</w:p>', '</w:tr>', '<w:br/>'], ["\n\n", "\n", "\n"], $data);
                return trim(html_entity_decode(strip_tags($xml)));
            }
            $zip->close();
        }
        return '';
    }

    public function render()
    {
        $project = $this->project->load([
            'subsidiary',
            'projectManager',
            'template.tasks',
            'members',
            'wbsItems.assignedUser',
            'statusUpdates.creator',
            'documents.uploader',
            'comments.user',
            'risks.owner',
            'approvalRequests.requester',
            'approvalRequests.reviewer',
            'wbsBaselines.approver',
        ]);

        $previewDoc = $this->previewDocId ? ProjectDocument::with(['project', 'uploader'])->find($this->previewDocId) : null;
        $availableUsers = \App\Models\User::getUsersForSubsidiary($this->project->subsidiary_id);
        $allSubsidiaries = \App\Models\Subsidiary::orderBy('name')->get();
        $allPms = \App\Models\User::where('is_active', true)->orderBy('name')->get();

        return view('livewire.project-workspace', compact('project', 'previewDoc', 'availableUsers', 'allSubsidiaries', 'allPms'));
    }

    public function toggleCollapse(int $id)
    {
        $this->dispatch('toggle-collapse', id: $id);
    }

    public function expandAll()
    {
        $this->dispatch('expand-all');
    }

    public function collapseAll()
    {
        $this->dispatch('collapse-all');
    }
}
