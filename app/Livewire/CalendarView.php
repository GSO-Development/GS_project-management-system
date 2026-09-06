<?php

namespace App\Livewire;

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\ProgressCalculationService;
use Carbon\Carbon;
use Livewire\Component;

class CalendarView extends Component
{
    // Scopes & Filters
    public string $scopeFilter = 'all'; // 'all' or 'my_tasks'
    public string $subsidiaryFilter = 'all';
    public string $projectFilter = 'all';
    public string $statusFilter = 'all';
    public string $priorityFilter = 'all';
    public string $typeFilter = 'all'; // 'all', 'task', 'milestone', 'deadline'
    public string $search = '';

    // View Modes: 'calendar' (Month Grid), 'day' (Day Schedule), 'list' (Timeline List)
    public string $viewMode = 'calendar';

    // Calendar state
    public int $year;
    public int $month;
    public string $selectedDate = '';

    // Modals state
    public bool $showEventModal = false;
    public ?array $selectedEvent = null;

    public bool $showCreateEventModal = false;
    public ?int $newEventProject = null;
    public string $newEventTitle = '';
    public string $newEventType = 'task'; // 'task' or 'milestone'
    public ?string $newEventDate = null;
    public ?string $newEventStartTime = null;
    public ?string $newEventEndTime = null;
    public string $newEventPriority = 'medium';
    public ?int $newEventAssignedUser = null;

    public function mount(?string $date = null)
    {
        $today = now();
        $this->year = (int) $today->format('Y');
        $this->month = (int) $today->format('m');
        $this->selectedDate = $date ?: $today->format('Y-m-d');
        $this->newEventDate = $this->selectedDate;

        $user = auth()->user();
        $isPmoAdmin = $user && $user->isPmoAdmin();
        $isProjectManager = $user && $user->isProjectManager();

        // If regular user (neither PMO Admin nor PM), lock to 'my_tasks'
        if (!$isPmoAdmin && !$isProjectManager) {
            $this->scopeFilter = 'my_tasks';
        } else {
            $this->scopeFilter = 'all';
        }

        // Initialize default project for creation modal
        $firstProj = $this->getAvailableProjects()->first();
        if ($firstProj) {
            $this->newEventProject = $firstProj->id;
        }
    }

    public function setScope(string $scope): void
    {
        $user = auth()->user();
        $isPmoAdmin = $user && $user->isPmoAdmin();
        $isProjectManager = $user && $user->isProjectManager();

        if (!$isPmoAdmin && !$isProjectManager) {
            $this->scopeFilter = 'my_tasks';
            return;
        }

        if (in_array($scope, ['all', 'my_tasks'])) {
            $this->scopeFilter = $scope;
        }
    }

    public function setViewMode(string $mode): void
    {
        if (in_array($mode, ['calendar', 'day', 'list'])) {
            $this->viewMode = $mode;
        }
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $parsed = Carbon::parse($date);
        
        // If selected date is in another month, update calendar year & month
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

    public function today(): void
    {
        $today = now();
        $this->year = (int) $today->format('Y');
        $this->month = (int) $today->format('m');
        $this->selectedDate = $today->format('Y-m-d');
    }

    public function prevDay(): void
    {
        $current = Carbon::parse($this->selectedDate ?: now()->format('Y-m-d'))->subDay();
        $this->selectDate($current->format('Y-m-d'));
    }

    public function nextDay(): void
    {
        $current = Carbon::parse($this->selectedDate ?: now()->format('Y-m-d'))->addDay();
        $this->selectDate($current->format('Y-m-d'));
    }

    public function clearFilters(): void
    {
        $this->reset([
            'subsidiaryFilter',
            'projectFilter',
            'statusFilter',
            'priorityFilter',
            'typeFilter',
            'search',
        ]);
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $this->statusFilter === $status ? 'all' : $status;
    }

    public function showEventDetails(array $event): void
    {
        $this->selectedEvent = $event;
        $this->showEventModal = true;
    }

    public function openCreateEventModal(?string $date = null): void
    {
        $this->reset(['newEventTitle', 'newEventType', 'newEventPriority', 'newEventStartTime', 'newEventEndTime', 'newEventAssignedUser']);
        $this->newEventDate = $date ?: ($this->selectedDate ?: now()->format('Y-m-d'));

        $firstProj = $this->getAvailableProjects()->first();
        if ($firstProj) {
            $this->newEventProject = $firstProj->id;
        }

        $this->showCreateEventModal = true;
    }

    public function createEvent(): void
    {
        $user = auth()->user();
        if (!$user->isPmoAdmin() && !$user->isProjectManager()) {
            $this->dispatch('toast', message: 'Unauthorized to schedule events.', type: 'error');
            return;
        }

        $this->validate([
            'newEventProject' => 'required|exists:projects,id',
            'newEventTitle' => 'required|string|max:255',
            'newEventDate' => 'required|date',
            'newEventPriority' => 'required|in:low,medium,high,critical',
            'newEventStartTime' => 'nullable|string',
            'newEventEndTime' => 'nullable|string',
            'newEventAssignedUser' => 'nullable|exists:users,id',
        ]);

        $project = Project::findOrFail($this->newEventProject);

        if (!$user->isPmoAdmin() && $project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'You can only add tasks to projects you manage.', type: 'error');
            return;
        }

        $wbsCount = WbsItem::where('project_id', $project->id)->count() + 1;

        WbsItem::create([
            'project_id' => $project->id,
            'wbs_code' => (string) $wbsCount,
            'item_type' => $this->newEventType === 'milestone' ? ItemType::PHASE : ItemType::TASK,
            'title' => trim($this->newEventTitle),
            'start_date' => $this->newEventDate,
            'end_date' => $this->newEventDate,
            'start_time' => $this->newEventStartTime ?: null,
            'end_time' => $this->newEventEndTime ?: null,
            'assigned_user_id' => $this->newEventAssignedUser ?: null,
            'status' => WbsStatus::NOT_STARTED,
            'priority' => Priority::from($this->newEventPriority),
            'progress' => 0,
            'weight' => 1.0,
            'is_milestone' => $this->newEventType === 'milestone',
            'created_by' => $user->id,
        ]);

        $this->showCreateEventModal = false;
        $this->dispatch('toast', message: 'Calendar event created successfully!', type: 'success');
    }

    public function updateTaskStatusFromCalendar(int $taskId, string $status): void
    {
        $task = WbsItem::find($taskId);
        if (!$task) return;

        $user = auth()->user();
        $canUpdate = $user->isPmoAdmin()
            || ($task->project && $task->project->project_manager_id === $user->id)
            || $task->assigned_user_id === $user->id;

        if (!$canUpdate) {
            $this->dispatch('toast', message: 'You can only update status for your assigned tasks or managed projects.', type: 'error');
            return;
        }

        $statusEnum = WbsStatus::tryFrom($status);
        if ($statusEnum) {
            $task->status = $statusEnum;
            if ($status === 'completed') {
                $task->progress = 100;
            } elseif ($status === 'in_progress' && $task->progress == 0) {
                $task->progress = 10;
            } elseif ($status === 'not_started') {
                $task->progress = 0;
            }
            $task->saveQuietly();

            (new ProgressCalculationService())->updateItemProgress($task);

            if ($this->selectedEvent && isset($this->selectedEvent['id']) && $this->selectedEvent['id'] == $taskId) {
                $this->selectedEvent['status'] = $status;
                $this->selectedEvent['status_label'] = $statusEnum->label();
                $this->selectedEvent['progress'] = $task->progress;
            }

            $this->dispatch('toast', message: 'Task status updated to ' . $statusEnum->label(), type: 'success');
        }
    }

    protected function getAvailableProjects()
    {
        $user = auth()->user();
        if (!$user) return collect();

        if ($user->isPmoAdmin()) {
            return Project::with('subsidiary')->orderBy('name')->get();
        }

        if ($user->isProjectManager()) {
            return Project::where('project_manager_id', $user->id)
                ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id))
                ->orWhereHas('wbsItems', fn($wq) => $wq->where('assigned_user_id', $user->id))
                ->with('subsidiary')
                ->orderBy('name')
                ->get();
        }

        // Regular User
        return Project::whereHas('wbsItems', fn($wq) => $wq->where('assigned_user_id', $user->id))
            ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id))
            ->with('subsidiary')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        $user = auth()->user();
        $isPmoAdmin = $user && $user->isPmoAdmin();
        $isProjectManager = $user && $user->isProjectManager();

        // Auto-start any due tasks
        WbsItem::autoStartDueTasks();

        $currentDate = Carbon::create($this->year, $this->month, 1);
        $selectedDateObj = Carbon::parse($this->selectedDate ?: now()->format('Y-m-d'));

        // -------------------------------------------------------------
        // 1. Fetch Subsidiaries & Projects for Dropdowns
        // -------------------------------------------------------------
        $projectsList = $this->getAvailableProjects();
        if ($projectsList->isEmpty()) {
            $projectsList = Project::with('subsidiary')->orderBy('name')->get();
        }

        if ($isPmoAdmin) {
            $subsidiaries = Subsidiary::orderBy('name')->get();
        } else {
            $subIds = $projectsList->pluck('subsidiary_id')->filter()->unique();
            $subsidiaries = Subsidiary::whereIn('id', $subIds)->orderBy('name')->get();
            if ($subsidiaries->isEmpty()) {
                $subsidiaries = Subsidiary::orderBy('name')->get();
            }
        }

        // Assignable users for create event modal
        $assignableUsers = User::where('is_active', true)->orderBy('name')->get(['id', 'name', 'email']);

        // -------------------------------------------------------------
        // 2. Compute Role Task Counts for Scope Switcher (My Tasks vs All Tasks)
        // -------------------------------------------------------------
        $myTasksQuery = WbsItem::where('assigned_user_id', $user->id)
            ->where(function($q) {
                $q->whereNotNull('start_date')->orWhereNotNull('end_date');
            });
        if ($this->subsidiaryFilter !== 'all') {
            $myTasksQuery->whereHas('project', fn($q) => $q->where('subsidiary_id', $this->subsidiaryFilter));
        }
        if ($this->projectFilter !== 'all') {
            $myTasksQuery->where('project_id', $this->projectFilter);
        }
        $myTasksScopeCount = $myTasksQuery->count();

        if ($isPmoAdmin) {
            $allTasksQuery = WbsItem::where(function($q) {
                $q->whereNotNull('start_date')->orWhereNotNull('end_date');
            });
            if ($this->subsidiaryFilter !== 'all') {
                $allTasksQuery->whereHas('project', fn($q) => $q->where('subsidiary_id', $this->subsidiaryFilter));
            }
            if ($this->projectFilter !== 'all') {
                $allTasksQuery->where('project_id', $this->projectFilter);
            }
            $allTasksScopeCount = $allTasksQuery->count();
        } elseif ($isProjectManager) {
            $allTasksQuery = WbsItem::where(function($q) use ($user) {
                $q->whereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id))
                  ->orWhere('assigned_user_id', $user->id);
            })->where(function($q) {
                $q->whereNotNull('start_date')->orWhereNotNull('end_date');
            });
            if ($this->subsidiaryFilter !== 'all') {
                $allTasksQuery->whereHas('project', fn($q) => $q->where('subsidiary_id', $this->subsidiaryFilter));
            }
            if ($this->projectFilter !== 'all') {
                $allTasksQuery->where('project_id', $this->projectFilter);
            }
            $allTasksScopeCount = $allTasksQuery->count();
        } else {
            $allTasksScopeCount = $myTasksScopeCount;
        }

        // -------------------------------------------------------------
        // 3. Build Active WBS Items Query Based on Scope & Filters
        // -------------------------------------------------------------
        $wbsQuery = WbsItem::with(['project.subsidiary', 'project.projectManager', 'assignedUser'])
            ->where(function($q) {
                $q->whereNotNull('start_date')->orWhereNotNull('end_date');
            });

        if ($isPmoAdmin) {
            if ($this->scopeFilter === 'my_tasks') {
                $wbsQuery->where('assigned_user_id', $user->id);
            }
            // If 'all', PMO Admin sees all tasks across all projects
        } elseif ($isProjectManager) {
            if ($this->scopeFilter === 'my_tasks') {
                $wbsQuery->where('assigned_user_id', $user->id);
            } else {
                // 'all': All tasks in projects managed by this PM, plus any assigned tasks
                $wbsQuery->where(function($q) use ($user) {
                    $q->whereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id))
                      ->orWhere('assigned_user_id', $user->id);
                });
            }
        } else {
            // Regular User: STRICTLY only assigned tasks
            $wbsQuery->where('assigned_user_id', $user->id);
        }

        // Filter: Subsidiary
        if ($this->subsidiaryFilter !== 'all') {
            $wbsQuery->whereHas('project', fn($q) => $q->where('subsidiary_id', $this->subsidiaryFilter));
        }

        // Filter: Project
        if ($this->projectFilter !== 'all') {
            $wbsQuery->where('project_id', $this->projectFilter);
        }

        // Filter: Status
        if ($this->statusFilter !== 'all') {
            $wbsQuery->where('status', $this->statusFilter);
        }

        // Filter: Priority
        if ($this->priorityFilter !== 'all') {
            $wbsQuery->where('priority', $this->priorityFilter);
        }

        // Filter: Type
        if ($this->typeFilter === 'milestone') {
            $wbsQuery->where('is_milestone', true);
        } elseif ($this->typeFilter === 'task') {
            $wbsQuery->where('is_milestone', false);
        }

        // Filter: Search Keyword
        if (!empty(trim($this->search))) {
            $search = '%' . trim($this->search) . '%';
            $wbsQuery->where(function($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('wbs_code', 'like', $search)
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', $search));
            });
        }

        $allWbs = $wbsQuery->get();

        // -------------------------------------------------------------
        // 4. Fetch Project Deadlines (when not restricted to my_tasks)
        // -------------------------------------------------------------
        $projectsWithDeadlines = collect();
        if ($this->typeFilter !== 'task' && $this->scopeFilter !== 'my_tasks') {
            $projectQuery = Project::query()->whereNotNull('deadline');
            if ($isPmoAdmin) {
                // All projects
            } elseif ($isProjectManager) {
                $projectQuery->where('project_manager_id', $user->id);
            } else {
                $projectQuery->whereHas('wbsItems', fn($q) => $q->where('assigned_user_id', $user->id));
            }

            if ($this->subsidiaryFilter !== 'all') {
                $projectQuery->where('subsidiary_id', $this->subsidiaryFilter);
            }
            if ($this->projectFilter !== 'all') {
                $projectQuery->where('id', $this->projectFilter);
            }
            $projectsWithDeadlines = $projectQuery->get();
        }

        // -------------------------------------------------------------
        // 5. Structure Events by Date Key (Y-m-d)
        // -------------------------------------------------------------
        $eventsByDate = [];
        $monthListEvents = [];
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        foreach ($allWbs as $item) {
            $sDate = $item->start_date ? $item->start_date->copy() : ($item->end_date ? $item->end_date->copy() : null);
            $eDate = $item->end_date ? $item->end_date->copy() : $sDate;

            if ($sDate && $eDate) {
                $startTimeStr = $item->start_time ? Carbon::parse($item->start_time)->format('h:i A') : null;
                $endTimeStr = $item->end_time ? Carbon::parse($item->end_time)->format('h:i A') : null;
                $timeRange = null;
                if ($startTimeStr && $endTimeStr) {
                    $timeRange = "{$startTimeStr} – {$endTimeStr}";
                } elseif ($startTimeStr) {
                    $timeRange = "Starts at {$startTimeStr}";
                } elseif ($endTimeStr) {
                    $timeRange = "Due at {$endTimeStr}";
                }

                $color = match(true) {
                    $item->is_milestone => 'purple',
                    $item->status === WbsStatus::COMPLETED => 'emerald',
                    $item->status === WbsStatus::IN_PROGRESS => 'blue',
                    $item->status === WbsStatus::BLOCKED => 'rose',
                    default => 'crimson',
                };

                $eventItem = [
                    'id' => $item->id,
                    'project_id' => $item->project_id,
                    'project' => $item->project->name ?? 'Project',
                    'project_code' => $item->project->code ?? '',
                    'subsidiary' => $item->project?->subsidiary?->name ?? '',
                    'type' => $item->is_milestone ? 'milestone' : 'task',
                    'title' => $item->title,
                    'description' => $item->description,
                    'code' => $item->wbs_code,
                    'priority' => $item->priority?->value ?? 'medium',
                    'priority_label' => $item->priority ? $item->priority->label() : 'Medium',
                    'status' => $item->status?->value ?? 'not_started',
                    'status_label' => $item->status ? $item->status->label() : 'Not Started',
                    'progress' => $item->progress ?? 0,
                    'start_date' => $item->start_date ? $item->start_date->format('Y-m-d') : null,
                    'end_date' => $item->end_date ? $item->end_date->format('Y-m-d') : null,
                    'start_date_formatted' => $item->start_date ? $item->start_date->format('M d, Y') : null,
                    'end_date_formatted' => $item->end_date ? $item->end_date->format('M d, Y') : null,
                    'start_time' => $startTimeStr,
                    'end_time' => $endTimeStr,
                    'time_range' => $timeRange,
                    'assigned_user' => $item->assignedUser->name ?? 'Unassigned',
                    'assigned_user_id' => $item->assigned_user_id,
                    'assigned_user_email' => $item->assignedUser->email ?? null,
                    'is_my_task' => $item->assigned_user_id === $user->id,
                    'color' => $color,
                ];

                // Day-by-day mapping for calendar grid & day agenda
                $currDate = $sDate->copy();
                $maxSpan = min(14, $sDate->diffInDays($eDate) + 1);
                $spanCount = 0;

                while ($currDate->lte($eDate) && $spanCount < $maxSpan) {
                    $dateKey = $currDate->format('Y-m-d');
                    $dayEvent = $eventItem;
                    $dayEvent['date'] = $dateKey;
                    $eventsByDate[$dateKey][] = $dayEvent;
                    $currDate->addDay();
                    $spanCount++;
                }

                // Month List View items (overlapping current month)
                if ($sDate->lte($endOfMonth) && $eDate->gte($startOfMonth)) {
                    $primaryDate = ($eDate->between($startOfMonth, $endOfMonth))
                        ? $eDate
                        : (($sDate->between($startOfMonth, $endOfMonth)) ? $sDate : $startOfMonth);
                    $listItem = $eventItem;
                    $listItem['raw_date'] = $primaryDate->format('Y-m-d');
                    $listItem['display_date'] = $primaryDate;
                    $monthListEvents[] = $listItem;
                }
            }
        }

        // Project Deadlines
        foreach ($projectsWithDeadlines as $proj) {
            if ($proj->deadline) {
                $dateKey = $proj->deadline->format('Y-m-d');
                $deadlineItem = [
                    'id' => $proj->id,
                    'project_id' => $proj->id,
                    'project' => $proj->name,
                    'project_code' => $proj->code,
                    'subsidiary' => $proj->subsidiary->name ?? '',
                    'type' => 'project_deadline',
                    'title' => 'Project Deadline: ' . $proj->name,
                    'description' => $proj->description,
                    'code' => $proj->code,
                    'priority' => $proj->priority?->value ?? 'high',
                    'priority_label' => $proj->priority ? $proj->priority->label() : 'High',
                    'status' => $proj->status?->value ?? 'in_progress',
                    'status_label' => $proj->status ? $proj->status->label() : 'In Progress',
                    'progress' => $proj->overall_progress ?? 0,
                    'start_date' => $proj->start_date ? $proj->start_date->format('Y-m-d') : null,
                    'end_date' => $proj->deadline->format('Y-m-d'),
                    'start_date_formatted' => $proj->start_date ? $proj->start_date->format('M d, Y') : null,
                    'end_date_formatted' => $proj->deadline->format('M d, Y'),
                    'start_time' => null,
                    'end_time' => null,
                    'time_range' => 'All Day Milestone',
                    'assigned_user' => $proj->projectManager->name ?? 'Lead PM',
                    'assigned_user_id' => $proj->project_manager_id,
                    'assigned_user_email' => $proj->projectManager->email ?? null,
                    'is_my_task' => $proj->project_manager_id === $user->id,
                    'color' => 'rose',
                    'date' => $dateKey,
                ];

                $eventsByDate[$dateKey][] = $deadlineItem;

                if ($proj->deadline->between($startOfMonth, $endOfMonth)) {
                    $deadlineListItem = $deadlineItem;
                    $deadlineListItem['raw_date'] = $proj->deadline->format('Y-m-d');
                    $deadlineListItem['display_date'] = $proj->deadline;
                    $monthListEvents[] = $deadlineListItem;
                }
            }
        }

        // Sort month list events chronologically
        usort($monthListEvents, fn($a, $b) => strcmp($a['raw_date'], $b['raw_date']));

        // Group by Date String for timeline blocks
        $groupedListEvents = [];
        foreach ($monthListEvents as $evt) {
            $groupedListEvents[$evt['raw_date']][] = $evt;
        }

        // -------------------------------------------------------------
        // 6. Selected Day Schedule Items (Sorted by Start Time)
        // -------------------------------------------------------------
        $selectedDayEvents = $eventsByDate[$this->selectedDate] ?? [];
        usort($selectedDayEvents, function($a, $b) {
            $timeA = $a['start_time'] ?? '99:99';
            $timeB = $b['start_time'] ?? '99:99';
            return strcmp($timeA, $timeB);
        });

        // -------------------------------------------------------------
        // 7. Month Calendar Grid Weeks & Days
        // -------------------------------------------------------------
        $startOfWeekDay = $startOfMonth->dayOfWeek; // 0 for Sunday
        $daysInMonth = $currentDate->daysInMonth;

        $weeks = [];
        $currentWeek = [];

        // Previous month padding days
        $prevMonthLastDay = $startOfMonth->copy()->subDay();
        for ($i = $startOfWeekDay - 1; $i >= 0; $i--) {
            $dayDate = $prevMonthLastDay->copy()->subDays($i);
            $currentWeek[] = [
                'date' => $dayDate->format('Y-m-d'),
                'dayNumber' => (int) $dayDate->format('d'),
                'isCurrentMonth' => false,
                'isToday' => $dayDate->isToday(),
                'isSelected' => $dayDate->format('Y-m-d') === $this->selectedDate,
                'events' => $eventsByDate[$dayDate->format('Y-m-d')] ?? [],
            ];
        }

        // Current month days
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dayDate = Carbon::create($this->year, $this->month, $d);
            $currentWeek[] = [
                'date' => $dayDate->format('Y-m-d'),
                'dayNumber' => $d,
                'isCurrentMonth' => true,
                'isToday' => $dayDate->isToday(),
                'isSelected' => $dayDate->format('Y-m-d') === $this->selectedDate,
                'events' => $eventsByDate[$dayDate->format('Y-m-d')] ?? [],
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
                $currentWeek[] = [
                    'date' => $dayDate->format('Y-m-d'),
                    'dayNumber' => $nextMonthDay,
                    'isCurrentMonth' => false,
                    'isToday' => $dayDate->isToday(),
                    'isSelected' => $dayDate->format('Y-m-d') === $this->selectedDate,
                    'events' => $eventsByDate[$dayDate->format('Y-m-d')] ?? [],
                ];
                $nextMonthDay++;
            }
            $weeks[] = $currentWeek;
        }

        // -------------------------------------------------------------
        // 8. KPI Cockpit Metrics for Current Month
        // -------------------------------------------------------------
        $totalMonthEvents = count($monthListEvents);
        $inProgressMonthEvents = count(array_filter($monthListEvents, fn($e) => $e['status'] === 'in_progress'));
        $completedMonthEvents = count(array_filter($monthListEvents, fn($e) => $e['status'] === 'completed'));
        $milestoneMonthEvents = count(array_filter($monthListEvents, fn($e) => $e['type'] === 'milestone' || $e['type'] === 'project_deadline'));
        $overdueMonthEvents = count(array_filter($monthListEvents, fn($e) => $e['status'] !== 'completed' && isset($e['raw_date']) && $e['raw_date'] < now()->format('Y-m-d')));

        return view('livewire.calendar-view', compact(
            'weeks',
            'currentDate',
            'selectedDateObj',
            'selectedDayEvents',
            'eventsByDate',
            'monthListEvents',
            'groupedListEvents',
            'subsidiaries',
            'projectsList',
            'assignableUsers',
            'isPmoAdmin',
            'isProjectManager',
            'myTasksScopeCount',
            'allTasksScopeCount',
            'totalMonthEvents',
            'inProgressMonthEvents',
            'completedMonthEvents',
            'milestoneMonthEvents',
            'overdueMonthEvents'
        ))->layout('layouts.app', ['title' => 'Project Calendar — GS NexusPM']);
    }
}
