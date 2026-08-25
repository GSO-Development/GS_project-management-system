<?php

namespace App\Livewire;

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\WbsItem;
use Carbon\Carbon;
use Livewire\Component;

class CalendarView extends Component
{
    public string $subsidiaryFilter = 'all';
    public string $projectFilter = 'all';
    public string $viewMode = 'calendar'; // 'calendar' or 'list'

    public int $year;
    public int $month;

    // Modals state
    public bool $showEventModal = false;
    public ?array $selectedEvent = null;

    public bool $showCreateEventModal = false;
    public ?int $newEventProject = null;
    public string $newEventTitle = '';
    public string $newEventType = 'milestone'; // 'milestone' or 'task'
    public ?string $newEventDate = null;
    public string $newEventPriority = 'medium';

    public function mount()
    {
        $this->year = (int) now()->format('Y');
        $this->month = (int) now()->format('m');
        $this->newEventDate = now()->format('Y-m-d');

        $firstProj = Project::first();
        if ($firstProj) {
            $this->newEventProject = $firstProj->id;
        }
    }

    public function prevMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->year = (int) $date->format('Y');
        $this->month = (int) $date->format('m');
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->year = (int) $date->format('Y');
        $this->month = (int) $date->format('m');
    }

    public function today()
    {
        $this->year = (int) now()->format('Y');
        $this->month = (int) now()->format('m');
    }

    public function showEventDetails(array $event)
    {
        $this->selectedEvent = $event;
        $this->showEventModal = true;
    }

    public function openCreateEventModal(?string $date = null)
    {
        $this->reset(['newEventTitle', 'newEventType', 'newEventPriority']);
        if ($date) {
            $this->newEventDate = $date;
        } else {
            $this->newEventDate = now()->format('Y-m-d');
        }

        $firstProj = Project::first();
        if ($firstProj) {
            $this->newEventProject = $firstProj->id;
        }

        $this->showCreateEventModal = true;
    }

    public function createEvent()
    {
        $this->validate([
            'newEventProject' => 'required|exists:projects,id',
            'newEventTitle' => 'required|string|max:255',
            'newEventDate' => 'required|date',
            'newEventPriority' => 'required|in:low,medium,high,critical',
        ]);

        $project = Project::findOrFail($this->newEventProject);

        $wbsCount = WbsItem::where('project_id', $project->id)->count() + 1;

        WbsItem::create([
            'project_id' => $project->id,
            'wbs_code' => (string) $wbsCount,
            'item_type' => $this->newEventType === 'milestone' ? ItemType::PHASE : ItemType::TASK,
            'title' => $this->newEventTitle,
            'end_date' => $this->newEventDate,
            'status' => WbsStatus::NOT_STARTED,
            'priority' => Priority::from($this->newEventPriority),
            'is_milestone' => $this->newEventType === 'milestone',
            'created_by' => auth()->id(),
        ]);

        $this->showCreateEventModal = false;
        $this->dispatch('toast', message: 'Calendar event created successfully!', type: 'success');
    }

    public function updateTaskStatusFromCalendar(int $taskId, string $status)
    {
        $task = WbsItem::find($taskId);
        if (!$task) return;

        $user = auth()->user();
        if (!$user->hasRole('super_admin') && $task->project->project_manager_id !== $user->id && $task->assigned_user_id !== $user->id) {
            $this->dispatch('toast', message: 'You can only update status for your assigned tasks.', type: 'error');
            return;
        }

        $statusEnum = WbsStatus::tryFrom($status);
        if ($statusEnum) {
            $task->status = $statusEnum;
            if ($status === 'completed') $task->progress = 100;
            elseif ($status === 'in_progress' && $task->progress == 0) $task->progress = 10;
            elseif ($status === 'not_started') $task->progress = 0;
            $task->save();

            (new \App\Services\ProgressCalculationService())->updateItemProgress($task);

            if ($this->selectedEvent && isset($this->selectedEvent['id']) && $this->selectedEvent['id'] == $taskId) {
                $this->selectedEvent['status'] = $status;
                $this->selectedEvent['progress'] = $task->progress;
            }

            $this->dispatch('toast', message: 'Task status updated to ' . $statusEnum->label(), type: 'success');
        }
    }

    public function render()
    {
        $user = auth()->user();
        $currentDate = Carbon::create($this->year, $this->month, 1);

        // Fetch WBS items
        $wbsQuery = WbsItem::with(['project', 'assignedUser'])
            ->where(function($q) {
                $q->whereNotNull('start_date')->orWhereNotNull('end_date');
            });

        if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
            $wbsQuery->where(function($q) use ($user) {
                $q->whereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id))
                  ->orWhere(function($sub) use ($user) {
                      $sub->where('assigned_user_id', $user->id)
                          ->whereHas('project', fn($pq) => $pq->where('pm_accepted', true));
                  });
            });
        }

        if ($this->subsidiaryFilter !== 'all') {
            $wbsQuery->whereHas('project', fn($q) => $q->where('subsidiary_id', $this->subsidiaryFilter));
        }

        if ($this->projectFilter !== 'all') {
            $wbsQuery->where('project_id', $this->projectFilter);
        }

        $allWbs = $wbsQuery->get();

        // Fetch Project Deadlines
        $projectQuery = Project::query()->whereNotNull('deadline');
        if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
            $projectQuery->where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhere(function($sub) use ($user) {
                      $sub->where('pm_accepted', true)
                          ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                  });
            });
        }
        if ($this->subsidiaryFilter !== 'all') {
            $projectQuery->where('subsidiary_id', $this->subsidiaryFilter);
        }
        if ($this->projectFilter !== 'all') {
            $projectQuery->where('id', $this->projectFilter);
        }
        $projectsWithDeadlines = $projectQuery->get();

        // Group events by Y-m-d date string
        $eventsByDate = [];

        foreach ($allWbs as $item) {
            $sDate = $item->start_date ? $item->start_date->copy() : ($item->end_date ? $item->end_date->copy() : null);
            $eDate = $item->end_date ? $item->end_date->copy() : $sDate;

            if ($sDate && $eDate) {
                $currDate = $sDate->copy();
                $maxSpan = min(14, $sDate->diffInDays($eDate) + 1);
                $spanCount = 0;

                while ($currDate->lte($eDate) && $spanCount < $maxSpan) {
                    $dateKey = $currDate->format('Y-m-d');
                    $eventsByDate[$dateKey][] = [
                        'id' => $item->id,
                        'project_id' => $item->project_id,
                        'type' => $item->is_milestone ? 'milestone' : 'task',
                        'title' => $item->title,
                        'code' => $item->wbs_code,
                        'project' => $item->project->name ?? '',
                        'priority' => $item->priority->value ?? 'medium',
                        'status' => $item->status->value ?? 'not_started',
                        'progress' => $item->progress ?? 0,
                        'assigned_user' => $item->assignedUser->name ?? 'Unassigned',
                        'assigned_user_id' => $item->assigned_user_id,
                        'color' => $item->is_milestone ? 'purple' : ($item->status->value === 'completed' ? 'emerald' : 'crimson'),
                        'date' => $dateKey,
                    ];
                    $currDate->addDay();
                    $spanCount++;
                }
            }
        }

        foreach ($projectsWithDeadlines as $proj) {
            if ($proj->deadline) {
                $dateKey = $proj->deadline->format('Y-m-d');
                $eventsByDate[$dateKey][] = [
                    'id' => $proj->id,
                    'project_id' => $proj->id,
                    'type' => 'project_deadline',
                    'title' => 'Project Deadline: ' . $proj->name,
                    'code' => $proj->code,
                    'project' => $proj->name,
                    'priority' => $proj->priority->value ?? 'high',
                    'color' => 'rose',
                    'date' => $dateKey,
                ];
            }
        }

        // Build List View Unique Events Collection (Sorted Chronologically)
        $monthListEvents = [];
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        foreach ($allWbs as $item) {
            $sDate = $item->start_date ? $item->start_date->copy() : ($item->end_date ? $item->end_date->copy() : null);
            $eDate = $item->end_date ? $item->end_date->copy() : $sDate;

            if ($sDate && $eDate) {
                // Check if this item overlaps with the current month
                if ($sDate->lte($endOfMonth) && $eDate->gte($startOfMonth)) {
                    // Pick primary display date for grouping
                    $primaryDate = ($eDate->between($startOfMonth, $endOfMonth)) ? $eDate : (($sDate->between($startOfMonth, $endOfMonth)) ? $sDate : $startOfMonth);
                    
                    $monthListEvents[] = [
                        'id' => $item->id,
                        'project_id' => $item->project_id,
                        'project_name' => $item->project->name ?? 'Project',
                        'project_code' => $item->project->code ?? null,
                        'subsidiary_name' => $item->project?->subsidiary?->name ?? null,
                        'type' => $item->is_milestone ? 'milestone' : 'task',
                        'title' => $item->title,
                        'code' => $item->wbs_code,
                        'priority' => $item->priority->value ?? 'medium',
                        'status' => $item->status->value ?? 'not_started',
                        'status_label' => $item->status ? $item->status->label() : 'Not Started',
                        'progress' => $item->progress ?? 0,
                        'start_date' => $item->start_date ? $item->start_date->format('M d, Y') : null,
                        'end_date' => $item->end_date ? $item->end_date->format('M d, Y') : null,
                        'raw_date' => $primaryDate->format('Y-m-d'),
                        'display_date' => $primaryDate,
                        'assigned_user' => $item->assignedUser->name ?? 'Unassigned',
                        'assigned_user_id' => $item->assigned_user_id,
                        'color' => $item->is_milestone ? 'purple' : ($item->status->value === 'completed' ? 'emerald' : 'crimson'),
                    ];
                }
            }
        }

        foreach ($projectsWithDeadlines as $proj) {
            if ($proj->deadline && $proj->deadline->between($startOfMonth, $endOfMonth)) {
                $monthListEvents[] = [
                    'id' => $proj->id,
                    'project_id' => $proj->id,
                    'project_name' => $proj->name,
                    'project_code' => $proj->code,
                    'subsidiary_name' => $proj->subsidiary->name ?? null,
                    'type' => 'project_deadline',
                    'title' => 'Project Deadline: ' . $proj->name,
                    'code' => $proj->code,
                    'priority' => $proj->priority->value ?? 'high',
                    'status' => $proj->status->value ?? 'in_progress',
                    'status_label' => $proj->status ? $proj->status->label() : 'In Progress',
                    'progress' => $proj->overall_progress ?? 0,
                    'start_date' => $proj->start_date ? $proj->start_date->format('M d, Y') : null,
                    'end_date' => $proj->deadline->format('M d, Y'),
                    'raw_date' => $proj->deadline->format('Y-m-d'),
                    'display_date' => $proj->deadline,
                    'assigned_user' => $proj->projectManager->name ?? 'Lead PM',
                    'color' => 'rose',
                ];
            }
        }

        // Sort all list events chronologically by date
        usort($monthListEvents, fn($a, $b) => strcmp($a['raw_date'], $b['raw_date']));

        // Group by Date String for visual timeline blocks
        $groupedListEvents = [];
        foreach ($monthListEvents as $evt) {
            $groupedListEvents[$evt['raw_date']][] = $evt;
        }

        // Build Calendar Grid Weeks & Days
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
                'events' => $eventsByDate[$dayDate->format('Y-m-d')] ?? [],
            ];

            if (count($currentWeek) === 7) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }
        }

        // Next month padding days to complete last week
        if (count($currentWeek) > 0) {
            $nextMonthDay = 1;
            while (count($currentWeek) < 7) {
                $dayDate = $endOfMonth->copy()->addDays($nextMonthDay);
                $currentWeek[] = [
                    'date' => $dayDate->format('Y-m-d'),
                    'dayNumber' => $nextMonthDay,
                    'isCurrentMonth' => false,
                    'isToday' => $dayDate->isToday(),
                    'events' => $eventsByDate[$dayDate->format('Y-m-d')] ?? [],
                ];
                $nextMonthDay++;
            }
            $weeks[] = $currentWeek;
        }

        $subsidiaries = Subsidiary::all();
        $projectsList = Project::all();

        return view('livewire.calendar-view', compact(
            'weeks',
            'currentDate',
            'eventsByDate',
            'monthListEvents',
            'groupedListEvents',
            'subsidiaries',
            'projectsList'
        ));
    }
}
