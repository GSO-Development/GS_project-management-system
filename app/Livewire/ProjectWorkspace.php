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
    public string $activeTab = 'overview';

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
    public ?string $editDescription = null;
    public ?string $editPriority = null;
    public ?float $editEstimatedBudget = null;

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

    public function saveSetupModal()
    {
        $user = auth()->user();
        if (!$user->hasRole('pmo_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or PMO Admin can setup the project workspace.', type: 'error');
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

        // Sync collaborators
        $membersToSync = array_unique(array_merge([$this->project->project_manager_id], $this->setupCollaboratorIds));
        $syncData = [];
        foreach ($membersToSync as $memberId) {
            $syncData[$memberId] = ['role' => ($memberId == $this->project->project_manager_id) ? 'lead' : 'member'];
        }

        $this->project->members()->sync($syncData);
        $this->project->load('members');

        $this->showSetupModal = false;
        $this->dispatch('toast', message: '🚀 Project setup completed successfully! You can now add project tasks.', type: 'success');
    }

    public function openEditProjectModal()
    {
        $user = auth()->user();
        if (!$user->hasRole('pmo_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or PMO Admin can edit project details.', type: 'error');
            return;
        }

        $this->editName = $this->project->name;
        $this->editDescription = $this->project->description;
        $this->editPriority = $this->project->priority->value;
        $this->editEstimatedBudget = (float) $this->project->estimated_budget;
        $this->showEditProjectModal = true;
    }

    public function saveProjectDetails()
    {
        $user = auth()->user();
        if (!$user->hasRole('pmo_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or PMO Admin can edit project details.', type: 'error');
            return;
        }

        $this->validate([
            'editName' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editPriority' => 'required|string',
            'editEstimatedBudget' => 'nullable|numeric|min:0',
        ]);

        $this->project->name = $this->editName;
        $this->project->description = $this->editDescription;
        $this->project->priority = $this->editPriority;
        $this->project->estimated_budget = $this->editEstimatedBudget ?: 0;
        $this->project->save();

        $this->showEditProjectModal = false;
        $this->dispatch('toast', message: 'Project details updated successfully!', type: 'success');
    }

    public function openTimelineModal()
    {
        $user = auth()->user();
        if (!$user->hasRole('pmo_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or PMO Admin can edit the project deadline.', type: 'error');
            return;
        }

        $this->startDateInput = $this->project->start_date?->toDateString() ?: now()->toDateString();
        $this->deadlineInput = $this->project->deadline?->toDateString() ?: now()->addMonths(3)->toDateString();
        $this->showTimelineModal = true;
    }

    public function saveTimeline()
    {
        $user = auth()->user();
        if (!$user->hasRole('pmo_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or PMO Admin can edit the project deadline.', type: 'error');
            return;
        }

        $this->validate([
            'startDateInput' => 'nullable|date',
            'deadlineInput' => 'required|date|after_or_equal:startDateInput',
        ]);

        $this->project->start_date = $this->startDateInput ?: null;
        $this->project->deadline = $this->deadlineInput;
        $this->project->save();

        $this->showTimelineModal = false;
        $this->dispatch('toast', message: 'Project deadline updated successfully!', type: 'success');
    }

    public function openCollaboratorsModal()
    {
        $user = auth()->user();
        if (!$user->hasRole('pmo_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or PMO Admin can manage project collaborators.', type: 'error');
            return;
        }

        $this->selectedCollaboratorIds = array_map('strval', $this->project->members->pluck('id')->toArray());
        $this->showCreateCollaboratorSection = false;
        $this->showCollaboratorsModal = true;
    }

    public function saveCollaborators()
    {
        $user = auth()->user();
        if (!$user->hasRole('pmo_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or PMO Admin can manage project collaborators.', type: 'error');
            return;
        }

        // Track existing member IDs before sync (to detect newly added ones)
        $existingMemberIds = $this->project->members->pluck('id')->toArray();

        $intIds = array_filter(array_map('intval', $this->selectedCollaboratorIds));
        $membersToSync = array_unique(array_merge([$this->project->project_manager_id], $intIds));
        $syncData = [];
        foreach ($membersToSync as $memberId) {
            $syncData[$memberId] = ['role' => ($memberId == $this->project->project_manager_id) ? 'lead' : 'member'];
        }

        $this->project->members()->sync($syncData);
        $this->project->load('members', 'subsidiary');

        // Send mail only to newly added collaborators (not existing, not PM)
        $newMemberIds = array_diff(array_keys($syncData), $existingMemberIds, [$this->project->project_manager_id]);
        if (!empty($newMemberIds)) {
            try {
                $newMembers = User::whereIn('id', $newMemberIds)->get();
                foreach ($newMembers as $newMember) {
                    Mail::to($newMember->email)
                        ->send(new ProjectAssignedMail($this->project, $newMember, 'member'));
                }
            } catch (\Throwable $e) {
                \Log::error('ProjectAssignedMail (collaborator) failed: ' . $e->getMessage());
            }
        }

        $this->showCollaboratorsModal = false;
        $this->dispatch('toast', message: 'Project collaborators updated successfully!', type: 'success');
    }

    public function removeCollaborator(int $userId)
    {
        $user = auth()->user();
        if (!$user->hasRole('pmo_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or PMO Admin can remove collaborators.', type: 'error');
            return;
        }

        if ($userId === $this->project->project_manager_id) {
            $this->dispatch('toast', message: 'Cannot remove the Project Owner.', type: 'error');
            return;
        }

        $this->project->members()->detach($userId);
        $this->selectedCollaboratorIds = array_values(array_diff($this->selectedCollaboratorIds, [(string) $userId, $userId]));
        $this->project->load('members');
        $this->dispatch('toast', message: 'Collaborator removed from project.', type: 'info');
    }

    public function deleteUserFromSystem(int $userId)
    {
        $user = auth()->user();
        if (!$user->hasRole('pmo_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or PMO Admin can delete users.', type: 'error');
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
        if (!$user->hasRole('super_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only the Project Owner or Super Admin can create collaborators.', type: 'error');
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
        if (!$user->hasRole('super_admin') && $this->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Only Project Manager or PMO Admin can edit financial metrics.', type: 'error');
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
        if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local') {
            $isPm = ($project->project_manager_id === $user->id);
            $isMember = $project->members->contains($user->id);
            
            if (!$isPm && !$isMember) {
                abort(403, 'Unauthorized project access.');
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
        if (!$user->hasRole('super_admin') && $this->project->project_manager_id !== $user->id && !$this->project->members->contains($user->id)) {
            $this->dispatch('toast', message: 'You must be assigned to this project to update status.', type: 'error');
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
        if (!$user->hasRole('super_admin') && $this->project->project_manager_id !== $user->id && !$this->project->members->contains($user->id)) {
            $this->dispatch('toast', message: 'You must be assigned to this project to update health.', type: 'error');
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
        $risk = ProjectRisk::where('project_id', $this->project->id)->where('id', $riskId)->first();
        if ($risk) {
            $risk->status = $status;
            $risk->save();
            $this->dispatch('toast', message: 'Risk status updated to ' . ucfirst($status), type: 'success');
        }
    }

    public function deleteRisk(int $riskId)
    {
        $risk = ProjectRisk::where('project_id', $this->project->id)->where('id', $riskId)->first();
        if ($risk) {
            $risk->delete();
            $this->dispatch('toast', message: 'Risk record removed.', type: 'info');
        }
    }

    public function openResolveBlockerModal(int $blockerId)
    {
        $this->selectedBlockerId = $blockerId;
        $this->blockerResolutionInput = '';
        $this->showResolveBlockerModal = true;
    }

    public function saveBlockerResolution()
    {
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

        ApprovalRequest::create([
            'project_id'      => $this->project->id,
            'request_type'    => $this->reqType,
            'requested_by'    => auth()->id(),
            'reason'          => $this->reqReason,
            'requested_value' => $requestedValue,
            'status'          => 'pending',
            'submitted_at'    => now(),
        ]);

        $this->reset(['reqReason', 'reqValue']);
        $this->dispatch('toast', message: '✅ Approval request submitted for review!', type: 'success');
    }

    public function approveRequestInWorkspace(int $id)
    {
        $user = auth()->user();
        $isPm = ($this->project->project_manager_id === $user->id);
        if (!$user->hasRole('super_admin') && !$isPm) {
            $this->dispatch('toast', message: 'Only PMO Admins and the designated Project Manager can approve requests.', type: 'error');
            return;
        }

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
        $isPm = ($this->project->project_manager_id === $user->id);
        if (!$user->hasRole('super_admin') && !$isPm) {
            $this->dispatch('toast', message: 'Only PMO Admins and the designated Project Manager can reject requests.', type: 'error');
            return;
        }

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
        $availableUsers = \App\Models\User::where('is_active', true)
            ->where(function($q) {
                $q->where('subsidiary_id', $this->project->subsidiary_id)
                  ->orWhereIn('id', $this->project->members->pluck('id')->toArray());
            })
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.project-workspace', compact('project', 'previewDoc', 'availableUsers'));
    }
}
