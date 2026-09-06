<?php

namespace App\Livewire;

use App\Enums\Priority;
use App\Enums\ProjectHealth;
use App\Enums\ProjectStatus;
use App\Mail\ProjectAssignedMail;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\ProjectDetailExtractor;
use App\Services\RbacService;

class ProjectCreate extends Component
{
    use WithFileUploads;

    // Step Wizard state
    public int $currentStep = 1;

    public string $code = '';
    public string $name = '';
    public ?string $description = null;
    public ?int $subsidiary_id = null;
    public ?int $project_manager_id = null;
    public array $sponsor_ids = [];
    public array $owner_ids = [];
    public array $steering_committee_ids = [];
    public array $selected_participant_ids = [];
    public ?string $start_date = null;
    public ?string $deadline = null;
    public string $leaderSearch = '';
    public string $sponsorSearch = '';
    public string $ownerSearch = '';
    public string $steeringSearch = '';
    public string $participantSearch = '';
    public string $templateSearch = '';

    // Custom role assignments: ['role_code' => [user_ids]]
    public array $customRoleAssignments = [];
    // Search strings for each custom role: ['role_code' => 'search_query']
    public array $customRoleSearch = [];

    // Breakdown Method Properties
    public string $creation_option = 'template'; // 'template' or 'manual'
    public ?int $selected_template_id = null;
    public bool $showManualDatesModal = false;
    public ?string $calculatedDeadline = null;

    // Charter Extractor properties
    public $charterFile;
    public ?array $extractedData = null;
    public ?string $rawTextPreview = null;
    public bool $showExtractor = false;

    public function mount()
    {
        $user = auth()->user();
        if (!$user || !$user->isPmoAdmin()) {
            session()->flash('error', 'Unauthorized. Only PMO Administrators are authorized to initiate new projects.');
            return redirect()->route('projects.index');
        }

        $this->currentStep = 1;
        $this->start_date = now()->format('Y-m-d');
        $this->deadline = null;

        $firstSub = Subsidiary::first();
        if ($firstSub) {
            $this->subsidiary_id = $firstSub->id;
        }

        $this->project_manager_id = null;
        $this->selected_participant_ids = [];

        $firstTpl = \App\Models\ProjectTemplate::first();
        if ($firstTpl) {
            $this->selected_template_id = $firstTpl->id;
        }

        $this->generateCode();
        $this->calculateTemplateDeadline();

        // Initialize custom role assignments
        $this->initCustomRoles();
    }

    /**
     * Get all custom (non-system) roles from the DB.
     */
    protected function getCustomRoles(): array
    {
        $allRoles = RbacService::getAllRoles();
        return array_filter($allRoles, fn($r) => !($r['is_system'] ?? true));
    }

    /**
     * Initialize the customRoleAssignments and customRoleSearch arrays
     * for all currently-known custom roles.
     */
    protected function initCustomRoles(): void
    {
        foreach ($this->getCustomRoles() as $code => $role) {
            if (!isset($this->customRoleAssignments[$code])) {
                $this->customRoleAssignments[$code] = [];
            }
            if (!isset($this->customRoleSearch[$code])) {
                $this->customRoleSearch[$code] = '';
            }
        }
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'subsidiary_id' => 'required|exists:subsidiaries,id',
                'name'          => 'required|string|max:255',
            ]);
        }

        if ($this->currentStep === 2) {
            $this->validate([
                'project_manager_id' => 'required|exists:users,id',
            ]);
        }

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function updatedCreationOption($value)
    {
        if ($value === 'manual') {
            $this->showManualDatesModal = false;
            if (!$this->start_date) {
                $this->start_date = now()->format('Y-m-d');
            }
            if (!$this->deadline) {
                $this->deadline = now()->addMonths(3)->format('Y-m-d');
            }
        } else {
            $this->showManualDatesModal = false;
            if (!$this->selected_template_id) {
                $firstTpl = \App\Models\ProjectTemplate::first();
                if ($firstTpl) $this->selected_template_id = $firstTpl->id;
            }
            $this->calculateTemplateDeadline();
        }
    }

    public function selectBlankCanvas(): void
    {
        $this->creation_option = 'manual';
        $this->selected_template_id = null;
        $this->calculatedDeadline = null;
        if (!$this->start_date) {
            $this->start_date = now()->format('Y-m-d');
        }
        if (!$this->deadline) {
            $this->deadline = now()->addMonths(3)->format('Y-m-d');
        }
    }

    public function selectTemplate(int $templateId): void
    {
        $this->creation_option = 'template';
        $this->selected_template_id = $templateId;
        $this->calculateTemplateDeadline();
    }

    public function setQuickStartDate(string $preset): void
    {
        if ($preset === 'today') {
            $this->start_date = now()->format('Y-m-d');
        } elseif ($preset === 'next_monday') {
            $this->start_date = now()->next(\Carbon\Carbon::MONDAY)->format('Y-m-d');
        } elseif ($preset === 'next_month') {
            $this->start_date = now()->addMonth()->startOfMonth()->format('Y-m-d');
        }
        $this->calculateTemplateDeadline();
    }

    public function setManualDuration(int $months): void
    {
        if (!$this->start_date) {
            $this->start_date = now()->format('Y-m-d');
        }
        $this->deadline = \Carbon\Carbon::parse($this->start_date)->addMonths($months)->format('Y-m-d');
    }

    public function updatedSelectedTemplateId()
    {
        $this->calculateTemplateDeadline();
    }

    public function updatedStartDate()
    {
        $this->calculateTemplateDeadline();
    }

    public function calculateTemplateDeadline()
    {
        if ($this->creation_option === 'template' && $this->selected_template_id && $this->start_date) {
            $template = \App\Models\ProjectTemplate::find($this->selected_template_id);
            if ($template) {
                $rootTasks = $template->tasks()->whereNull('parent_id')->get();
                $totalDays = 0;
                foreach ($rootTasks as $task) {
                    $totalDays += $this->getDurationInDays($task->duration, $task->unit);
                }

                $startDateObj = \Carbon\Carbon::parse($this->start_date);
                if ($totalDays > 0) {
                    $calculated = $startDateObj->copy()->addDays($totalDays)->subDay();
                    $this->calculatedDeadline = $calculated->toDateString();
                    $this->deadline = $this->calculatedDeadline;
                } else {
                    $this->calculatedDeadline = null;
                    $this->deadline = null;
                }
            }
        } else {
            $this->calculatedDeadline = null;
        }
    }

    public function confirmManualDates()
    {
        $this->validate([
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
        ]);
        $this->showManualDatesModal = false;
        $this->dispatch('toast', message: 'Manual dates configured successfully!', type: 'success');
    }

    public function selectSubsidiary(int $subId)
    {
        $this->subsidiary_id = $subId;
        $this->autoSelectPmAndParticipants();
        $this->generateCode();
    }

    public function updatedSubsidiaryId()
    {
        $this->autoSelectPmAndParticipants();
        $this->generateCode();
    }

    public function autoSelectPmAndParticipants()
    {
        // Do not auto select Project Leader or participants - leave empty until user selects
        $this->project_manager_id = null;
        $this->selected_participant_ids = [];
    }

    public function updatedSponsorIds(): void
    {
        $sStr = array_map('strval', $this->sponsor_ids);
        $this->owner_ids = array_values(array_diff(array_map('strval', $this->owner_ids), $sStr));
        $this->steering_committee_ids = array_values(array_diff(array_map('strval', $this->steering_committee_ids), $sStr));
        $this->selected_participant_ids = array_values(array_diff(array_map('strval', $this->selected_participant_ids), $sStr));
        if ($this->project_manager_id && in_array((string)$this->project_manager_id, $sStr)) {
            $this->project_manager_id = null;
        }
    }

    public function updatedOwnerIds(): void
    {
        $oStr = array_map('strval', $this->owner_ids);
        $this->sponsor_ids = array_values(array_diff(array_map('strval', $this->sponsor_ids), $oStr));
        $this->steering_committee_ids = array_values(array_diff(array_map('strval', $this->steering_committee_ids), $oStr));
        $this->selected_participant_ids = array_values(array_diff(array_map('strval', $this->selected_participant_ids), $oStr));
        if ($this->project_manager_id && in_array((string)$this->project_manager_id, $oStr)) {
            $this->project_manager_id = null;
        }
    }

    public function updatedSteeringCommitteeIds(): void
    {
        $scStr = array_map('strval', $this->steering_committee_ids);
        $this->sponsor_ids = array_values(array_diff(array_map('strval', $this->sponsor_ids), $scStr));
        $this->owner_ids = array_values(array_diff(array_map('strval', $this->owner_ids), $scStr));
        $this->selected_participant_ids = array_values(array_diff(array_map('strval', $this->selected_participant_ids), $scStr));
        if ($this->project_manager_id && in_array((string)$this->project_manager_id, $scStr)) {
            $this->project_manager_id = null;
        }
    }

    public function selectLeader(int $userId): void
    {
        $uStr = (string)$userId;
        $this->sponsor_ids = array_values(array_diff(array_map('strval', $this->sponsor_ids), [$uStr]));
        $this->owner_ids = array_values(array_diff(array_map('strval', $this->owner_ids), [$uStr]));
        $this->steering_committee_ids = array_values(array_diff(array_map('strval', $this->steering_committee_ids), [$uStr]));
        $this->project_manager_id = $userId;
    }

    public function generateCode()
    {
        $this->code = Project::generateCodeForSubsidiary($this->subsidiary_id);
    }

    public function toggleAllParticipants()
    {
        if (!$this->subsidiary_id) return;
        
        $excludedIds = array_map('strval', array_merge(
            $this->sponsor_ids,
            $this->owner_ids,
            $this->steering_committee_ids
        ));
        if ($this->project_manager_id) {
            $excludedIds[] = (string)$this->project_manager_id;
        }

        $availableIds = User::getUsersForSubsidiary($this->subsidiary_id)
            ->reject(fn($u) => in_array((string)$u->id, $excludedIds))
            ->pluck('id')
            ->map(fn($id) => (string)$id)
            ->toArray();

        if (count($this->selected_participant_ids) >= count($availableIds) && count($availableIds) > 0) {
            $this->selected_participant_ids = [];
        } else {
            $this->selected_participant_ids = $availableIds;
        }
    }

    public function updatedCharterFile()
    {
        $this->validate([
            'charterFile' => 'required|file|max:10240|mimes:txt,json,docx,pdf',
        ]);

        $filePath = $this->charterFile->getRealPath();
        $originalName = $this->charterFile->getClientOriginalName();

        $this->extractedData = ProjectDetailExtractor::extract($filePath, $originalName);
        $this->rawTextPreview = $this->extractedData['raw_text'] ?? '';

        if (!empty($this->extractedData['name'])) {
            $this->name = $this->extractedData['name'];
        }
        if (!empty($this->extractedData['description'])) {
            $this->description = $this->extractedData['description'];
        }
        if (!empty($this->extractedData['start_date'])) {
            $this->start_date = $this->extractedData['start_date'];
        }
        if (!empty($this->extractedData['deadline'])) {
            $this->deadline = $this->extractedData['deadline'];
        }
        if (!empty($this->extractedData['subsidiary_id'])) {
            $this->subsidiary_id = $this->extractedData['subsidiary_id'];
            $this->generateCode();
        }
        if (!empty($this->extractedData['project_manager_id'])) {
            $this->project_manager_id = $this->extractedData['project_manager_id'];
        }
        if (!empty($this->extractedData['participant_ids'])) {
            $this->selected_participant_ids = array_map('strval', $this->extractedData['participant_ids']);
        }

        $this->dispatch('toast', message: 'Charter file parsed and pre-filled successfully!', type: 'success');
    }

    public function clearCharterFile()
    {
        $this->reset(['charterFile', 'extractedData', 'rawTextPreview']);
        $this->dispatch('toast', message: 'Charter cleared. You can edit fields manually.', type: 'info');
    }

    public function save()
    {
        if (empty($this->code) || Project::withTrashed()->where('code', $this->code)->exists()) {
            $this->generateCode();
        }

        if (!$this->subsidiary_id) {
            $firstSub = Subsidiary::first();
            if ($firstSub) $this->subsidiary_id = $firstSub->id;
        }

        if (!$this->project_manager_id) {
            $pms = User::getPmsForSubsidiary($this->subsidiary_id);
            if ($pms->count() > 0) {
                $this->project_manager_id = $pms->first()->id;
            }
        }

        $validationRules = [
            'subsidiary_id' => 'required|exists:subsidiaries,id',
            'name' => 'required|string|max:255',
            'project_manager_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'sponsor_ids' => 'nullable|array',
            'owner_ids' => 'nullable|array',
            'steering_committee_ids' => 'nullable|array',
            'selected_participant_ids' => 'nullable|array',
            'creation_option' => 'required|in:template,manual',
        ];

        if ($this->creation_option === 'manual') {
            $validationRules['start_date'] = 'required|date';
            $validationRules['deadline'] = 'required|date|after_or_equal:start_date';
        } else {
            $validationRules['selected_template_id'] = 'required|exists:project_templates,id';
            $validationRules['start_date'] = 'required|date';
        }

        $this->validate($validationRules);

        if ($this->creation_option === 'template') {
            $this->calculateTemplateDeadline();
        }

        $isSelfAssigned = (auth()->id() == $this->project_manager_id);

        $project = Project::create([
            'code' => strtoupper($this->code),
            'name' => $this->name,
            'description' => $this->description,
            'subsidiary_id' => $this->subsidiary_id,
            'project_manager_id' => $this->project_manager_id,
            'pm_accepted' => $isSelfAssigned,
            'pm_accepted_at' => $isSelfAssigned ? now() : null,
            'created_by' => auth()->id(),
            'priority' => Priority::MEDIUM,
            'status' => ProjectStatus::PLANNING,
            'start_date' => $this->start_date ?: null,
            'deadline' => $this->deadline ?: null,
            'estimated_budget' => 0,
            'wbs_breakdown_type' => $this->creation_option,
            'template_id' => ($this->creation_option === 'template') ? $this->selected_template_id : null,
            'health' => ProjectHealth::ON_TRACK,
        ]);

        // Sync all Governance roles (Sponsors, Owners, Steering Committee, Project Leader, Core Team)
        $syncData = [];

        // 1. Project Sponsors
        foreach (array_map('intval', $this->sponsor_ids) as $sId) {
            if ($sId > 0) $syncData[$sId] = ['role' => 'sponsor'];
        }

        // 2. Project Owners
        foreach (array_map('intval', $this->owner_ids) as $oId) {
            if ($oId > 0) $syncData[$oId] = ['role' => 'owner'];
        }

        // 3. Steering Committee
        foreach (array_map('intval', $this->steering_committee_ids) as $scId) {
            if ($scId > 0) $syncData[$scId] = ['role' => 'steering_committee'];
        }

        // 4. Project Leader (lead) - Highest Priority
        if ($this->project_manager_id) {
            $syncData[(int)$this->project_manager_id] = ['role' => 'lead'];
        }

        // 5. Core Project Team (members)
        foreach (array_map('intval', $this->selected_participant_ids) as $mId) {
            if ($mId > 0 && !isset($syncData[$mId])) {
                $syncData[$mId] = ['role' => 'member'];
            }
        }

        // 6. Custom roles (dynamic, from Roles & Permissions Hub)
        foreach ($this->customRoleAssignments as $roleCode => $userIds) {
            foreach (array_map('intval', $userIds) as $uId) {
                if ($uId > 0 && !isset($syncData[$uId])) {
                    $syncData[$uId] = ['role' => $roleCode];
                }
            }
        }

        $project->members()->sync($syncData);

        // Copy WBS items if created from template
        if ($this->creation_option === 'template' && $this->selected_template_id) {
            $startDateObj = \Carbon\Carbon::parse($this->start_date);
            $this->createWbsFromTemplate($project, $this->selected_template_id, $startDateObj);
        }

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'created_project',
            'module'      => 'projects',
            'record_type' => Project::class,
            'record_id'   => $project->id,
            'new_values'  => [
                'name'          => $project->name,
                'code'          => $project->code,
                'subsidiary_id' => $project->subsidiary_id,
                'owner_id'      => $project->project_manager_id,
                'members_count' => count($syncData),
                'breakdown_type'=> $this->creation_option,
            ],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        // Register official Project Leadership Approval Request for the designated PM
        if (!$isSelfAssigned) {
            \App\Models\ApprovalRequest::create([
                'project_id' => $project->id,
                'request_type' => \App\Enums\ApprovalType::NEW_PROJECT_PLAN,
                'requested_by' => auth()->id(),
                'current_value' => null,
                'requested_value' => [
                    'project_name' => $project->name,
                    'project_code' => $project->code,
                    'assigned_pm_id' => $project->project_manager_id,
                    'start_date' => $project->start_date?->toDateString(),
                    'deadline' => $project->deadline?->toDateString(),
                    'template_name' => $project->template?->name ?? ($this->creation_option === 'template' ? 'Standard Blueprint' : 'Custom Agile WBS'),
                ],
                'reason' => "PMO Administration has assigned you as Project Leader for '{$project->name}' ({$project->code}). Review project details & blueprint to accept leadership.",
                'status' => \App\Enums\ApprovalStatus::PENDING,
                'submitted_at' => now(),
            ]);
        }

        // Send project assignment emails and in-app database notifications
        try {
            if ($isSelfAssigned) {
                // If PM created it directly, it is already accepted; notify all members
                $assignedUsers = User::whereIn('id', array_keys($syncData))->get();
                foreach ($assignedUsers as $assignedUser) {
                    $memberRole = $syncData[$assignedUser->id]['role'] ?? 'member';
                    $assignedUser->notify(new \App\Notifications\ProjectAssignmentNotification($project, $memberRole));
                    Mail::to($assignedUser->email)->send(new ProjectAssignedMail($project, $assignedUser, $memberRole));
                }
            } else {
                // If created by PMO Admin for a designated PM, ONLY notify the PM to review & accept first
                $pmUser = User::find($project->project_manager_id);
                if ($pmUser) {
                    $pmUser->notify(new \App\Notifications\ProjectAssignmentNotification($project, 'lead'));
                    Mail::to($pmUser->email)->send(new ProjectAssignedMail($project, $pmUser, 'lead'));
                }
            }
        } catch (\Throwable $e) {
            // Log mail/notification error but never block project creation
            \Log::error('ProjectAssignedNotification / Mail failed: ' . $e->getMessage());
        }

        session()->flash('message', 'Project initialized successfully!');
        return redirect()->route('projects.show', $project->id);
    }

    private function createWbsFromTemplate(Project $project, int $templateId, \Carbon\Carbon $projectStartDate)
    {
        $template = \App\Models\ProjectTemplate::findOrFail($templateId);
        $templateTasks = $template->tasks;

        // Group tasks by parent_id
        $tasksByParent = $templateTasks->groupBy(function($task) {
            return $task->parent_id ?: 'root';
        });

        $this->scheduleAndCreateTasks($project, $tasksByParent, 'root', $projectStartDate, null);

        // Cascade hierarchical schedule dates (Days, Weeks, Months, Hours)
        \App\Services\WbsScheduleCascadeService::cascadeProjectSchedule($project->id, true);

        // Recalculate WBS numbering and progress
        (new \App\Services\WbsNumberingService())->recalculateProjectWbsCodes($project->id);
        (new \App\Services\ProgressCalculationService())->updateProjectOverallProgress($project->id);
    }

    private function scheduleAndCreateTasks(Project $project, $tasksByParent, $parentIdKey, \Carbon\Carbon $currentStartDate, $parentWbsItemId = null)
    {
        $tasks = $tasksByParent->get($parentIdKey);
        if (!$tasks) {
            return $currentStartDate;
        }

        $lastEndDate = $currentStartDate->copy();

        foreach ($tasks as $task) {
            $durationInDays = $this->getDurationInDays($task->duration, $task->unit);
            $taskStartDate = $lastEndDate->copy();
            $taskEndDate = $taskStartDate->copy()->addDays(max(1, $durationInDays))->subDay();

            $itemType = $parentWbsItemId ? \App\Enums\ItemType::TASK : \App\Enums\ItemType::PHASE;

            $wbsItem = \App\Models\WbsItem::create([
                'project_id'   => $project->id,
                'parent_id'    => $parentWbsItemId,
                'wbs_code'     => 'TEMP',
                'item_type'    => $itemType,
                'title'        => $task->name ?? 'Task',
                'description'  => null,
                'duration'     => $durationInDays,
                'start_date'   => $taskStartDate->toDateString(),
                'end_date'     => $taskEndDate->toDateString(),
                'status'       => \App\Enums\WbsStatus::NOT_STARTED,
                'priority'     => \App\Enums\Priority::MEDIUM,
                'progress'     => 0,
                'sort_order'   => (int) ($task->order_index ?? 0),
                'is_milestone' => false,
                'created_by'   => auth()->id(),
            ]);

            // Recursively create children
            $childEndDate = $this->scheduleAndCreateTasks($project, $tasksByParent, (string)$task->id, $taskStartDate, $wbsItem->id);

            $lastEndDate = $taskEndDate;
        }

        return $lastEndDate;
    }

    private function getDurationInDays($duration, $unit)
    {
        if ($unit === 'weeks') {
            return $duration * 7;
        } elseif ($unit === 'months') {
            return $duration * 30;
        }
        return $duration;
    }

    public function render()
    {
        $subsidiaries = Subsidiary::all();
        $currentSub = $this->subsidiary_id ? Subsidiary::find($this->subsidiary_id) : null;
        $allPms = $this->subsidiary_id ? User::getPmsForSubsidiary($this->subsidiary_id) : User::where('is_active', true)->get();
        $allParticipants = $this->subsidiary_id ? User::getUsersForSubsidiary($this->subsidiary_id) : User::where('is_active', true)->get();
        $allTemplates = \App\Models\ProjectTemplate::all();

        // Load custom roles and filter their user lists
        $customRoles = $this->getCustomRoles();
        $customRoleUsers = [];
        foreach ($customRoles as $code => $roleMeta) {
            $search = trim($this->customRoleSearch[$code] ?? '');
            if ($search !== '') {
                $lowerSearch = strtolower($search);
                $customRoleUsers[$code] = $allParticipants->filter(function ($u) use ($lowerSearch) {
                    return str_contains(strtolower($u->name), $lowerSearch)
                        || str_contains(strtolower($u->email), $lowerSearch)
                        || str_contains(strtolower($u->subsidiary->code ?? ''), $lowerSearch);
                })->values();
            } else {
                $customRoleUsers[$code] = $allParticipants;
            }
        }

        // Filter Leaders by search query
        $lSearch = trim($this->leaderSearch);
        if ($lSearch !== '') {
            $lowerLSearch = strtolower($lSearch);
            $pms = $allPms->filter(function ($user) use ($lowerLSearch) {
                return str_contains(strtolower($user->name), $lowerLSearch)
                    || str_contains(strtolower($user->email), $lowerLSearch)
                    || str_contains(strtolower($user->subsidiary->code ?? ''), $lowerLSearch)
                    || str_contains(strtolower($user->subsidiary->name ?? ''), $lowerLSearch);
            })->values();
        } else {
            $pms = $allPms;
        }

        // Filter Sponsors by search query
        $sSearch = trim($this->sponsorSearch);
        if ($sSearch !== '') {
            $lowerSSearch = strtolower($sSearch);
            $sponsors = $allParticipants->filter(function ($user) use ($lowerSSearch) {
                return str_contains(strtolower($user->name), $lowerSSearch)
                    || str_contains(strtolower($user->email), $lowerSSearch)
                    || str_contains(strtolower($user->subsidiary->code ?? ''), $lowerSSearch);
            })->values();
        } else {
            $sponsors = $allParticipants;
        }

        // Filter Owners by search query
        $oSearch = trim($this->ownerSearch);
        if ($oSearch !== '') {
            $lowerOSearch = strtolower($oSearch);
            $owners = $allParticipants->filter(function ($user) use ($lowerOSearch) {
                return str_contains(strtolower($user->name), $lowerOSearch)
                    || str_contains(strtolower($user->email), $lowerOSearch)
                    || str_contains(strtolower($user->subsidiary->code ?? ''), $lowerOSearch);
            })->values();
        } else {
            $owners = $allParticipants;
        }

        // Filter Steering Committee by search query
        $scSearch = trim($this->steeringSearch);
        if ($scSearch !== '') {
            $lowerScSearch = strtolower($scSearch);
            $steeringCommittee = $allParticipants->filter(function ($user) use ($lowerScSearch) {
                return str_contains(strtolower($user->name), $lowerScSearch)
                    || str_contains(strtolower($user->email), $lowerScSearch)
                    || str_contains(strtolower($user->subsidiary->code ?? ''), $lowerScSearch);
            })->values();
        } else {
            $steeringCommittee = $allParticipants;
        }

        // Filter participants by search query
        $search = trim($this->participantSearch);
        if ($search !== '') {
            $lowerSearch = strtolower($search);
            $participants = $allParticipants->filter(function ($user) use ($lowerSearch) {
                return str_contains(strtolower($user->name), $lowerSearch)
                    || str_contains(strtolower($user->email), $lowerSearch)
                    || str_contains(strtolower($user->subsidiary->code ?? ''), $lowerSearch)
                    || str_contains(strtolower($user->subsidiary->name ?? ''), $lowerSearch);
            })->values();
        } else {
            $participants = $allParticipants;
        }

        // Filter templates by search query
        $tSearch = trim($this->templateSearch);
        if ($tSearch !== '') {
            $lowerTSearch = strtolower($tSearch);
            $templates = $allTemplates->filter(function ($tpl) use ($lowerTSearch) {
                return str_contains(strtolower($tpl->name), $lowerTSearch)
                    || str_contains(strtolower($tpl->description ?? ''), $lowerTSearch);
            })->values();
        } else {
            $templates = $allTemplates;
        }

        return view('livewire.project-create', compact(
            'subsidiaries', 'currentSub', 'pms', 'allPms',
            'sponsors', 'owners', 'steeringCommittee',
            'participants', 'allParticipants', 'templates', 'allTemplates',
            'customRoles', 'customRoleUsers'
        ));
    }
}
