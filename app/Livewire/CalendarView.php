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
    public string $scopeFilter = 'all'; // 'all' or 'my_events'
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
    public bool $showCreateEventModal = false;
    public ?int $newEventProject = null;
    public string $newEventTitle = '';
    public string $newEventDescription = '';
    public string $newEventType = 'meeting'; // 'meeting', 'review', 'milestone', 'task', 'deadline'
    public ?string $newEventDate = null;
    public ?string $newEventEndDate = null;
    public ?string $newEventStartTime = '09:00';
    public ?string $newEventEndTime = '10:00';
    public bool $newEventIsAllDay = false;
    public string $newEventLocation = '';
    public string $newEventMeetingLink = '';
    public string $newEventPriority = 'medium';
    public array $newEventAttendees = [];

    // Outlook / Calendar Subscription Modal
    public bool $showSubscribeModal = false;

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

        // If regular user with neither PMO Admin nor PM role, default to my_events
        if (!$isPmoAdmin && !$isPm) {
            $this->scopeFilter = 'my_events';
        } else {
            $this->scopeFilter = 'all';
        }

        // Initialize default project for creation modal if user has access
        $creatableProjects = $this->getCreatableProjects();
        if ($creatableProjects->isNotEmpty()) {
            $this->newEventProject = $creatableProjects->first()->id;
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

    // ─── Modal Actions ────────────────────────────────────────────────

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
        ]);

        $this->newEventType = 'meeting';
        $this->newEventPriority = 'medium';
        $this->newEventStartTime = '09:00';
        $this->newEventEndTime = '10:00';
        $this->newEventIsAllDay = false;

        $selected = $date ?: ($this->selectedDate ?: now()->format('Y-m-d'));
        $this->newEventDate = $selected;
        $this->newEventEndDate = $selected;

        $creatable = $this->getCreatableProjects();
        if ($creatable->isNotEmpty()) {
            $this->newEventProject = $creatable->first()->id;
        }

        $this->showCreateEventModal = true;
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
        $this->dispatch('toast', message: 'Calendar event created successfully & added to schedule.', type: 'success');
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
        if (!$user || empty($user->email)) return null;

        $targetEmail = $user->email;
        $attendeeEmails = [];
        if (!empty($event->attendees)) {
            $attendeeEmails = User::whereIn('id', $event->attendees)->pluck('email')->filter()->all();
        }

        $result = AzureGraphService::createCalendarEvent($targetEmail, [
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
        ]);

        if ($result['success'] && !empty($result['microsoft_event_id'])) {
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

            $startTimeStr = $wbs->start_time ? Carbon::parse($wbs->start_time)->format('h:i A') : null;
            $endTimeStr = $wbs->end_time ? Carbon::parse($wbs->end_time)->format('h:i A') : null;
            $timeRange = ($startTimeStr && $endTimeStr)
                ? "{$startTimeStr} – {$endTimeStr}"
                : ($startTimeStr ? "Starts {$startTimeStr}" : ($endTimeStr ? "Due {$endTimeStr}" : 'All Day Deliverable'));

            $isMilestone = (bool)$wbs->is_milestone;
            $color = $isMilestone ? 'purple' : ($wbs->status === WbsStatus::COMPLETED ? 'emerald' : 'blue');

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

        $query = http_build_query([
            'path'     => '/calendar/action/compose',
            'rru'      => 'addevent',
            'subject'  => $task->title . ($task->project ? ' [' . $task->project->code . ']' : ''),
            'body'     => ($task->description ? $task->description . "\n\n" : '') . "Project: " . ($task->project->name ?? 'GS'),
            'startdt'  => $startIso,
            'enddt'    => $endIso,
            'location' => 'GS NexusPM Workspace',
            'allday'   => empty($task->start_time) && empty($task->end_time) ? 'true' : 'false',
        ]);

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
