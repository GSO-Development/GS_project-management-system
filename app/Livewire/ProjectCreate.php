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

class ProjectCreate extends Component
{
    use WithFileUploads;

    public string $code = '';
    public string $name = '';
    public ?string $description = null;
    public ?int $subsidiary_id = null;
    public ?int $project_manager_id = null;
    public array $selected_participant_ids = [];
    public ?string $start_date = null;
    public ?string $deadline = null;
    public string $participantSearch = '';

    // Breakdown Method Properties
    public string $creation_option = 'template'; // 'template' or 'manual'
    public ?int $selected_template_id = null;
    public bool $showManualDatesModal = false;
    public ?string $calculatedDeadline = null;

    // Charter Extractor properties
    public $charterFile;
    public ?array $extractedData = null;
    public ?string $rawTextPreview = null;
    public bool $showExtractor = true;

    public function mount()
    {
        $this->start_date = now()->format('Y-m-d');
        $this->deadline = null;

        $firstSub = Subsidiary::first();
        if ($firstSub) {
            $this->subsidiary_id = $firstSub->id;
        }

        $this->autoSelectPmAndParticipants();
        $this->generateCode();
    }

    public function updatedCreationOption($value)
    {
        if ($value === 'manual') {
            $this->showManualDatesModal = true;
            if (!$this->start_date) {
                $this->start_date = now()->format('Y-m-d');
            }
            if (!$this->deadline) {
                $this->deadline = now()->addMonths(3)->format('Y-m-d');
            }
        } else {
            $this->showManualDatesModal = false;
            $this->calculateTemplateDeadline();
        }
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
        if (!$this->subsidiary_id) return;

        // Auto select Project Manager for the selected Subsidiary
        $pms = User::getPmsForSubsidiary($this->subsidiary_id);
        if ($pms->count() > 0) {
            $this->project_manager_id = $pms->first()->id;
        } else {
            $firstPm = User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->first() ?? User::where('is_active', true)->first();
            $this->project_manager_id = $firstPm?->id;
        }

        // Do not auto select participants (keep unchecked as requested by the user)
        $this->selected_participant_ids = [];
    }

    public function generateCode()
    {
        $this->code = Project::generateCodeForSubsidiary($this->subsidiary_id);
    }

    public function toggleAllParticipants()
    {
        if (!$this->subsidiary_id) return;
        $allIds = User::getUsersForSubsidiary($this->subsidiary_id)->pluck('id')->map(fn($id) => (string)$id)->toArray();

        if (count($this->selected_participant_ids) === count($allIds)) {
            $this->selected_participant_ids = [];
        } else {
            $this->selected_participant_ids = $allIds;
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

        $project = Project::create([
            'code' => strtoupper($this->code),
            'name' => $this->name,
            'description' => $this->description,
            'subsidiary_id' => $this->subsidiary_id,
            'project_manager_id' => $this->project_manager_id,
            'created_by' => auth()->id(),
            'priority' => Priority::MEDIUM,
            'status' => ProjectStatus::PLANNING,
            'start_date' => $this->start_date ?: null,
            'deadline' => $this->deadline ?: null,
            'estimated_budget' => 0,
            'wbs_breakdown_type' => $this->creation_option,
            'health' => ProjectHealth::ON_TRACK,
        ]);

        // Sync Project Owner (lead) and selected Subsidiary Participants (members)
        $membersToSync = array_unique(array_merge([$this->project_manager_id], array_map('intval', $this->selected_participant_ids)));
        $syncData = [];
        foreach ($membersToSync as $memberId) {
            $syncData[$memberId] = ['role' => ($memberId == $this->project_manager_id) ? 'lead' : 'member'];
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

        // Send project assignment emails to all assigned members
        try {
            $assignedUsers = User::whereIn('id', array_keys($syncData))->get();
            foreach ($assignedUsers as $assignedUser) {
                $memberRole = $syncData[$assignedUser->id]['role'] ?? 'member';
                Mail::to($assignedUser->email)
                    ->send(new ProjectAssignedMail($project, $assignedUser, $memberRole));
            }
        } catch (\Throwable $e) {
            // Log mail error but never block project creation
            \Log::error('ProjectAssignedMail failed: ' . $e->getMessage());
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

        foreach ($tasks as $index => $task) {
            $days = $this->getDurationInDays($task->duration, $task->unit);

            // First task starts at current start date; subsequent tasks start the next day after previous sibling ends
            $taskStartDate = ($index === 0) ? $currentStartDate->copy() : $lastEndDate->copy()->addDay();
            $taskEndDate = $taskStartDate->copy()->addDays($days)->subDay();

            // Determine depth
            $depth = 0;
            $p = $task;
            while ($p->parent_id) {
                $depth++;
                $p = $p->parent;
            }

            $itemType = match($depth) {
                0 => \App\Enums\ItemType::PHASE,
                1 => \App\Enums\ItemType::TASK,
                default => \App\Enums\ItemType::SUBTASK,
            };

            $wbsItem = \App\Models\WbsItem::create([
                'project_id' => $project->id,
                'parent_id' => $parentWbsItemId,
                'wbs_code' => '', // computed by WbsNumberingService
                'item_type' => $itemType,
                'title' => $task->name,
                'description' => '',
                'assigned_user_id' => $project->project_manager_id,
                'start_date' => $taskStartDate->toDateString(),
                'end_date' => $taskEndDate->toDateString(),
                'duration' => $days,
                'status' => \App\Enums\WbsStatus::NOT_STARTED,
                'priority' => \App\Enums\Priority::MEDIUM,
                'progress' => 0,
                'weight' => 1.0,
                'is_milestone' => false,
                'sort_order' => $index + 1,
                'created_by' => auth()->id(),
            ]);

            if ($tasksByParent->has($task->id)) {
                // Children of this sibling start at the sibling's start date
                $this->scheduleAndCreateTasks($project, $tasksByParent, $task->id, $taskStartDate, $wbsItem->id);
            }

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

        $pms = $this->subsidiary_id ? User::getPmsForSubsidiary($this->subsidiary_id) : User::where('is_active', true)->get();
        $allParticipants = $this->subsidiary_id ? User::getUsersForSubsidiary($this->subsidiary_id) : User::where('is_active', true)->get();
        $templates = \App\Models\ProjectTemplate::all();

        // Filter participants by search query (name or email)
        $search = trim($this->participantSearch);
        if ($search !== '') {
            $lowerSearch = strtolower($search);
            $participants = $allParticipants->filter(function ($user) use ($lowerSearch) {
                return str_contains(strtolower($user->name), $lowerSearch)
                    || str_contains(strtolower($user->email), $lowerSearch);
            })->values();
        } else {
            $participants = $allParticipants;
        }

        return view('livewire.project-create', compact('subsidiaries', 'currentSub', 'pms', 'participants', 'allParticipants', 'templates'));
    }
}
