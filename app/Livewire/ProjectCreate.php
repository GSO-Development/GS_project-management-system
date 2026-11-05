<?php

namespace App\Livewire;

use App\Enums\Priority;
use App\Enums\ProjectStatus;
use App\Mail\ProjectAssignedMail;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use App\Services\RbacService;
use App\Services\TemplateWbsGeneratorService;
use App\Services\AzureGraphService;
use App\Services\AzureUserProvisioner;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;

class ProjectCreate extends Component
{
    public string $code = '';
    public string $name = '';
    public ?string $description = null;
    public ?string $estimated_budget = null;
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

    // Azure AD "add from directory" panel (only one team dropdown shows it at a time).
    // $azureRole: pm | owner | sponsor | steering | member | custom:{role_code}
    public ?string $azureRole = null;
    public string $azureQuery = '';
    public ?string $azureError = null;
    #[Locked]
    public array $azureResults = [];
    // Users provisioned/selected through Azure search – always kept visible in the pickers.
    #[Locked]
    public array $azureAddedUserIds = [];

    // Breakdown Method Properties
    public string $creation_option = 'manual'; // 'template' or 'manual'
    public ?int $selected_template_id = null;
    public bool $showManualDatesModal = false;
    public ?string $calculatedDeadline = null;

    public function mount()
    {
        $user = auth()->user();
        if (!$user || !$user->isPmoAdmin()) {
            session()->flash('error', 'Unauthorized. Only PMO Administrators are authorized to initiate new projects.');
            return redirect()->route('projects.index');
        }

        $this->start_date = now()->format('Y-m-d');
        $this->deadline = now()->addMonths(3)->format('Y-m-d');

        $firstSub = Subsidiary::first();
        if ($firstSub) {
            $this->subsidiary_id = $firstSub->id;
        }

        $this->project_manager_id = null;
        $this->selected_participant_ids = [];

        if ($draft = session()->pull('project_create_draft')) {
            foreach ($this->draftPropertyNames() as $property) {
                if (array_key_exists($property, $draft)) {
                    $this->{$property} = $draft[$property];
                }
            }
        }

        if ($selectedTemplateId = session()->pull('project_create_selected_template_id')) {
            if (\App\Models\ProjectTemplate::whereKey($selectedTemplateId)->exists()) {
                $this->creation_option = 'template';
                $this->selected_template_id = (int) $selectedTemplateId;
            }
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

    public function openTemplateLibrary()
    {
        $draft = [];
        foreach ($this->draftPropertyNames() as $property) {
            $draft[$property] = $this->{$property};
        }

        session()->put('project_create_draft', $draft);

        return redirect()->route('templates.index', ['select_for' => 'project']);
    }

    private function draftPropertyNames(): array
    {
        return [
            'code', 'name', 'description', 'subsidiary_id', 'project_manager_id',
            'sponsor_ids', 'owner_ids', 'steering_committee_ids',
            'selected_participant_ids', 'start_date', 'deadline', 'estimated_budget',
            'customRoleAssignments', 'creation_option', 'selected_template_id',
        ];
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

    private function getDurationInDays($duration, $unit): int
    {
        return TemplateWbsGeneratorService::getDurationInDays($duration, $unit);
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
        foreach ($this->customRoleAssignments as $cCode => $cIds) {
            $this->customRoleAssignments[$cCode] = array_values(array_diff(array_map('strval', (array)$cIds), $sStr));
        }
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
        foreach ($this->customRoleAssignments as $cCode => $cIds) {
            $this->customRoleAssignments[$cCode] = array_values(array_diff(array_map('strval', (array)$cIds), $oStr));
        }
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
        foreach ($this->customRoleAssignments as $cCode => $cIds) {
            $this->customRoleAssignments[$cCode] = array_values(array_diff(array_map('strval', (array)$cIds), $scStr));
        }
        if ($this->project_manager_id && in_array((string)$this->project_manager_id, $scStr)) {
            $this->project_manager_id = null;
        }
    }

    public function updatedCustomRoleAssignments(): void
    {
        $allCustomSelected = [];
        foreach ($this->customRoleAssignments as $code => $ids) {
            $allCustomSelected = array_merge($allCustomSelected, array_map('strval', (array)$ids));
        }
        $allCustomSelected = array_values(array_unique($allCustomSelected));

        if (!empty($allCustomSelected)) {
            $this->selected_participant_ids = array_values(array_diff(array_map('strval', $this->selected_participant_ids), $allCustomSelected));
            $this->sponsor_ids = array_values(array_diff(array_map('strval', $this->sponsor_ids), $allCustomSelected));
            $this->owner_ids = array_values(array_diff(array_map('strval', $this->owner_ids), $allCustomSelected));
            $this->steering_committee_ids = array_values(array_diff(array_map('strval', $this->steering_committee_ids), $allCustomSelected));
            if ($this->project_manager_id && in_array((string)$this->project_manager_id, $allCustomSelected)) {
                $this->project_manager_id = null;
            }
        }
    }

    public function removeCustomRoleUser(string $roleCode, int $userId): void
    {
        if (isset($this->customRoleAssignments[$roleCode])) {
            $uStr = (string)$userId;
            $this->customRoleAssignments[$roleCode] = array_values(array_filter(
                array_map('strval', (array)$this->customRoleAssignments[$roleCode]),
                fn($id) => $id !== $uStr && (int)$id !== $userId
            ));
        }
    }

    public function selectLeader(int $userId): void
    {
        $uStr = (string)$userId;
        $this->sponsor_ids = array_values(array_diff(array_map('strval', $this->sponsor_ids), [$uStr]));
        $this->owner_ids = array_values(array_diff(array_map('strval', $this->owner_ids), [$uStr]));
        $this->steering_committee_ids = array_values(array_diff(array_map('strval', $this->steering_committee_ids), [$uStr]));
        $this->selected_participant_ids = array_values(array_diff(array_map('strval', $this->selected_participant_ids), [$uStr]));
        foreach ($this->customRoleAssignments as $cCode => $cIds) {
            $this->customRoleAssignments[$cCode] = array_values(array_diff(array_map('strval', (array)$cIds), [$uStr]));
        }
        $this->project_manager_id = $userId;
    }

    // ────────────────────────────────────────────────────────────────
    //  Azure AD – add people who are not yet in the system
    // ────────────────────────────────────────────────────────────────

    protected function canUseAzureDirectory(): bool
    {
        return (bool) auth()->user()?->isPmoAdmin();
    }

    protected function azureIsConfigured(): bool
    {
        return !empty(config('services.azure.tenant'))
            && !empty(config('services.azure.client_id'))
            && !empty(config('services.azure.client_secret'));
    }

    protected function isValidAzureRole(string $role): bool
    {
        if (in_array($role, ['pm', 'owner', 'sponsor', 'steering', 'member'], true)) {
            return true;
        }

        return str_starts_with($role, 'custom:')
            && array_key_exists(substr($role, 7), $this->getCustomRoles());
    }

    protected function azureRoleLabel(string $role): string
    {
        return match (true) {
            $role === 'pm'       => 'Project Manager',
            $role === 'owner'    => 'Project Owner',
            $role === 'sponsor'  => 'Project Sponsor',
            $role === 'steering' => 'Steering Committee',
            $role === 'member'   => 'Core Project Team',
            str_starts_with($role, 'custom:') => (string) ($this->getCustomRoles()[substr($role, 7)]['name']
                ?? ucwords(str_replace(['_', '-'], ' ', substr($role, 7)))),
            default => 'Team Member',
        };
    }

    // ── Live Azure AD lookup while typing in a dropdown's search box ──────────

    public function updatedLeaderSearch($value): void       { $this->autoAzureSearch('pm', (string) $value); }
    public function updatedOwnerSearch($value): void        { $this->autoAzureSearch('owner', (string) $value); }
    public function updatedSponsorSearch($value): void      { $this->autoAzureSearch('sponsor', (string) $value); }
    public function updatedSteeringSearch($value): void     { $this->autoAzureSearch('steering', (string) $value); }
    public function updatedParticipantSearch($value): void  { $this->autoAzureSearch('member', (string) $value); }

    public function updatedCustomRoleSearch($value, $key = null): void
    {
        if (is_string($key) && $key !== '') {
            $this->autoAzureSearch('custom:' . $key, (string) $value);
        }
    }

    protected function autoAzureSearch(string $role, string $text): void
    {
        if (!$this->canUseAzureDirectory() || !$this->isValidAzureRole($role)) {
            return;
        }

        $this->azureRole    = $role;
        $this->azureQuery   = trim($text);
        $this->azureResults = [];
        $this->azureError   = null;

        $this->runAzureSearch();
    }

    public function closeAzureSearch(): void
    {
        $this->azureRole    = null;
        $this->azureQuery   = '';
        $this->azureResults = [];
        $this->azureError   = null;
    }

    /** Clear the dropdown's own search box once someone has been picked from Azure AD. */
    protected function clearLocalSearchFor(string $role): void
    {
        match (true) {
            $role === 'pm'       => $this->leaderSearch = '',
            $role === 'owner'    => $this->ownerSearch = '',
            $role === 'sponsor'  => $this->sponsorSearch = '',
            $role === 'steering' => $this->steeringSearch = '',
            $role === 'member'   => $this->participantSearch = '',
            str_starts_with($role, 'custom:') => $this->customRoleSearch[substr($role, 7)] = '',
            default => null,
        };
    }

    protected function runAzureSearch(): void
    {
        $this->azureError   = null;
        $this->azureResults = [];

        $query = trim($this->azureQuery);
        if (!$this->canUseAzureDirectory() || mb_strlen($query) < 2) {
            return;
        }

        if (!$this->azureIsConfigured()) {
            $this->azureError = 'Azure AD is not configured. Please set the Azure tenant / client credentials first.';
            return;
        }

        try {
            $results = AzureGraphService::searchUsers($query, 8);
        } catch (\Throwable $e) {
            Log::error('ProjectCreate: Azure user search failed - ' . $e->getMessage());
            $this->azureError = 'Could not reach Azure AD right now. Please try again.';
            return;
        }

        if (empty($results)) {
            return;
        }

        // Which of these people already exist in the system?
        $azureIds = array_values(array_filter(array_column($results, 'id')));
        $emails   = array_values(array_filter(array_column($results, 'email')));

        $existing = User::withTrashed()
            ->where(function ($q) use ($azureIds, $emails) {
                $q->whereIn('azure_id', $azureIds)->orWhereIn('email', $emails);
            })
            ->get();

        // People the dropdown already lists (and that match the typed text) need no Azure row.
        $visible = User::getUsersForSubsidiary($this->subsidiary_id);
        if (!empty($this->azureAddedUserIds)) {
            $visible = $visible->concat(User::whereIn('id', $this->azureAddedUserIds)->get());
        }
        $visibleIds = $visible->pluck('id')->map(fn($id) => (int) $id)->all();
        $needle     = mb_strtolower($query);

        $rows = [];
        foreach ($results as $row) {
            $match = $existing->first(function ($u) use ($row) {
                return (!empty($u->azure_id) && $u->azure_id === ($row['id'] ?? null))
                    || strcasecmp((string) $u->email, (string) ($row['email'] ?? '')) === 0;
            });

            $row['in_system']      = (bool) $match;
            $row['blocked_reason'] = null;

            if ($match && !$match->trashed()) {
                $alreadyListed = in_array((int) $match->id, $visibleIds, true)
                    && $match->is_active
                    && !$match->isGovernanceOrAdminUser()
                    && (str_contains(mb_strtolower((string) $match->name), $needle)
                        || str_contains(mb_strtolower((string) $match->email), $needle));

                if ($alreadyListed) {
                    continue;
                }

                if (!$match->is_active) {
                    $row['blocked_reason'] = 'Deactivated';
                } elseif ($match->isGovernanceOrAdminUser()) {
                    $row['blocked_reason'] = 'Admin account';
                }
            }

            $rows[] = $row;
        }

        $this->azureResults = $rows;
    }

    public function addAzureUser(int $index): void
    {
        if (!$this->canUseAzureDirectory()) {
            $this->dispatch('toast', message: 'Only PMO Admins can add users from Azure AD.', type: 'error');
            return;
        }

        $role      = $this->azureRole;
        $azureUser = $this->azureResults[$index] ?? null;

        if (!$role || !$azureUser || !$this->isValidAzureRole($role)) {
            return;
        }

        try {
            ['user' => $user, 'created' => $created] = AzureUserProvisioner::provision($azureUser, $this->subsidiary_id);
        } catch (\RuntimeException $e) {
            $this->azureError = $e->getMessage();
            return;
        } catch (\Throwable $e) {
            Log::error('ProjectCreate: Azure user provisioning failed - ' . $e->getMessage());
            $this->azureError = 'Could not add this person. Please try again.';
            return;
        }

        // Keep the person visible in every picker, even if their subsidiary differs from the project's.
        $this->azureAddedUserIds = array_values(array_unique(array_merge($this->azureAddedUserIds, [(int) $user->id])));

        $currentRole = $this->currentRoleKeyOf((int) $user->id);
        if ($currentRole !== null && $currentRole !== $role) {
            $this->azureError = "{$user->name} is already assigned as {$this->azureRoleLabel($currentRole)} in this project. Remove them from that role first.";
            return;
        }

        $this->assignUserToRole($role, (int) $user->id);

        $label = $this->azureRoleLabel($role);
        $this->clearLocalSearchFor($role);
        $this->closeAzureSearch();

        $this->dispatch('azure-user-added', role: $role);
        $this->dispatch(
            'toast',
            message: $created
                ? "{$user->name} was added to the system from Azure AD and assigned as {$label}."
                : "{$user->name} assigned as {$label}.",
            type: 'success'
        );
    }

    /** Which project role (key) a user currently holds in this form, if any. */
    protected function currentRoleKeyOf(int $userId): ?string
    {
        $uStr = (string) $userId;

        if ($this->project_manager_id && (string) $this->project_manager_id === $uStr) {
            return 'pm';
        }
        if (in_array($uStr, array_map('strval', $this->owner_ids), true)) {
            return 'owner';
        }
        if (in_array($uStr, array_map('strval', $this->sponsor_ids), true)) {
            return 'sponsor';
        }
        if (in_array($uStr, array_map('strval', $this->steering_committee_ids), true)) {
            return 'steering';
        }
        foreach ($this->customRoleAssignments as $code => $ids) {
            if (in_array($uStr, array_map('strval', (array) $ids), true)) {
                return 'custom:' . $code;
            }
        }
        if (in_array($uStr, array_map('strval', $this->selected_participant_ids), true)) {
            return 'member';
        }

        return null;
    }

    protected function assignUserToRole(string $role, int $userId): void
    {
        $uStr = (string) $userId;
        $with = fn(array $ids): array => array_values(array_unique(array_merge(array_map('strval', $ids), [$uStr])));

        switch (true) {
            case $role === 'pm':
                $this->selectLeader($userId);
                $this->resetErrorBag('project_manager_id');
                break;

            case $role === 'owner':
                $this->owner_ids = $with($this->owner_ids);
                $this->updatedOwnerIds();
                break;

            case $role === 'sponsor':
                $this->sponsor_ids = $with($this->sponsor_ids);
                $this->updatedSponsorIds();
                break;

            case $role === 'steering':
                $this->steering_committee_ids = $with($this->steering_committee_ids);
                $this->updatedSteeringCommitteeIds();
                break;

            case $role === 'member':
                $this->selected_participant_ids = $with($this->selected_participant_ids);
                break;

            case str_starts_with($role, 'custom:'):
                $code = substr($role, 7);
                $this->customRoleAssignments[$code] = $with((array) ($this->customRoleAssignments[$code] ?? []));
                $this->updatedCustomRoleAssignments();
                break;
        }
    }

    public function generateCode()
    {
        $this->code = Project::generateCodeForSubsidiary($this->subsidiary_id);
    }

    public function toggleAllParticipants()
    {
        if (!$this->subsidiary_id) return;
        
        $allCustomIds = [];
        foreach ($this->customRoleAssignments as $cIds) {
            $allCustomIds = array_merge($allCustomIds, array_map('strval', (array)$cIds));
        }

        $excludedIds = array_map('strval', array_merge(
            $this->sponsor_ids,
            $this->owner_ids,
            $this->steering_committee_ids,
            $allCustomIds
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

        // Validate that PM is not an admin/governance user
        if ($this->project_manager_id) {
            $selectedPmUser = User::find($this->project_manager_id);
            if ($selectedPmUser && $selectedPmUser->isGovernanceOrAdminUser()) {
                $this->addError('project_manager_id', 'Super Admins, PMO Admins, and Admin Viewers cannot be assigned as Project Manager.');
                return;
            }
        }

        $validationRules = [
            'subsidiary_id' => 'required|exists:subsidiaries,id',
            'name' => 'required|string|max:255',
            'project_manager_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'estimated_budget' => 'nullable|numeric|min:0|max:9999999999999.99',
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

        $project = \App\Services\AuditLogService::withoutObserver(function () use ($isSelfAssigned) {
            return Project::create([
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
                'estimated_budget' => ($this->estimated_budget !== null && $this->estimated_budget !== '') ? $this->estimated_budget : 0,
                'wbs_breakdown_type' => $this->creation_option,
                'template_id' => ($this->creation_option === 'template') ? $this->selected_template_id : null,
            ]);
        });

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

        // 5. Custom roles (dynamic, from Roles & Permissions Hub)
        foreach ($this->customRoleAssignments as $roleCode => $userIds) {
            foreach (array_map('intval', (array)$userIds) as $uId) {
                if ($uId > 0 && !isset($syncData[$uId])) {
                    $syncData[$uId] = ['role' => $roleCode];
                }
            }
        }

        // 6. Core Project Team (members)
        foreach (array_map('intval', $this->selected_participant_ids) as $mId) {
            if ($mId > 0 && !isset($syncData[$mId])) {
                $syncData[$mId] = ['role' => 'member'];
            }
        }

        $project->members()->sync($syncData);

        // Copy WBS items if created from template
        if ($this->creation_option === 'template' && $this->selected_template_id) {
            $startDateObj = \Carbon\Carbon::parse($this->start_date);
            $this->createWbsFromTemplate($project, $this->selected_template_id, $startDateObj);
        }

        \App\Services\AuditLogService::log(
            action: 'created_project',
            module: 'projects',
            record: $project,
            new: [
                'name'          => $project->name,
                'code'          => $project->code,
                'subsidiary_id' => $project->subsidiary_id,
                'owner_id'      => $project->project_manager_id,
                'members_count' => count($syncData),
                'breakdown_type'=> $this->creation_option,
            ],
            actorType: \App\Models\ActivityLog::ACTOR_USER
        );

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

        if (auth()->user()?->isSuperAdmin()) {
            return redirect()->route('projects.index');
        }

        return redirect()->route('projects.show', $project->id);
    }

    private function createWbsFromTemplate(Project $project, int $templateId, \Carbon\Carbon $projectStartDate)
    {
        app(\App\Services\TemplateWbsGeneratorService::class)->generate($project, $templateId, $projectStartDate, true);
    }

    public function render()
    {
        $subsidiaries = Subsidiary::all();
        $currentSub = $this->subsidiary_id ? Subsidiary::find($this->subsidiary_id) : null;
        $allPms = User::getPmsForSubsidiary($this->subsidiary_id);
        $allParticipants = User::getUsersForSubsidiary($this->subsidiary_id);

        // People just added through Azure AD search must always be selectable/visible here.
        if (!empty($this->azureAddedUserIds)) {
            $azureAdded = User::with(['subsidiary', 'roles'])
                ->whereIn('id', $this->azureAddedUserIds)
                ->where('is_active', true)
                ->get()
                ->reject(fn($u) => $u->isGovernanceOrAdminUser());

            $mergeAzure = fn($list) => $list->concat($azureAdded)
                ->unique('id')
                ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
                ->values();

            $allPms          = $mergeAzure($allPms);
            $allParticipants = $mergeAzure($allParticipants);
        }
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
