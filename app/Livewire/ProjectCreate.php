<?php

namespace App\Livewire;

use App\Enums\Priority;
use App\Enums\ProjectHealth;
use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use Livewire\Component;

class ProjectCreate extends Component
{
    public string $code = '';
    public string $name = '';
    public ?string $description = null;
    public ?int $subsidiary_id = null;
    public ?int $project_manager_id = null;
    public array $selected_participant_ids = [];
    public ?string $start_date = null;
    public ?string $deadline = null;
    public string $participantSearch = '';

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
            $firstPm = User::role(['super_admin', 'project_manager'])->first();
            $this->project_manager_id = $firstPm?->id;
        }

        // Auto select all participants belonging to the selected Subsidiary
        $participants = User::getUsersForSubsidiary($this->subsidiary_id);
        $this->selected_participant_ids = $participants->pluck('id')->map(fn($id) => (string)$id)->toArray();
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

    public function save()
    {
        if (empty($this->code) || Project::where('code', $this->code)->exists()) {
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

        $this->validate([
            'subsidiary_id' => 'required|exists:subsidiaries,id',
            'name' => 'required|string|max:255',
            'project_manager_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
            'selected_participant_ids' => 'nullable|array',
        ]);

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
            'wbs_breakdown_type' => 'manual',
            'health' => ProjectHealth::ON_TRACK,
        ]);

        // Sync Project Owner (lead) and selected Subsidiary Participants (members)
        $membersToSync = array_unique(array_merge([$this->project_manager_id], array_map('intval', $this->selected_participant_ids)));
        $syncData = [];
        foreach ($membersToSync as $memberId) {
            $syncData[$memberId] = ['role' => ($memberId == $this->project_manager_id) ? 'lead' : 'member'];
        }
        $project->members()->sync($syncData);

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
            ],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        session()->flash('message', 'Project initialized with Subsidiary Owner & Participants successfully!');
        return redirect()->route('projects.show', $project->id);
    }

    public function render()
    {
        $subsidiaries = Subsidiary::all();
        $currentSub = $this->subsidiary_id ? Subsidiary::find($this->subsidiary_id) : null;

        $pms = $this->subsidiary_id ? User::getPmsForSubsidiary($this->subsidiary_id) : User::role(['super_admin', 'project_manager'])->get();
        $allParticipants = $this->subsidiary_id ? User::getUsersForSubsidiary($this->subsidiary_id) : User::where('is_active', true)->get();

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

        return view('livewire.project-create', compact('subsidiaries', 'currentSub', 'pms', 'participants', 'allParticipants'));
    }
}
