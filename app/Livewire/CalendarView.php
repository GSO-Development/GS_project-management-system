<?php

namespace App\Livewire;

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Models\CalendarEvent;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\AzureGraphService;
use App\Services\ProgressCalculationService;
use Carbon\Carbon;
use Livewire\Component;

class CalendarView extends Component
{
    // View Modes: 'calendar' (Month Grid), 'week' (Week Schedule), 'day' (Day Agenda), 'list' (Timeline List)
    public string $viewMode = 'calendar';

    // Scopes & Filters
    public string $scopeFilter = 'my_events'; // Default to 'my_events' (My Assigned)
    public string $subsidiaryFilter = 'all';
    public string $projectFilter = 'all';
    public string $categoryFilter = 'all'; // 'all', 'milestone', 'meeting', 'review', 'task', 'deadline'
    public string $search = '';

    // Calendar state
    public int $year;
    public int $month;
    public string $selectedDate = '';

    // Event Detail Modal
    public bool $showEventModal = false;
    public ?array $selectedEvent = null;

    // Event Creation Modal
    // Event Creation Modal & Scheduling Wizard
    public bool $showCreateEventModal = false;
    public int $createEventStep = 1; // 1 = Details & Project Team, 2 = Team Availability & Time
    public ?int $newEventProject = null;
    public string $newEventTitle = '';
    public string $newEventDescription = '';
    public string $newEventType = 'meeting'; // 'meeting', 'review', 'milestone', 'task', 'deadline'
    public ?string $newEventDate = null;
    public ?string $newEventEndDate = null;
    public ?string $newEventStartTime = null;
    public ?string $newEventEndTime = null;
    public bool $newEventIsAllDay = false;
    public string $newEventLocation = '';
    public string $newEventMeetingLink = '';
    public string $newEventPriority = 'medium';
    public array $newEventAttendees = [];

    // Step 2 Availability & Microsoft Calendar Schedule state
    public array $attendeeSchedules = [];
    public array $combinedBusySlots = [];
    public array $suggestedSlots = [];
    public bool $isLoadingAvailability = false;

    // Outlook / Calendar Subscription Modal
    public bool $showSubscribeModal = false;

    // Edit Event Modal state
    public bool $showEditEventModal = false;
    public ?int $editEventId = null;
    public ?int $editEventProject = null;
    public string $editEventTitle = '';
    public string $editEventDescription = '';
    public string $editEventType = 'meeting';
    public ?string $editEventDate = null;
    public ?string $editEventEndDate = null;
    public ?string $editEventStartTime = null;
    public ?string $editEventEndTime = null;
    public bool $editEventIsAllDay = false;
    public string $editEventLocation = '';
    public string $editEventMeetingLink = '';
    public string $editEventPriority = 'medium';
    public array $editEventAttendees = [];

    public function mount(?string $date = null)
    {
        $today = now();
        $this->year = (int) $today->format('Y');
        $this->month = (int) $today->format('m');
        $this->selectedDate = $date ?: $today->format('Y-m-d');
        $this->newEventDate = $this->selectedDate;
        $this->newEventEndDate = $this->selectedDate;

        $user = auth()->user();
        $isPmoAdmin = $user && $user->isPmoAdmin();
        $isPm = $user && $user->isProjectManager();

        // Default to 'my_events' (My Assigned) display for all users
        $this->scopeFilter = 'my_events';

        // Initialize default project for creation modal if user has access
        $creatableProjects = $this->getCreatableProjects();
        if ($creatableProjects->isNotEmpty()) {
            $this->newEventProject = $creatableProjects->first()->id;
            $this->updatedNewEventProject($this->newEventProject);
        }
    }

    // ─── View & Date Navigation ───────────────────────────────────────

    public function setViewMode(string $mode): void
    {
        if (in_array($mode, ['calendar', 'week', 'day', 'list'])) {
            $this->viewMode = $mode;
        }
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $parsed = Carbon::parse($date);

        if ((int) $parsed->format('Y') !== $this->year || (int) $parsed->format('m') !== $this->month) {
            $this->year = (int) $parsed->format('Y');
            $this->month = (int) $parsed->format('m');
        }
    }

    public function prevMonth(): void
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->year = (int) $date->format('Y');
        $this->month = (int) $date->format('m');
    }

    public function nextMonth(): void
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->year = (int) $date->format('Y');
        $this->month = (int) $date->format('m');
    }

    public function prevPeriod(): void
    {
        if ($this->viewMode === 'day') {
            $current = Carbon::parse($this->selectedDate ?: now()->format('Y-m-d'))->subDay();
            $this->selectDate($current->format('Y-m-d'));
        } elseif ($this->viewMode === 'week') {
            $current = Carbon::parse($this->selectedDate ?: now()->format('Y-m-d'))->subWeek();
            $this->selectDate($current->format('Y-m-d'));
        } else {
            $this->prevMonth();
        }
    }

    public function nextPeriod(): void
    {
        if ($this->viewMode === 'day') {
            $current = Carbon::parse($this->selectedDate ?: now()->format('Y-m-d'))->addDay();
            $this->selectDate($current->format('Y-m-d'));
        } elseif ($this->viewMode === 'week') {
            $current = Carbon::parse($this->selectedDate ?: now()->format('Y-m-d'))->addWeek();
            $this->selectDate($current->format('Y-m-d'));
        } else {
            $this->nextMonth();
        }
    }

    public function today(): void
    {
        $today = now();
        $this->year = (int) $today->format('Y');
        $this->month = (int) $today->format('m');
        $this->selectedDate = $today->format('Y-m-d');
    }

    public function setScope(string $scope): void
    {
        $user = auth()->user();
        if (!$user) return;

        if (in_array($scope, ['all', 'my_events'])) {
            $this->scopeFilter = $scope;
        }
    }

    public function clearFilters(): void
    {
        $this->reset([
            'subsidiaryFilter',
            'projectFilter',
            'categoryFilter',
            'search',
        ]);
    }

    public function toggleCategory(string $category): void
    {
        $this->categoryFilter = ($this->categoryFilter === $category) ? 'all' : $category;
    }

    // ─── Permissions & Project Access ─────────────────────────────────

    /**
     * Determine if current user can create events.
     * PMO Admin can always create.
     * PM can create only if they manage at least one project.
     * Other assigned roles (Sponsor, Owner, Committee, Member) cannot create.
     */
    public function userCanCreateEvent(): bool
    {
        $user = auth()->user();
        if (!$user) return false;

        if ($user->isPmoAdmin()) {
            return true;
        }

        return Project::where('project_manager_id', $user->id)->exists();
    }

    /**
     * Get projects available for event creation based on strict permissions:
     * - PMO Admin: all projects across organization.
     * - Project Manager: STRICTLY only projects where project_manager_id == user->id.
     * - Other roles: empty collection.
     */
    public function getCreatableProjects()
    {
        $user = auth()->user();
        if (!$user) return collect();

        if ($user->isPmoAdmin()) {
            return Project::with('subsidiary')->orderBy('name')->get();
        }

        // PM: strictly only projects managed by this user
        return Project::where('project_manager_id', $user->id)
            ->with('subsidiary')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get projects visible in filter dropdowns (includes assigned/membership).
     */
    protected function getVisibleProjects()
    {
        $user = auth()->user();
        if (!$user) return collect();

        if ($user->isPmoAdmin()) {
            return Project::with('subsidiary')->orderBy('name')->get();
        }

        return Project::where(function($q) use ($user) {
            $q->where('project_manager_id', $user->id)
              ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id))
              ->orWhereHas('wbsItems', fn($wq) => $wq->where('assigned_user_id', $user->id));
        })->with('subsidiary')->orderBy('name')->get();
    }

    // ─── Dynamic Project Attendees & Permissions ──────────────────────

    /**
     * Get users dynamically associated with the currently selected project in the creation modal.
     * Includes Project Manager, assigned members with project roles, and WBS task assignees.
     */
    public function getProjectAttendeesProperty()
    {
        if (!$this->newEventProject) {
            return User::where('is_active', true)->orderBy('name')->get()->map(function($u) {
                $u->project_role_label = 'Corporate User';
                return $u;
            });
        }

        $project = Project::with(['projectManager', 'members', 'creator'])->find($this->newEventProject);
        if (!$project) {
            return collect();
        }

        $users = collect();

        // 1. Project Manager / Lead
        if ($project->projectManager && $project->projectManager->is_active) {
            $pm = $project->projectManager;
            $pm->project_role_label = 'Project Manager / Lead';
            $users->push($pm);
        }

        // 2. Project Members with Roles
        foreach ($project->members as $member) {
            if ($member->is_active && !$users->contains('id', $member->id)) {
                $roleLabel = match($member->pivot->role ?? '') {
                    'lead' => 'Project Lead',
                    'sponsor' => 'Project Sponsor',
                    'owner' => 'Project Owner',
                    'steering_committee' => 'Steering Committee',
                    'observer' => 'Observer',
                    default => 'Team Member',
                };
                $member->project_role_label = $roleLabel;
                $users->push($member);
            }
        }

        // 3. WBS Task Assignees
        $assignedIds = WbsItem::where('project_id', $project->id)
            ->whereNotNull('assigned_user_id')
            ->pluck('assigned_user_id')
            ->unique();
        if ($assignedIds->isNotEmpty()) {
            $assignees = User::whereIn('id', $assignedIds)->where('is_active', true)->get();
            foreach ($assignees as $au) {
                if (!$users->contains('id', $au->id)) {
                    $au->project_role_label = 'Task Assignee';
                    $users->push($au);
                }
            }
        }

        // 4. Project Creator
        if ($project->creator && $project->creator->is_active && !$users->contains('id', $project->creator->id)) {
            $creator = $project->creator;
            $creator->project_role_label = 'Project Creator';
            $users->push($creator);
        }

        return $users->sortBy('name')->values();
    }

    /**
     * Determine if a user has a corporate Microsoft Azure AD account.
     */
    public function userHasAzureAccount(User $user): bool
    {
        if ($user->isAzureUser() || !empty($user->azure_id) || !empty($user->azure_token)) {
            return true;
        }

        $email = strtolower($user->email ?? '');
        $corporateDomains = [
            '@georgesteuart.com',
            '@georgesteuart.lk',
            '@gsoptimize.lk',
            '@gshealth.lk',
            '@gsaviation.lk',
        ];

        foreach ($corporateDomains as $domain) {
            if (str_ends_with($email, $domain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * React when user selects a different project in the modal:
     * Refresh attendees and automatically select ALL assigned project members who have Microsoft Azure accounts.
     */
    public function updatedNewEventProject($value): void
    {
        $this->newEventAttendees = [];
        if ($value) {
            $selectedIds = [];

            // Automatically select ALL users assigned to this project who have Microsoft Azure accounts
            foreach ($this->projectAttendees as $att) {
                if ($this->userHasAzureAccount($att)) {
                    $selectedIds[] = (int) $att->id;
                }
            }

            // Always ensure Project Manager is included
            $project = Project::find($value);
            if ($project && $project->project_manager_id) {
                $selectedIds[] = (int) $project->project_manager_id;
            }

            // Always include current user
            $currId = auth()->id();
            if ($currId) {
                $selectedIds[] = (int) $currId;
            }

            $this->newEventAttendees = array_values(array_unique($selectedIds));
        }
    }

    public function updatedNewEventDate($value): void
    {
        if (empty($this->newEventEndDate) || $this->newEventEndDate < $value) {
            $this->newEventEndDate = $value;
        }
        if ($this->createEventStep === 2) {
            $this->computeAttendeeAvailability();
        }
    }

    public function toggleAttendee(int $userId): void
    {
        if (in_array($userId, $this->newEventAttendees)) {
            $this->newEventAttendees = array_values(array_diff($this->newEventAttendees, [$userId]));
        } else {
            $this->newEventAttendees[] = $userId;
        }
    }

    public function selectAllAttendees(): void
    {
        $this->newEventAttendees = $this->projectAttendees->pluck('id')->map(fn($id) => (int)$id)->all();
    }

    public function deselectAllAttendees(): void
    {
        $this->newEventAttendees = [];
    }

    // ─── Modal Actions & 2-Step Scheduling Wizard ─────────────────────

    public function openCreateEventModal(?string $date = null): void
    {
        if (!$this->userCanCreateEvent()) {
            $this->dispatch('toast', message: 'You do not have permission to schedule calendar events.', type: 'error');
            return;
        }

        $this->reset([
            'newEventTitle',
            'newEventDescription',
            'newEventLocation',
            'newEventMeetingLink',
            'newEventAttendees',
            'attendeeSchedules',
            'suggestedSlots',
        ]);

        $this->createEventStep = 1;
        $this->newEventType = 'meeting';
        $this->newEventPriority = 'medium';
        $this->newEventStartTime = null;
        $this->newEventEndTime = null;
        $this->newEventIsAllDay = false;

        $selected = $date ?: ($this->selectedDate ?: now()->format('Y-m-d'));
        $this->newEventDate = $selected;
        $this->newEventEndDate = $selected;

        $creatable = $this->getCreatableProjects();
        if ($creatable->isNotEmpty()) {
            $this->newEventProject = $creatable->first()->id;
            $this->updatedNewEventProject($this->newEventProject);
        }

        $this->showCreateEventModal = true;
    }

    /**
     * Advance from Step 1 to Step 2: Validate details and calculate team availability.
     */
    public function goToStep2(): void
    {
        if (empty($this->newEventEndDate) || $this->newEventEndDate < $this->newEventDate) {
            $this->newEventEndDate = $this->newEventDate;
        }

        $this->validate([
            'newEventTitle'       => 'required|string|max:255',
            'newEventType'        => 'required|in:meeting,review,milestone,task,deadline',
            'newEventDate'        => 'required|date',
            'newEventEndDate'     => 'nullable|date|after_or_equal:newEventDate',
            'newEventPriority'    => 'required|in:low,medium,high,critical',
            'newEventLocation'    => 'nullable|string|max:255',
            'newEventMeetingLink' => 'nullable|url|max:500',
        ]);

        $user = auth()->user();
        if (!$user->isPmoAdmin() && empty($this->newEventProject)) {
            $this->addError('newEventProject', 'Please select an associated project.');
            return;
        }

        if (empty($this->newEventAttendees)) {
            $this->newEventAttendees = [(int) $user->id];
        }

        $this->createEventStep = 2;
        $this->computeAttendeeAvailability();
    }

    /**
     * Navigate back from Step 2 to Step 1.
     */
    public function goToStep1(): void
    {
        $this->createEventStep = 1;
    }

    /**
     * Quick day navigation in Step 2 availability view.
     */
    public function changeScheduleDate(string $direction): void
    {
        $current = Carbon::parse($this->newEventDate ?: now()->format('Y-m-d'));
        if ($direction === 'next') {
            $current->addDay();
        } elseif ($direction === 'prev') {
            $current->subDay();
        }
        $this->newEventDate = $current->format('Y-m-d');
        if (empty($this->newEventEndDate) || $this->newEventEndDate < $this->newEventDate) {
            $this->newEventEndDate = $this->newEventDate;
        }
        $this->computeAttendeeAvailability();
    }

    /**
     * Compute Microsoft 365 & Local NexusPM availability for each selected attendee.
     */
    public function computeAttendeeAvailability(): void
    {
        $this->isLoadingAvailability = true;
        $date = $this->newEventDate ?: now()->format('Y-m-d');

        $attendeeIds = array_map('intval', $this->newEventAttendees);

        // Ensure ALL assigned project members with Microsoft Azure accounts are included in Step 2 availability
        if ($this->newEventProject) {
            foreach ($this->projectAttendees as $att) {
                if ($this->userHasAzureAccount($att) && !in_array((int) $att->id, $attendeeIds)) {
                    $attendeeIds[] = (int) $att->id;
                }
            }
        }

        if (empty($attendeeIds)) {
            $attendeeIds = [(int) auth()->id()];
        }
        $this->newEventAttendees = array_values(array_unique($attendeeIds));

        $attendees = User::whereIn('id', $attendeeIds)->get();
        $emails = $attendees->pluck('email')->filter()->all();

        // 1. Query Microsoft 365 Outlook schedule via Azure Graph
        $azureSchedules = AzureGraphService::getAttendeesSchedule($emails, $date);

        // 2. Query all local calendar events where attendees participate on this date
        $localCalEvents = CalendarEvent::whereDate('start_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereDate('end_date', '>=', $date)
                  ->orWhereNull('end_date');
            })
            ->get();

        // 8 Modern distinctive color palettes for attendees
        $palette = [
            ['name' => 'blue',    'hex' => '#2563eb', 'bg' => 'bg-blue-600',    'bg_soft' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-400'],
            ['name' => 'emerald', 'hex' => '#059669', 'bg' => 'bg-emerald-600', 'bg_soft' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-400'],
            ['name' => 'purple',  'hex' => '#7c3aed', 'bg' => 'bg-purple-600',  'bg_soft' => 'bg-purple-50',  'text' => 'text-purple-700',  'border' => 'border-purple-400'],
            ['name' => 'amber',   'hex' => '#d97706', 'bg' => 'bg-amber-600',   'bg_soft' => 'bg-amber-50',   'text' => 'text-amber-700',   'border' => 'border-amber-400'],
            ['name' => 'rose',    'hex' => '#e11d48', 'bg' => 'bg-rose-600',    'bg_soft' => 'bg-rose-50',    'text' => 'text-rose-700',    'border' => 'border-rose-400'],
            ['name' => 'cyan',    'hex' => '#0891b2', 'bg' => 'bg-cyan-600',    'bg_soft' => 'bg-cyan-50',    'text' => 'text-cyan-700',    'border' => 'border-cyan-400'],
            ['name' => 'indigo',  'hex' => '#4f46e5', 'bg' => 'bg-indigo-600',  'bg_soft' => 'bg-indigo-50',  'text' => 'text-indigo-700',  'border' => 'border-indigo-400'],
            ['name' => 'teal',    'hex' => '#0d9488', 'bg' => 'bg-teal-600',    'bg_soft' => 'bg-teal-50',    'text' => 'text-teal-700',    'border' => 'border-teal-400'],
        ];

        $attendeeSchedules = [];
        $index = 0;

        foreach ($attendees as $attUser) {
            $color = $palette[$index % count($palette)];
            $index++;

            $busySlots = [];
            $userEmail = strtolower($attUser->email);

            // Helper to compute timeline percentage (08:00 to 18:00 = 600 min)
            $calcTimelinePct = function(string $start, string $end) {
                $sH = (int) substr($start, 0, 2);
                $sM = (int) substr($start, 3, 2);
                $eH = (int) substr($end, 0, 2);
                $eM = (int) substr($end, 3, 2);

                $startMins = max(0, min(600, ($sH - 8) * 60 + $sM));
                $endMins   = max(0, min(600, ($eH - 8) * 60 + $eM));
                $duration  = max(20, $endMins - $startMins);

                $leftPct  = round(($startMins / 600) * 100, 1);
                $widthPct = round(min(100 - $leftPct, ($duration / 600) * 100), 1);

                return [$leftPct, $widthPct];
            };

            // A. Microsoft Outlook Schedule Items (Live from Microsoft 365)
            if (isset($azureSchedules[$userEmail])) {
                foreach ($azureSchedules[$userEmail] as $mSlot) {
                    if (!empty($mSlot['start']) && !empty($mSlot['end'])) {
                        [$lPct, $wPct] = $calcTimelinePct($mSlot['start'], $mSlot['end']);
                        $status = strtolower($mSlot['status'] ?? 'busy');
                        $isTentative = ($status === 'tentative');
                        $busySlots[] = [
                            'start'        => $mSlot['start'],
                            'end'          => $mSlot['end'],
                            'status'       => $status,
                            'is_tentative' => $isTentative,
                            'title'        => $isTentative ? 'Tentative (Outlook)' : 'Busy (Outlook)',
                            'source'       => 'Microsoft 365 Outlook',
                            'left_pct'     => $lPct,
                            'width_pct'    => $wPct,
                        ];
                    }
                }
            }

            // B. Events synced with Microsoft Calendar
            foreach ($localCalEvents as $cev) {
                $isParticipant = $cev->created_by === $attUser->id ||
                    (is_array($cev->attendees) && (in_array($attUser->id, $cev->attendees) || in_array((string)$attUser->id, $cev->attendees)));

                if ($isParticipant) {
                    $startT = $cev->start_time ? substr($cev->start_time, 0, 5) : ($cev->is_all_day ? '08:00' : '09:00');
                    $endT = $cev->end_time ? substr($cev->end_time, 0, 5) : ($cev->is_all_day ? '18:00' : '10:00');
                    [$lPct, $wPct] = $calcTimelinePct($startT, $endT);

                    $busySlots[] = [
                        'start'     => $startT,
                        'end'       => $endT,
                        'title'     => 'Busy (Outlook)',
                        'source'    => 'Microsoft Calendar',
                        'left_pct'  => $lPct,
                        'width_pct' => $wPct,
                    ];
                }
            }

            // Deduplicate and sort busy slots
            usort($busySlots, fn($a, $b) => strcmp($a['start'], $b['start']));

            $attendeeSchedules[] = [
                'user_id'    => $attUser->id,
                'name'       => $attUser->name,
                'email'      => $attUser->email,
                'initials'   => $this->getInitials($attUser->name),
                'color'      => $color,
                'busy_slots' => $busySlots,
            ];
        }

        $this->attendeeSchedules = $attendeeSchedules;

        // Compute unified team availability slots (merged busy intervals across all attendees)
        $rawSlots = [];
        foreach ($attendeeSchedules as $sched) {
            foreach ($sched['busy_slots'] as $bs) {
                $rawSlots[] = ['start' => $bs['start'], 'end' => $bs['end']];
            }
        }
        usort($rawSlots, fn($a, $b) => strcmp($a['start'], $b['start']));

        $mergedBusy = [];
        foreach ($rawSlots as $slot) {
            if (empty($mergedBusy)) {
                $mergedBusy[] = $slot;
            } else {
                $lastIdx = count($mergedBusy) - 1;
                if ($slot['start'] <= $mergedBusy[$lastIdx]['end']) {
                    if ($slot['end'] > $mergedBusy[$lastIdx]['end']) {
                        $mergedBusy[$lastIdx]['end'] = $slot['end'];
                    }
                } else {
                    $mergedBusy[] = $slot;
                }
            }
        }

        $combinedSlots = [];
        foreach ($mergedBusy as $mb) {
            [$lPct, $wPct] = $calcTimelinePct($mb['start'], $mb['end']);
            $combinedSlots[] = [
                'start'     => $mb['start'],
                'end'       => $mb['end'],
                'title'     => 'Busy',
                'left_pct'  => $lPct,
                'width_pct' => $wPct,
            ];
        }
        $this->combinedBusySlots = $combinedSlots;

        $this->computeSuggestedSlots();
        $this->isLoadingAvailability = false;
    }

    /**
     * Compute mutual free windows between 08:00 AM and 06:00 PM for all attendees.
    /**
     * Check if a candidate start and end time overlaps with any attendee's busy schedule.
     */
    public function hasSchedulingConflict(string $start, string $end): bool
    {
        $s = substr($start, 0, 5);
        $e = substr($end, 0, 5);

        foreach ($this->attendeeSchedules as $sched) {
            foreach ($sched['busy_slots'] as $slot) {
                $bStart = substr($slot['start'], 0, 5);
                $bEnd   = substr($slot['end'], 0, 5);
                if ($s < $bEnd && $e > $bStart) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Compute mutual free windows between 08:00 AM and 06:00 PM for all attendees.
     */
    protected function computeSuggestedSlots(bool $autoResolveConflict = true): void
    {
        // Determine requested duration in minutes (default 60 min)
        $durationMinutes = 60;
        if (!empty($this->newEventStartTime) && !empty($this->newEventEndTime)) {
            try {
                $st = Carbon::createFromFormat('H:i', substr($this->newEventStartTime, 0, 5));
                $et = Carbon::createFromFormat('H:i', substr($this->newEventEndTime, 0, 5));
                $diff = abs($et->diffInMinutes($st));
                if ($diff >= 15 && $diff <= 360) {
                    $durationMinutes = $diff;
                }
            } catch (\Exception $e) {}
        }

        // Test candidate slot windows between 08:00 and 18:00
        $suggested = [];
        $dayStart = Carbon::createFromTime(8, 0);
        $dayEnd   = Carbon::createFromTime(18, 0);

        $curr = clone $dayStart;
        while ($curr->lt($dayEnd)) {
            $slotEnd = (clone $curr)->addMinutes($durationMinutes);
            if ($slotEnd->gt($dayEnd)) {
                break;
            }

            $wStart = $curr->format('H:i');
            $wEnd   = $slotEnd->format('H:i');

            // Strictly check whether ANY attendee has a conflict
            $conflictFound = $this->hasSchedulingConflict($wStart, $wEnd);

            if (!$conflictFound) {
                $startFormatted = $curr->format('h:i A');
                $endFormatted   = $slotEnd->format('h:i A');
                $suggested[] = [
                    'start' => $wStart,
                    'end'   => $wEnd,
                    'label' => "{$startFormatted} – {$endFormatted}",
                ];
            }

            // Step by 30 mins for short durations, 60 mins for >= 60m
            $step = ($durationMinutes <= 45) ? 30 : 60;
            $curr->addMinutes($step);

            if (count($suggested) >= 6) {
                break;
            }
        }

        $this->suggestedSlots = $suggested;

        if ($autoResolveConflict) {
            // Check if current selection has any conflict or is not set
            $currentHasConflict = empty($this->newEventStartTime) ||
                                  empty($this->newEventEndTime) ||
                                  $this->hasSchedulingConflict($this->newEventStartTime, $this->newEventEndTime);

            // If current selection has a conflict OR is empty, automatically choose the best free slot!
            if ($currentHasConflict && !empty($suggested)) {
                // Find preferred slot in standard daytime business hours (>= 09:00), else first
                $preferred = null;
                foreach ($suggested as $s) {
                    if ($s['start'] >= '09:00') {
                        $preferred = $s;
                        break;
                    }
                }
                if (!$preferred) {
                    $preferred = $suggested[0];
                }

                $this->newEventStartTime = $preferred['start'];
                $this->newEventEndTime   = $preferred['end'];
                $this->newEventIsAllDay  = false;
            } elseif ($currentHasConflict && empty($suggested) && empty($this->newEventStartTime)) {
                $this->newEventStartTime = '09:00';
                $this->newEventEndTime   = '10:00';
            }
        }
    }

    /**
     * 1-Click Time Slot selection from the timeline or recommendation pills.
     */
    public function selectTimeSlot(string $start, ?string $end = null): void
    {
        $this->newEventStartTime = substr($start, 0, 5);

        if ($end) {
            $this->newEventEndTime = substr($end, 0, 5);
        } else {
            $duration = 60;
            if (!empty($this->newEventEndTime)) {
                try {
                    $st = Carbon::createFromFormat('H:i', substr($start, 0, 5));
                    $et = Carbon::createFromFormat('H:i', substr($this->newEventEndTime, 0, 5));
                    $diff = abs($et->diffInMinutes($st));
                    if ($diff >= 15 && $diff <= 360) $duration = $diff;
                } catch (\Exception $e) {}
            }
            $st = Carbon::createFromFormat('H:i', substr($start, 0, 5));
            $this->newEventEndTime = (clone $st)->addMinutes($duration)->format('H:i');
        }

        $this->newEventIsAllDay = false;
        $this->computeSuggestedSlots(false);
    }

    /**
     * Adjust duration while keeping start time.
     */
    public function setDuration(int $minutes): void
    {
        $startT = $this->newEventStartTime ? substr($this->newEventStartTime, 0, 5) : '09:00';
        $start = Carbon::createFromFormat('H:i', $startT);
        $end = (clone $start)->addMinutes($minutes);
        $this->newEventEndTime = $end->format('H:i');
        $this->newEventIsAllDay = false;
        $this->computeSuggestedSlots(true);
    }

    public function updatedNewEventStartTime($val): void
    {
        if (empty($val)) return;
        try {
            $s = Carbon::createFromFormat('H:i', substr($val, 0, 5));
            if (!empty($this->newEventEndTime)) {
                $e = Carbon::createFromFormat('H:i', substr($this->newEventEndTime, 0, 5));
                if ($e->lte($s)) {
                    $this->newEventEndTime = (clone $s)->addHour()->format('H:i');
                }
            } else {
                $this->newEventEndTime = (clone $s)->addHour()->format('H:i');
            }
        } catch (\Exception $ex) {}

        $this->computeSuggestedSlots(false);
    }

    public function updatedNewEventEndTime($val): void
    {
        $this->computeSuggestedSlots(false);
    }

    /**
     * Real-time conflict detector for the current start and end times.
     */
    public function getSchedulingConflictsProperty(): array
    {
        if ($this->newEventIsAllDay || empty($this->newEventStartTime) || empty($this->newEventEndTime)) {
            return [];
        }

        $conflicts = [];
        $selStart = substr($this->newEventStartTime, 0, 5);
        $selEnd   = substr($this->newEventEndTime, 0, 5);

        foreach ($this->attendeeSchedules as $sched) {
            foreach ($sched['busy_slots'] as $slot) {
                $bStart = substr($slot['start'], 0, 5);
                $bEnd   = substr($slot['end'], 0, 5);
                if ($selStart < $bEnd && $selEnd > $bStart) {
                    $conflicts[] = [
                        'user_name'     => $sched['name'],
                        'color'         => $sched['color'],
                        'conflict_time' => "{$slot['start']} – {$slot['end']}",
                        'title'         => $slot['title'] ?? 'Busy',
                    ];
                    break;
                }
            }
        }

        return $conflicts;
    }

    protected function getInitials(string $name): string
    {
        $words = explode(' ', trim($name));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= strtoupper($w[0]);
                if (strlen($initials) >= 2) break;
            }
        }
        return $initials ?: 'U';
    }

    public function showEventDetails(array $event): void
    {
        $this->selectedEvent = $event;
        $this->showEventModal = true;
    }

    public function openSubscribeModal(): void
    {
        $this->showSubscribeModal = true;
    }

    // ─── Event Creation & Management ──────────────────────────────────

    public function createEvent(): void
    {
        $user = auth()->user();
        if (!$this->userCanCreateEvent()) {
            $this->dispatch('toast', message: 'Unauthorized: Only PMO Admins and Project Managers can create events.', type: 'error');
            return;
        }

        // If on Step 1, advance to Step 2 first
        if ($this->createEventStep === 1) {
            $this->goToStep2();
            return;
        }

        $this->validate([
            'newEventTitle'       => 'required|string|max:255',
            'newEventType'        => 'required|in:meeting,review,milestone,task,deadline',
            'newEventDate'        => 'required|date',
            'newEventEndDate'     => 'nullable|date|after_or_equal:newEventDate',
            'newEventPriority'    => 'required|in:low,medium,high,critical',
            'newEventLocation'    => 'nullable|string|max:255',
            'newEventMeetingLink' => 'nullable|url|max:500',
            'newEventProject'     => 'nullable|exists:projects,id',
            'newEventAttendees'   => 'nullable|array',
            'newEventStartTime'   => 'nullable|string',
            'newEventEndTime'     => 'nullable|string',
        ]);

        $project = null;
        if ($this->newEventProject) {
            $project = Project::find($this->newEventProject);
        }

        // Strict PM validation: PM can only create events for projects they manage
        if (!$user->isPmoAdmin()) {
            if (!$project || $project->project_manager_id !== $user->id) {
                $this->dispatch('toast', message: 'Unauthorized: You can only schedule events for projects you manage as Project Manager.', type: 'error');
                return;
            }
        }

        $endDate = $this->newEventEndDate ?: $this->newEventDate;

        // Create CalendarEvent
        $calEvent = CalendarEvent::create([
            'project_id'   => $project?->id,
            'created_by'   => $user->id,
            'title'        => trim($this->newEventTitle),
            'description'  => trim($this->newEventDescription),
            'event_type'   => $this->newEventType,
            'start_date'   => $this->newEventDate,
            'end_date'     => $endDate,
            'start_time'   => $this->newEventIsAllDay ? null : $this->newEventStartTime,
            'end_time'     => $this->newEventIsAllDay ? null : $this->newEventEndTime,
            'is_all_day'   => $this->newEventIsAllDay,
            'location'     => trim($this->newEventLocation) ?: null,
            'meeting_link' => trim($this->newEventMeetingLink) ?: null,
            'attendees'    => !empty($this->newEventAttendees) ? array_map('intval', $this->newEventAttendees) : null,
            'priority'     => $this->newEventPriority,
        ]);

        // If event type is Task or Milestone, also create a linked WbsItem so it reflects across Kanban/Gantt
        if ($project && in_array($this->newEventType, ['task', 'milestone'])) {
            $wbsCount = WbsItem::where('project_id', $project->id)->count() + 1;
            $assignedUserId = !empty($this->newEventAttendees) ? (int)$this->newEventAttendees[0] : null;

            $wbsItem = WbsItem::create([
                'project_id'       => $project->id,
                'wbs_code'         => (string)$wbsCount,
                'item_type'        => $this->newEventType === 'milestone' ? ItemType::PHASE : ItemType::TASK,
                'title'            => trim($this->newEventTitle),
                'description'      => trim($this->newEventDescription),
                'start_date'       => $this->newEventDate,
                'end_date'         => $endDate,
                'start_time'       => $this->newEventIsAllDay ? null : $this->newEventStartTime,
                'end_time'         => $this->newEventIsAllDay ? null : $this->newEventEndTime,
                'assigned_user_id' => $assignedUserId,
                'status'           => WbsStatus::NOT_STARTED,
                'priority'         => Priority::from($this->newEventPriority),
                'progress'         => 0,
                'weight'           => 1.0,
                'is_milestone'     => $this->newEventType === 'milestone',
                'created_by'       => $user->id,
            ]);

            $calEvent->update(['wbs_item_id' => $wbsItem->id]);
        }

        // Attempt background sync to Microsoft Graph API if Azure is configured
        $this->syncEventToAzureGraph($calEvent);

        $this->showCreateEventModal = false;
        $this->createEventStep = 1;
        $this->dispatch('toast', message: 'Calendar event created successfully & added to schedule.', type: 'success');
    }

    public function openEditEventModal(int $eventId): void
    {
        $user = auth()->user();
        $event = CalendarEvent::with('project')->find($eventId);
        if (!$event) {
            $this->dispatch('toast', message: 'Event not found.', type: 'error');
            return;
        }

        if (!$event->userCanManage($user)) {
            $this->dispatch('toast', message: 'Unauthorized: Only PMO Admins and the Project Manager of this project can edit this meeting.', type: 'error');
            return;
        }

        $this->editEventId = $event->id;
        $this->editEventProject = $event->project_id;
        $this->editEventTitle = $event->title;
        $this->editEventDescription = $event->description ?? '';
        $this->editEventType = $event->event_type ?? 'meeting';
        $this->editEventDate = $event->start_date ? $event->start_date->format('Y-m-d') : '';
        $this->editEventEndDate = $event->end_date ? $event->end_date->format('Y-m-d') : $this->editEventDate;
        $this->editEventStartTime = $event->start_time ? substr($event->start_time, 0, 5) : '';
        $this->editEventEndTime = $event->end_time ? substr($event->end_time, 0, 5) : '';
        $this->editEventIsAllDay = (bool) $event->is_all_day;
        $this->editEventLocation = $event->location ?? '';
        $this->editEventMeetingLink = $event->meeting_link ?? '';
        $this->editEventPriority = $event->priority ?? 'medium';
        $this->editEventAttendees = is_array($event->attendees) ? array_map('intval', $event->attendees) : [];

        $this->showEventModal = false;
        $this->showEditEventModal = true;
    }

    public function closeEditEventModal(): void
    {
        $this->showEditEventModal = false;
        $this->editEventId = null;
    }

    public function toggleEditAttendee(int $userId): void
    {
        if (in_array($userId, $this->editEventAttendees)) {
            $this->editEventAttendees = array_values(array_diff($this->editEventAttendees, [$userId]));
        } else {
            $this->editEventAttendees[] = $userId;
        }
    }

    public function selectAllEditAttendees(): void
    {
        $this->editEventAttendees = $this->editProjectAttendees->pluck('id')->map(fn($id) => (int)$id)->all();
    }

    public function deselectAllEditAttendees(): void
    {
        $this->editEventAttendees = [];
    }

    public function updatedEditEventProject($value): void
    {
        if ($value) {
            $project = Project::with(['projectManager', 'members'])->find($value);
            if ($project) {
                $validUserIds = collect([$project->project_manager_id])
                    ->merge($project->members->pluck('id'))
                    ->filter()
                    ->map(fn($id) => (int)$id)
                    ->all();
                $this->editEventAttendees = array_values(array_intersect($this->editEventAttendees, $validUserIds));
                if ($project->project_manager_id && !in_array((int)$project->project_manager_id, $this->editEventAttendees)) {
                    $this->editEventAttendees[] = (int)$project->project_manager_id;
                }
            }
        }
    }

    public function getEditProjectAttendeesProperty()
    {
        if (!$this->editEventProject) {
            return User::where('is_active', true)->orderBy('name')->get()->map(function($u) {
                $u->project_role_label = 'Corporate User';
                return $u;
            });
        }

        $project = Project::with(['projectManager', 'members', 'creator'])->find($this->editEventProject);
        if (!$project) return collect();

        $users = collect();

        // 1. Project Manager / Lead
        if ($project->projectManager && $project->projectManager->is_active) {
            $pm = $project->projectManager;
            $pm->project_role_label = 'Project Manager / Lead';
            $users->push($pm);
        }

        // 2. Project Members with Roles
        foreach ($project->members as $member) {
            if ($member->is_active && !$users->contains('id', $member->id)) {
                $roleLabel = match($member->pivot->role ?? '') {
                    'lead' => 'Project Lead',
                    'sponsor' => 'Project Sponsor',
                    'owner' => 'Project Owner',
                    'steering_committee' => 'Steering Committee',
                    'observer' => 'Observer',
                    default => 'Team Member',
                };
                $member->project_role_label = $roleLabel;
                $users->push($member);
            }
        }

        // 3. WBS Task Assignees
        $assignedIds = WbsItem::where('project_id', $project->id)
            ->whereNotNull('assigned_user_id')
            ->pluck('assigned_user_id')
            ->unique();
        if ($assignedIds->isNotEmpty()) {
            $assignees = User::whereIn('id', $assignedIds)->where('is_active', true)->get();
            foreach ($assignees as $au) {
                if (!$users->contains('id', $au->id)) {
                    $au->project_role_label = 'Task Assignee';
                    $users->push($au);
                }
            }
        }

        // 4. Project Creator
        if ($project->creator && $project->creator->is_active && !$users->contains('id', $project->creator->id)) {
            $creator = $project->creator;
            $creator->project_role_label = 'Project Creator';
            $users->push($creator);
        }

        return $users->sortBy('name')->values();
    }

    public function updateEvent(): void
    {
        $user = auth()->user();
        if (!$this->editEventId) return;

        $event = CalendarEvent::with('project')->find($this->editEventId);
        if (!$event || !$event->userCanManage($user)) {
            $this->dispatch('toast', message: 'Unauthorized: Only PMO Admins and the Project Manager of this project can edit this meeting.', type: 'error');
            return;
        }

        // Normalize meeting link if missing scheme
        if (!empty($this->editEventMeetingLink)) {
            $link = trim($this->editEventMeetingLink);
            if (!preg_match('#^https?://#i', $link)) {
                $link = 'https://' . $link;
            }
            $this->editEventMeetingLink = $link;
        }

        $this->validate([
            'editEventTitle'       => 'required|string|max:255',
            'editEventType'        => 'required|in:meeting,review,milestone,task,deadline',
            'editEventDate'        => 'required|date',
            'editEventEndDate'     => 'nullable|date|after_or_equal:editEventDate',
            'editEventPriority'    => 'required|in:low,medium,high,critical',
            'editEventLocation'    => 'nullable|string|max:255',
            'editEventMeetingLink' => 'nullable|url|max:500',
            'editEventProject'     => 'nullable|exists:projects,id',
            'editEventAttendees'   => 'nullable|array',
            'editEventStartTime'   => 'nullable|string',
            'editEventEndTime'     => 'nullable|string',
        ]);

        $endDate = $this->editEventEndDate ?: $this->editEventDate;

        // Auto-calculate end time if not all-day and end time is missing
        if (!$this->editEventIsAllDay && $this->editEventStartTime && !$this->editEventEndTime) {
            $this->editEventEndTime = \Carbon\Carbon::parse($this->editEventStartTime)->addHour()->format('H:i');
        }

        $event->update([
            'project_id'   => $this->editEventProject,
            'title'        => trim($this->editEventTitle),
            'description'  => trim($this->editEventDescription),
            'event_type'   => $this->editEventType,
            'start_date'   => $this->editEventDate,
            'end_date'     => $endDate,
            'start_time'   => $this->editEventIsAllDay ? null : $this->editEventStartTime,
            'end_time'     => $this->editEventIsAllDay ? null : $this->editEventEndTime,
            'is_all_day'   => $this->editEventIsAllDay,
            'location'     => trim($this->editEventLocation) ?: null,
            'meeting_link' => trim($this->editEventMeetingLink) ?: null,
            'attendees'    => !empty($this->editEventAttendees) ? array_map('intval', $this->editEventAttendees) : null,
            'priority'     => $this->editEventPriority,
        ]);

        if ($event->wbs_item_id) {
            $wbs = WbsItem::find($event->wbs_item_id);
            if ($wbs) {
                $assignedUserId = !empty($this->editEventAttendees) ? (int)$this->editEventAttendees[0] : null;
                $wbs->update([
                    'title'            => trim($this->editEventTitle),
                    'description'      => trim($this->editEventDescription),
                    'start_date'       => $this->editEventDate,
                    'end_date'         => $endDate,
                    'start_time'       => $this->editEventIsAllDay ? null : $this->editEventStartTime,
                    'end_time'         => $this->editEventIsAllDay ? null : $this->editEventEndTime,
                    'assigned_user_id' => $assignedUserId,
                    'priority'         => Priority::from($this->editEventPriority),
                ]);
            }
        }

        $this->syncEventToAzureGraph($event);

        $this->showEditEventModal = false;
        $this->editEventId = null;
        $this->dispatch('toast', message: 'Meeting updated successfully & schedule refreshed.', type: 'success');
    }

    public function deleteEvent(string $type, int $id): void
    {
        $user = auth()->user();
        if (!$user) return;

        if ($type === 'calendar_event') {
            $event = CalendarEvent::find($id);
            if (!$event) return;

            if (!$event->userCanManage($user)) {
                $this->dispatch('toast', message: 'Unauthorized to delete this calendar event.', type: 'error');
                return;
            }

            if ($event->wbs_item_id) {
                WbsItem::where('id', $event->wbs_item_id)->delete();
            }
            $event->delete();

            $this->showEventModal = false;
            $this->dispatch('toast', message: 'Calendar event removed successfully.', type: 'success');
        } elseif ($type === 'wbs_task') {
            $task = WbsItem::with('project')->find($id);
            if (!$task) return;

            $canManage = $user->isPmoAdmin() || ($task->project && $task->project->project_manager_id === $user->id);
            if (!$canManage) {
                $this->dispatch('toast', message: 'Unauthorized to delete project task.', type: 'error');
                return;
            }

            $task->delete();
            $this->showEventModal = false;
            $this->dispatch('toast', message: 'Task removed from calendar schedule.', type: 'success');
        }
    }

    /**
     * Trigger on-demand sync of a CalendarEvent to Microsoft 365 Outlook.
     */
    public function syncToOutlook(int $eventId): void
    {
        $calEvent = CalendarEvent::with(['project', 'creator'])->find($eventId);
        if (!$calEvent) {
            $this->dispatch('toast', message: 'Event not found.', type: 'error');
            return;
        }

        $result = $this->syncEventToAzureGraph($calEvent);

        if ($result && $result['success']) {
            $this->dispatch('toast', message: 'Synced to Microsoft 365 Outlook successfully!', type: 'success');
            if ($this->selectedEvent && $this->selectedEvent['id'] == $eventId) {
                $this->selectedEvent['synced_to_microsoft_at'] = now()->format('M d, Y h:i A');
            }
        } else {
            $msg = $result['message'] ?? 'Could not sync via Microsoft Graph. Use the 1-Click "Add to Outlook" button instead.';
            $this->dispatch('toast', message: $msg, type: 'info');
        }
    }

    protected function syncEventToAzureGraph(CalendarEvent $event): ?array
    {
        $user = auth()->user();
        if (!$user) return null;

        // Collect all attendee emails
        $attendeeEmails = [];
        if (!empty($event->attendees)) {
            $attendeeEmails = User::whereIn('id', $event->attendees)->pluck('email')->filter()->all();
        }

        // Determine organizer / target email: prefer user email, or PM email, or first attendee email
        $targetEmail = $user->email;
        if (!empty($event->project_id)) {
            $project = $event->project ?: Project::find($event->project_id);
            if ($project && $project->projectManager && !empty($project->projectManager->email)) {
                if ($this->userHasAzureAccount($project->projectManager)) {
                    $targetEmail = $project->projectManager->email;
                }
            }
        }
        if (empty($targetEmail) && !empty($attendeeEmails)) {
            $targetEmail = $attendeeEmails[0];
        }

        $eventData = [
            'title'           => $event->title . ($event->project ? ' [' . $event->project->code . ']' : ''),
            'description'     => $event->description,
            'start_date'      => $event->start_date->format('Y-m-d'),
            'end_date'        => $event->end_date ? $event->end_date->format('Y-m-d') : $event->start_date->format('Y-m-d'),
            'start_time'      => $event->start_time ?: '09:00:00',
            'end_time'        => $event->end_time ?: '10:00:00',
            'is_all_day'      => $event->is_all_day,
            'location'        => $event->location,
            'meeting_link'    => $event->meeting_link,
            'attendee_emails' => $attendeeEmails,
        ];

        // 1. Attempt primary sync via Microsoft Graph API for organizer (Exchange will distribute to all attendees)
        $result = AzureGraphService::createCalendarEvent($targetEmail, $eventData);

        // 2. Also attempt direct calendar event creation for each attendee if separate mailboxes
        if (!empty($attendeeEmails)) {
            foreach ($attendeeEmails as $attEmail) {
                if ($attEmail !== $targetEmail) {
                    $attResult = AzureGraphService::createCalendarEvent($attEmail, $eventData);
                    if ($attResult['success']) {
                        $result['success'] = true;
                    }
                }
            }
        }

        if ($result && $result['success'] && !empty($result['microsoft_event_id'])) {
            $event->update([
                'microsoft_event_id'    => $result['microsoft_event_id'],
                'synced_to_microsoft_at' => now(),
            ]);
        }

        return $result;
    }

    // ─── Render View & Data Aggregation ───────────────────────────────

    public function render()
    {
        $user = auth()->user();
        $isPmoAdmin = $user && $user->isPmoAdmin();
        $isPm = $user && $user->isProjectManager();
        $canCreate = $this->userCanCreateEvent();

        // 1. Projects & Subsidiaries for Filter Controls
        $projectsList = $this->getVisibleProjects();
        $creatableProjects = $this->getCreatableProjects();

        if ($isPmoAdmin) {
            $subsidiaries = Subsidiary::orderBy('name')->get();
        } else {
            $subIds = $projectsList->pluck('subsidiary_id')->filter()->unique();
            $subsidiaries = Subsidiary::whereIn('id', $subIds)->orderBy('name')->get();
            if ($subsidiaries->isEmpty()) {
                $subsidiaries = Subsidiary::orderBy('name')->get();
            }
        }

        $assignableUsers = User::where('is_active', true)->orderBy('name')->get(['id', 'name', 'email']);

        // 2. Query Calendar Events (Meetings, Reviews, Custom Milestones)
        $calEventQuery = CalendarEvent::with(['project.subsidiary', 'creator']);

        // Subsidiary filter
        if ($this->subsidiaryFilter !== 'all') {
            $calEventQuery->whereHas('project', fn($q) => $q->where('subsidiary_id', $this->subsidiaryFilter));
        }

        // Project filter
        if ($this->projectFilter !== 'all') {
            $calEventQuery->where('project_id', $this->projectFilter);
        }

        // Category filter
        if ($this->categoryFilter !== 'all') {
            $calEventQuery->where('event_type', $this->categoryFilter);
        }

        // Search query
        if (!empty(trim($this->search))) {
            $s = '%' . trim($this->search) . '%';
            $calEventQuery->where(function($q) use ($s) {
                $q->where('title', 'like', $s)
                  ->orWhere('description', 'like', $s)
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', $s)->orWhere('code', 'like', $s));
            });
        }

        // Scope filter
        if ($this->scopeFilter === 'my_events') {
            $calEventQuery->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereJsonContains('attendees', (string)$user->id)
                  ->orWhereJsonContains('attendees', (int)$user->id)
                  ->orWhereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id));
            });
        } else {
            if (!$isPmoAdmin) {
                $calEventQuery->where(function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhereJsonContains('attendees', (string)$user->id)
                      ->orWhereJsonContains('attendees', (int)$user->id)
                      ->orWhereHas('project', function($pq) use ($user) {
                          $pq->where('project_manager_id', $user->id)
                             ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                      });
                });
            }
        }

        $allCalendarEvents = $calEventQuery->get();

        // 3. Query WBS Items (Tasks & Milestones)
        $wbsQuery = WbsItem::with(['project.subsidiary', 'assignedUser'])
            ->where(function($q) {
                $q->whereNotNull('start_date')->orWhereNotNull('end_date');
            });

        if ($this->subsidiaryFilter !== 'all') {
            $wbsQuery->whereHas('project', fn($q) => $q->where('subsidiary_id', $this->subsidiaryFilter));
        }

        if ($this->projectFilter !== 'all') {
            $wbsQuery->where('project_id', $this->projectFilter);
        }

        if ($this->categoryFilter === 'milestone') {
            $wbsQuery->where('is_milestone', true);
        } elseif ($this->categoryFilter === 'task') {
            $wbsQuery->where('is_milestone', false);
        } elseif (in_array($this->categoryFilter, ['meeting', 'review'])) {
            // WBS has no meetings/reviews
            $wbsQuery->whereRaw('1 = 0');
        }

        if (!empty(trim($this->search))) {
            $s = '%' . trim($this->search) . '%';
            $wbsQuery->where(function($q) use ($s) {
                $q->where('title', 'like', $s)
                  ->orWhere('wbs_code', 'like', $s)
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', $s));
            });
        }

        if ($this->scopeFilter === 'my_events') {
            $wbsQuery->where('assigned_user_id', $user->id);
        } else {
            if (!$isPmoAdmin) {
                $wbsQuery->where(function($q) use ($user) {
                    $q->where('assigned_user_id', $user->id)
                      ->orWhereHas('project', function($pq) use ($user) {
                          $pq->where('project_manager_id', $user->id)
                             ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                      });
                });
            }
        }

        $allWbsItems = $wbsQuery->get();

        // 4. Query Project Deadlines
        $projectsWithDeadlines = collect();
        if ($this->categoryFilter === 'all' || $this->categoryFilter === 'deadline') {
            $projectDeadlineQuery = Project::whereNotNull('deadline');
            if ($this->subsidiaryFilter !== 'all') {
                $projectDeadlineQuery->where('subsidiary_id', $this->subsidiaryFilter);
            }
            if ($this->projectFilter !== 'all') {
                $projectDeadlineQuery->where('id', $this->projectFilter);
            }
            if ($this->scopeFilter === 'my_events') {
                $projectDeadlineQuery->where('project_manager_id', $user->id);
            } else {
                if (!$isPmoAdmin) {
                    $projectDeadlineQuery->where(function($q) use ($user) {
                        $q->where('project_manager_id', $user->id)
                          ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                    });
                }
            }
            $projectsWithDeadlines = $projectDeadlineQuery->get();
        }

        // 5. Structure All Events by Date & Compute Unified Event Collection
        $eventsByDate = [];
        $allFlatEvents = [];

        $currentDate = Carbon::create($this->year, $this->month, 1);
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        // A. Process CalendarEvents
        foreach ($allCalendarEvents as $ce) {
            $sDate = $ce->start_date->copy();
            $eDate = $ce->end_date ? $ce->end_date->copy() : $sDate;

            $attUsers = $ce->attendeeUsers;
            $attNames = $attUsers->pluck('name')->all();

            $eventItem = [
                'raw_id'             => $ce->id,
                'id'                 => 'cal_' . $ce->id,
                'source_type'        => 'calendar_event',
                'event_type'         => $ce->event_type,
                'type'               => $ce->event_type,
                'title'              => $ce->title,
                'description'        => $ce->description,
                'project_id'         => $ce->project_id,
                'project'            => $ce->project->name ?? 'Organization Event',
                'project_code'       => $ce->project->code ?? 'CORP',
                'subsidiary'         => $ce->project?->subsidiary?->name ?? 'George Steuart',
                'start_date'         => $sDate->format('Y-m-d'),
                'end_date'           => $eDate->format('Y-m-d'),
                'start_date_formatted' => $sDate->format('M d, Y'),
                'end_date_formatted'   => $eDate->format('M d, Y'),
                'start_time'         => $ce->start_time,
                'end_time'           => $ce->end_time,
                'time_range'         => $ce->time_range,
                'is_all_day'         => $ce->is_all_day,
                'location'           => $ce->location,
                'meeting_link'       => $ce->meeting_link,
                'priority'           => $ce->priority,
                'theme_color'        => $ce->theme_color,
                'creator_name'       => $ce->creator->name ?? 'PMO Admin',
                'attendees_count'    => count($attNames),
                'attendee_names'     => implode(', ', $attNames),
                'attendee_list'      => $attUsers->toArray(),
                'outlook_web_url'    => $ce->outlook_web_url,
                'is_synced_to_ms'    => !empty($ce->microsoft_event_id),
                'synced_to_microsoft_at' => $ce->synced_to_microsoft_at ? $ce->synced_to_microsoft_at->format('M d, Y h:i A') : null,
                'can_manage'         => $ce->userCanManage($user),
            ];

            // Add to dates in range
            $curr = $sDate->copy();
            $maxDays = min(14, $sDate->diffInDays($eDate) + 1);
            $dayCount = 0;
            while ($curr->lte($eDate) && $dayCount < $maxDays) {
                $dKey = $curr->format('Y-m-d');
                $dItem = $eventItem;
                $dItem['date'] = $dKey;
                $eventsByDate[$dKey][] = $dItem;
                $curr->addDay();
                $dayCount++;
            }

            $allFlatEvents[] = $eventItem;
        }

        // B. Process WbsItems
        foreach ($allWbsItems as $wbs) {
            $sDate = $wbs->start_date ? $wbs->start_date->copy() : ($wbs->end_date ? $wbs->end_date->copy() : null);
            $eDate = $wbs->end_date ? $wbs->end_date->copy() : $sDate;
            if (!$sDate || !$eDate) continue;

            // Safely parse time fields - handle H:i:s or H:i strings
            try {
                $startTimeStr = (!empty($wbs->start_time) && $wbs->start_time !== '??:??:??')
                    ? Carbon::createFromFormat('H:i:s', substr($wbs->start_time, 0, 8))->format('h:i A')
                    : null;
            } catch (\Exception $e) {
                $startTimeStr = null;
            }
            try {
                $endTimeStr = (!empty($wbs->end_time) && $wbs->end_time !== '??:??:??')
                    ? Carbon::createFromFormat('H:i:s', substr($wbs->end_time, 0, 8))->format('h:i A')
                    : null;
            } catch (\Exception $e) {
                $endTimeStr = null;
            }
            $timeRange = ($startTimeStr && $endTimeStr)
                ? "{$startTimeStr} – {$endTimeStr}"
                : ($startTimeStr ? "Starts {$startTimeStr}" : ($endTimeStr ? "Due {$endTimeStr}" : 'All Day Deliverable'));

            $isMilestone = (bool)$wbs->is_milestone;
            $color = match(true) {
                $isMilestone => 'purple',
                $wbs->status === WbsStatus::COMPLETED => 'emerald',
                $wbs->status === WbsStatus::IN_PROGRESS => 'amber',
                $wbs->status === WbsStatus::BLOCKED => 'rose',
                $eDate->isPast() && $wbs->status !== WbsStatus::COMPLETED => 'rose',
                default => 'slate',
            };

            $eventItem = [
                'raw_id'             => $wbs->id,
                'id'                 => 'wbs_' . $wbs->id,
                'source_type'        => 'wbs_task',
                'event_type'         => $isMilestone ? 'milestone' : 'task',
                'type'               => $isMilestone ? 'milestone' : 'task',
                'title'              => $wbs->title,
                'description'        => $wbs->description,
                'project_id'         => $wbs->project_id,
                'project'            => $wbs->project->name ?? 'Project',
                'project_code'       => $wbs->project->code ?? 'GS',
                'subsidiary'         => $wbs->project?->subsidiary?->name ?? 'George Steuart',
                'start_date'         => $sDate->format('Y-m-d'),
                'end_date'           => $eDate->format('Y-m-d'),
                'start_date_formatted' => $sDate->format('M d, Y'),
                'end_date_formatted'   => $eDate->format('M d, Y'),
                'start_time'         => $wbs->start_time,
                'end_time'           => $wbs->end_time,
                'time_range'         => $timeRange,
                'is_all_day'         => empty($wbs->start_time) && empty($wbs->end_time),
                'location'           => 'Project Workspace',
                'meeting_link'       => null,
                'priority'           => $wbs->priority?->value ?? 'medium',
                'theme_color'        => $color,
                'creator_name'       => $wbs->project?->projectManager?->name ?? 'Project Manager',
                'attendees_count'    => $wbs->assignedUser ? 1 : 0,
                'attendee_names'     => $wbs->assignedUser->name ?? 'Unassigned',
                'attendee_list'      => $wbs->assignedUser ? [$wbs->assignedUser->toArray()] : [],
                'status'             => $wbs->status?->value ?? 'not_started',
                'status_label'       => $wbs->status ? $wbs->status->label() : 'Not Started',
                'progress'           => $wbs->progress ?? 0,
                'outlook_web_url'    => $this->generateOutlookUrlForTask($wbs),
                'is_synced_to_ms'    => false,
                'synced_to_microsoft_at' => null,
                'can_manage'         => $isPmoAdmin || ($wbs->project && $wbs->project->project_manager_id === $user->id),
            ];

            $curr = $sDate->copy();
            $maxDays = min(14, $sDate->diffInDays($eDate) + 1);
            $dayCount = 0;
            while ($curr->lte($eDate) && $dayCount < $maxDays) {
                $dKey = $curr->format('Y-m-d');
                $dItem = $eventItem;
                $dItem['date'] = $dKey;
                $eventsByDate[$dKey][] = $dItem;
                $curr->addDay();
                $dayCount++;
            }

            $allFlatEvents[] = $eventItem;
        }

        // C. Process Project Deadlines
        foreach ($projectsWithDeadlines as $pDead) {
            $dKey = $pDead->deadline->format('Y-m-d');
            $eventItem = [
                'raw_id'             => $pDead->id,
                'id'                 => 'deadline_' . $pDead->id,
                'source_type'        => 'project_deadline',
                'event_type'         => 'deadline',
                'type'               => 'deadline',
                'title'              => 'Project Target Deadline: ' . $pDead->name,
                'description'        => $pDead->description,
                'project_id'         => $pDead->id,
                'project'            => $pDead->name,
                'project_code'       => $pDead->code,
                'subsidiary'         => $pDead->subsidiary->name ?? '',
                'start_date'         => $dKey,
                'end_date'           => $dKey,
                'start_date_formatted' => $pDead->deadline->format('M d, Y'),
                'end_date_formatted'   => $pDead->deadline->format('M d, Y'),
                'start_time'         => null,
                'end_time'           => null,
                'time_range'         => 'Target Completion Date',
                'is_all_day'         => true,
                'location'           => 'Executive Project Milestone',
                'meeting_link'       => null,
                'priority'           => 'critical',
                'theme_color'        => 'rose',
                'creator_name'       => $pDead->projectManager->name ?? 'Executive Sponsor',
                'attendees_count'    => 1,
                'attendee_names'     => $pDead->projectManager->name ?? 'Lead PM',
                'attendee_list'      => [],
                'status'             => $pDead->status?->value ?? 'in_progress',
                'status_label'       => $pDead->status ? $pDead->status->label() : 'In Progress',
                'progress'           => $pDead->overall_progress ?? 0,
                'outlook_web_url'    => $this->generateOutlookUrlForDeadline($pDead),
                'is_synced_to_ms'    => false,
                'synced_to_microsoft_at' => null,
                'can_manage'         => false,
                'date'               => $dKey,
            ];

            $eventsByDate[$dKey][] = $eventItem;
            $allFlatEvents[] = $eventItem;
        }

        // 6. Selected Day Schedule Items (Sorted by Start Time)
        $selectedDateObj = Carbon::parse($this->selectedDate ?: now()->format('Y-m-d'));
        $selectedDayEvents = $eventsByDate[$this->selectedDate] ?? [];
        usort($selectedDayEvents, function($a, $b) {
            $timeA = $a['start_time'] ?? '99:99';
            $timeB = $b['start_time'] ?? '99:99';
            return strcmp($timeA, $timeB);
        });

        // 7. Month Calendar Grid Weeks & Days
        $startOfWeekDay = $startOfMonth->dayOfWeek; // 0 = Sunday
        $daysInMonth = $currentDate->daysInMonth;
        $weeks = [];
        $currentWeek = [];

        // Previous month padding days
        $prevMonthLastDay = $startOfMonth->copy()->subDay();
        for ($i = $startOfWeekDay - 1; $i >= 0; $i--) {
            $dayDate = $prevMonthLastDay->copy()->subDays($i);
            $dStr = $dayDate->format('Y-m-d');
            $currentWeek[] = [
                'date'           => $dStr,
                'dayNumber'      => (int)$dayDate->format('d'),
                'isCurrentMonth' => false,
                'isToday'        => $dayDate->isToday(),
                'isSelected'     => $dStr === $this->selectedDate,
                'events'         => $eventsByDate[$dStr] ?? [],
            ];
        }

        // Current month days
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dayDate = Carbon::create($this->year, $this->month, $d);
            $dStr = $dayDate->format('Y-m-d');
            $currentWeek[] = [
                'date'           => $dStr,
                'dayNumber'      => $d,
                'isCurrentMonth' => true,
                'isToday'        => $dayDate->isToday(),
                'isSelected'     => $dStr === $this->selectedDate,
                'events'         => $eventsByDate[$dStr] ?? [],
            ];

            if (count($currentWeek) === 7) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }
        }

        // Next month padding days
        if (count($currentWeek) > 0) {
            $nextMonthDay = 1;
            while (count($currentWeek) < 7) {
                $dayDate = $endOfMonth->copy()->addDays($nextMonthDay);
                $dStr = $dayDate->format('Y-m-d');
                $currentWeek[] = [
                    'date'           => $dStr,
                    'dayNumber'      => $nextMonthDay,
                    'isCurrentMonth' => false,
                    'isToday'        => $dayDate->isToday(),
                    'isSelected'     => $dStr === $this->selectedDate,
                    'events'         => $eventsByDate[$dStr] ?? [],
                ];
                $nextMonthDay++;
            }
            $weeks[] = $currentWeek;
        }

        // 8. Week View Schedule (7 days of selected week)
        $weekStart = $selectedDateObj->copy()->startOfWeek(Carbon::SUNDAY);
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $wDay = $weekStart->copy()->addDays($i);
            $wStr = $wDay->format('Y-m-d');
            $weekDays[] = [
                'date'       => $wStr,
                'dayName'    => $wDay->format('D'),
                'dayNumber'  => (int)$wDay->format('d'),
                'isToday'    => $wDay->isToday(),
                'isSelected' => $wStr === $this->selectedDate,
                'events'     => $eventsByDate[$wStr] ?? [],
            ];
        }

        // 9. Chronological List View (Upcoming Events in Month)
        usort($allFlatEvents, fn($a, $b) => strcmp($a['start_date'], $b['start_date']));
        $groupedListEvents = [];
        foreach ($allFlatEvents as $evt) {
            if ($evt['start_date'] >= $startOfMonth->format('Y-m-d') && $evt['start_date'] <= $endOfMonth->format('Y-m-d')) {
                $groupedListEvents[$evt['start_date']][] = $evt;
            }
        }

        // 10. Metric Counters
        $totalEventsCount = count($allFlatEvents);
        $meetingsCount = count(array_filter($allFlatEvents, fn($e) => $e['event_type'] === 'meeting' || $e['event_type'] === 'review'));
        $milestonesCount = count(array_filter($allFlatEvents, fn($e) => $e['event_type'] === 'milestone' || $e['event_type'] === 'deadline'));
        $tasksCount = count(array_filter($allFlatEvents, fn($e) => $e['event_type'] === 'task'));

        // Generate Outlook Feed URL with security signature
        $feedToken = sha1($user->id . $user->email . config('app.key'));
        $feedUrl = url('/calendar/feed.ics?user_id=' . $user->id . '&token=' . $feedToken);

        return view('livewire.calendar-view', compact(
            'weeks',
            'weekDays',
            'currentDate',
            'selectedDateObj',
            'selectedDayEvents',
            'groupedListEvents',
            'eventsByDate',
            'subsidiaries',
            'projectsList',
            'creatableProjects',
            'assignableUsers',
            'isPmoAdmin',
            'isPm',
            'canCreate',
            'totalEventsCount',
            'meetingsCount',
            'milestonesCount',
            'tasksCount',
            'feedUrl'
        ))->layout('layouts.app', ['title' => 'Corporate Calendar — GS NexusPM']);
    }

    protected function generateOutlookUrlForTask(WbsItem $task): string
    {
        $startDateStr = $task->start_date ? $task->start_date->format('Y-m-d') : now()->format('Y-m-d');
        $endDateStr = $task->end_date ? $task->end_date->format('Y-m-d') : $startDateStr;
        $startTime = $task->start_time ?: '09:00:00';
        $endTime = $task->end_time ?: '10:00:00';

        $startIso = Carbon::parse("{$startDateStr} {$startTime}")->format('Y-m-d\TH:i:s');
        $endIso = Carbon::parse("{$endDateStr} {$endTime}")->format('Y-m-d\TH:i:s');

        $params = [
            'path'     => '/calendar/action/compose',
            'rru'      => 'addevent',
            'subject'  => $task->title . ($task->project ? ' [' . $task->project->code . ']' : ''),
            'body'     => ($task->description ? $task->description . "\n\n" : '') . "Project: " . ($task->project->name ?? 'GS'),
            'startdt'  => $startIso,
            'enddt'    => $endIso,
            'location' => 'GS NexusPM Workspace',
            'allday'   => empty($task->start_time) && empty($task->end_time) ? 'true' : 'false',
        ];

        if ($task->assignedUser && !empty($task->assignedUser->email)) {
            $params['to'] = $task->assignedUser->email;
        }

        $query = http_build_query($params);

        return "https://outlook.office.com/calendar/0/deeplink/compose?" . $query;
    }

    protected function generateOutlookUrlForDeadline(Project $project): string
    {
        $dStr = $project->deadline ? $project->deadline->format('Y-m-d') : now()->format('Y-m-d');
        $startIso = Carbon::parse("{$dStr} 09:00:00")->format('Y-m-d\TH:i:s');
        $endIso = Carbon::parse("{$dStr} 17:00:00")->format('Y-m-d\TH:i:s');

        $query = http_build_query([
            'path'     => '/calendar/action/compose',
            'rru'      => 'addevent',
            'subject'  => 'Project Target Deadline: ' . $project->name . ' [' . $project->code . ']',
            'body'     => "Final delivery deadline for project {$project->name} ({$project->code}).\n\n" . ($project->description ?? ''),
            'startdt'  => $startIso,
            'enddt'    => $endIso,
            'location' => 'George Steuart Executive Board',
            'allday'   => 'true',
        ]);

        return "https://outlook.office.com/calendar/0/deeplink/compose?" . $query;
    }
}
