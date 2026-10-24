<?php

namespace App\Livewire;

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Imports\TaskFileImport;
use App\Models\Comment;
use App\Models\Project;
use App\Models\User;
use App\Models\WbsDependency;
use App\Models\WbsItem;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskCompletedNotification;
use App\Services\DependencyValidationService;
use App\Services\ProgressCalculationService;
use App\Services\WbsNumberingService;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class WbsTree extends Component
{
    use WithPagination, WithFileUploads;

    public Project $project;
    public int $perPage = 50;
    public array $collapsedIds = [];

    // Add/Edit Modal
    public bool $showItemModal = false;
    public ?int $editingItemId = null;
    public ?int $selectedParentId = null;

    public string $item_type = 'task';
    public string $title = '';
    public ?string $description = null;
    public ?int $assigned_user_id = null;
    public ?string $start_date = null;
    public ?string $start_time = null;
    public ?string $end_date = null;
    public ?string $end_time = null;
    public string $status = 'not_started';
    public string $priority = 'medium';
    public int $progress = 0;
    public ?float $estimated_hours = null;
    public float $weight = 1.0;
    public bool $is_milestone = false;

    // Bulk task file import
    public bool $showImportModal = false;
    public $taskImportFile = null;
    public array $taskImportPreview = [];
    public array $taskImportErrors = [];

    // Task Status Filter ('all', 'in_progress', 'at_risk', 'blocked', 'completed')
    public string $statusFilter = 'all';

    // Assignee Filter ('all', 'mine')
    public string $assigneeFilter = 'all';

    // Cascade Impact Preview Modal
    public bool $showCascadeModal = false;
    public array $cascadePreview = [];
    public int $cascadeDaysDelta = 0;
    public ?int $cascadeSourceTaskId = null;       // task already saved — used to revert on Cancel
    public ?string $cascadeRevertEndDate = null;   // old end_date for revert
    public ?string $cascadeRevertStartDate = null; // old start_date for revert (not changed but kept for safety)

    // Dependency Modal
    public bool $showDepModal = false;
    public ?int $depSuccessorId = null;
    public ?int $depPredecessorId = null;

    public function getCanManageTasksProperty(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->isPmoAdmin()
            || $this->project->project_manager_id === $user->id
            || $this->project->userCan($user, 'task.edit');
    }

    public function getCanCreateTasksProperty(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->isPmoAdmin() 
            || $this->project->project_manager_id === $user->id
            || $this->project->userCan($user, 'task.create');
    }

    public function getCanCreateSubtasksProperty(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->isPmoAdmin() 
            || $this->project->project_manager_id === $user->id
            || $this->project->userCan($user, 'task.create_subtask')
            || $this->project->userCan($user, 'task.create');
    }

    public function getCanEditTasksProperty(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->isPmoAdmin() 
            || $this->project->project_manager_id === $user->id
            || $this->project->userCan($user, 'task.edit')
            || $this->project->userCan($user, 'task.edit_assigned');
    }

    public function getCanDeleteTasksProperty(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->isPmoAdmin() 
            || $this->project->project_manager_id === $user->id
            || $this->project->userCan($user, 'task.delete');
    }

    public function canEditSpecificItem(?WbsItem $item): bool
    {
        $user = auth()->user();
        if (!$user || !$item) return false;
        if ($user->isPmoAdmin()) return true;
        if ($this->project->project_manager_id === $user->id) return true;
        if ($this->project->userCan($user, 'task.edit')) return true;
        if ($item->assigned_user_id === $user->id && $this->project->userCan($user, 'task.edit_assigned')) return true;
        return false;
    }

    public function canDeleteSpecificItem(?WbsItem $item): bool
    {
        $user = auth()->user();
        if (!$user || !$item) return false;
        if ($user->isPmoAdmin()) return true;
        if ($this->project->project_manager_id === $user->id) return true;
        if ($this->project->userCan($user, 'task.delete')) return true;
        return false;
    }

    protected function rules(): array
    {
        return [
            'item_type' => 'required|in:phase,work_package,task,subtask,milestone',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'start_time' => 'nullable|string',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'end_time' => 'nullable|string',
            'status' => 'required|string',
            'priority' => 'required|string',
            'progress' => 'required|integer|min:0|max:100',
            'estimated_hours' => 'nullable|numeric|min:0',
            'weight' => 'required|numeric|min:0.1',
            'is_milestone' => 'boolean',
        ];
    }

    public function mount(Project $project): void
    {
        $this->project = $project;
        $user = auth()->user();

        // If project is not yet accepted by the Project Manager, only PMO Admin and the designated Project Manager can access it
        if (!$project->isPmAccepted() && !$user->isPmoAdmin() && $project->project_manager_id !== $user->id) {
            abort(403, 'This project is pending Project Manager acceptance.');
        }
        
        // Automatically transition tasks to in_progress if their start_date has arrived
        WbsItem::autoStartDueTasks($this->project->id);

        // Auto-cascade schedule dates if all tasks are in default template date state
        \App\Services\WbsScheduleCascadeService::cascadeProjectSchedule($this->project->id);

        // Check for newly overdue tasks and send automated email + database alert to PMO Admins & PM
        \App\Services\TaskOverdueNotificationService::checkAndNotifyOverdueTasks($this->project->id);

        // Default: collapse all items that have children so initially only main phases/tasks are visible
        $this->collapsedIds = WbsItem::where('project_id', $project->id)
            ->whereHas('children')
            ->pluck('id')
            ->map(fn($id) => (int)$id)
            ->toArray();
    }

    public function toggleCollapse(int $id): void
    {
        if (in_array($id, $this->collapsedIds)) {
            $this->collapsedIds = array_values(array_diff($this->collapsedIds, [$id]));
        } else {
            $this->collapsedIds[] = $id;
        }
    }

    public function expandAll(): void
    {
        $this->collapsedIds = [];
    }

    public function collapseAll(): void
    {
        $this->collapsedIds = WbsItem::where('project_id', $this->project->id)
            ->whereHas('children')
            ->pluck('id')
            ->map(fn($id) => (int)$id)
            ->toArray();
    }

    #[On('wbsUpdated')]
    #[On('refreshWbs')]
    public function refreshTree(): void
    {
        $this->project->refresh();
        $this->collapsedIds = WbsItem::where('project_id', $this->project->id)
            ->whereHas('children')
            ->pluck('id')
            ->map(fn($id) => (int)$id)
            ->toArray();
    }

    public function openAddItemModal(?int $parentId = null, string $type = 'task')
    {
        if ($parentId || $type === 'subtask') {
            abort_if(!$this->canCreateSubtasks, 403, 'You do not have permission to create subtasks for this project.');
        } else {
            abort_if(!$this->canCreateTasks, 403, 'You do not have permission to create tasks for this project.');
        }

        $this->reset(['editingItemId', 'title', 'description', 'assigned_user_id', 'start_date', 'start_time', 'end_date', 'end_time', 'progress', 'estimated_hours', 'is_milestone']);
        $this->selectedParentId = $parentId;
        $this->item_type = $type;

        if ($parentId) {
            $parentTask = WbsItem::find($parentId);
            $this->assigned_user_id = $parentTask->assigned_user_id ?? auth()->id();
            $this->start_date = $parentTask->start_date ? $parentTask->start_date->toDateString() : ($this->project->start_date ? $this->project->start_date->toDateString() : now()->toDateString());
            $this->end_date = $parentTask->end_date ? $parentTask->end_date->toDateString() : $this->start_date;

            // Intelligent hour slots for day subtasks (Default 2 slots: 08:30-12:30 and 12:30-17:30)
            $existingChildren = WbsItem::where('project_id', $this->project->id)->where('parent_id', $parentId)->orderBy('start_time')->get();
            if ($existingChildren->count() === 0) {
                $this->start_time = '08:30';
                $this->end_time   = '12:30';
                $this->title      = '08:30 AM – 12:30 PM';
            } elseif ($existingChildren->count() === 1) {
                $this->start_time = '12:30';
                $this->end_time   = '17:30';
                $this->title      = '12:30 PM – 05:30 PM';
            } else {
                $lastEndTime = $existingChildren->last()->end_time ? \Carbon\Carbon::parse($existingChildren->last()->end_time)->format('H:i') : '17:30';
                $this->start_time = $lastEndTime;
                $this->end_time   = \Carbon\Carbon::parse($lastEndTime)->addHours(2)->format('H:i');
                $pCode = $parentTask ? $parentTask->wbs_code : '1';
                $nextNum = $existingChildren->count() + 1;
                $this->title      = "Sub-task {$pCode}.{$nextNum}";
            }
            $this->item_type = 'subtask';
        } else {
            $this->assigned_user_id = auth()->id();
            $this->start_date = $this->project->start_date ? $this->project->start_date->toDateString() : now()->toDateString();
            $this->start_time = '08:30';
            $this->end_date = $this->start_date;
            $this->end_time = '17:30';
            $this->updateDefaultTitle();
        }

        $this->status = 'not_started';
        $this->priority = 'medium';
        $this->progress = 0;
        $this->weight = 1.0;
        $this->showItemModal = true;
    }

    public function openImportModal(): void
    {
        abort_if(!$this->canCreateTasks, 403, 'You do not have permission to import tasks for this project.');
        $this->resetValidation('taskImportFile');
        $this->reset(['taskImportFile', 'taskImportPreview', 'taskImportErrors']);
        $this->showImportModal = true;
    }

    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->reset(['taskImportFile', 'taskImportPreview', 'taskImportErrors']);
    }

    public function previewTaskImport(): void
    {
        abort_if(!$this->canCreateTasks, 403, 'You do not have permission to import tasks for this project.');
        $this->validate([
            'taskImportFile' => 'required|file|mimes:xlsx,xls,csv,txt,docx|max:5120',
        ]);

        try {
            $extension = strtolower($this->taskImportFile->getClientOriginalExtension());
            if ($extension === 'docx') {
                $rows = collect($this->parseDocxTaskRows($this->taskImportFile->getRealPath()));
            } else {
                $sheets = Excel::toArray(new TaskFileImport(), $this->taskImportFile);
                $rows = collect($sheets[0] ?? []);
            }
        } catch (\Throwable $e) {
            report($e);
            $this->addError('taskImportFile', 'The selected file could not be read. Check its format and try again.');
            $this->taskImportPreview = [];
            return;
        }
        $rows = $rows->filter(fn ($row) => filled($row['title'] ?? null));
        $allowedTypes = ['phase', 'work_package', 'task', 'subtask', 'milestone'];
        $allowedStatuses = ['backlog', 'not_started', 'in_progress', 'blocked', 'under_review', 'completed', 'on_hold', 'cancelled'];
        $allowedPriorities = ['low', 'medium', 'high', 'critical'];
        $this->taskImportErrors = [];
        $this->taskImportPreview = [];

        foreach ($rows->take(500)->values() as $index => $row) {
            $rowNumber = $index + 2;
            $title = trim((string) ($row['title'] ?? ''));
            if (mb_strlen($title) > 255) {
                $this->taskImportErrors[] = "Row {$rowNumber}: task title is longer than 255 characters and was skipped.";
                continue;
            }
            try {
                $startDate = $this->normalizeImportDate($row['start_date'] ?? null);
                $endDate = $this->normalizeImportDate($row['end_date'] ?? null);
            } catch (\Throwable $e) {
                $this->taskImportErrors[] = "Row {$rowNumber}: invalid start_date or end_date.";
                continue;
            }

            if ($startDate && $endDate && $endDate < $startDate) {
                $this->taskImportErrors[] = "Row {$rowNumber}: end_date must be on or after start_date.";
                continue;
            }

            $type = Str::snake(strtolower(trim((string) ($row['type'] ?? $row['item_type'] ?? 'task'))));
            $status = Str::snake(strtolower(trim((string) ($row['status'] ?? 'not_started'))));
            $priority = strtolower(trim((string) ($row['priority'] ?? 'medium')));
            $progress = max(0, min(100, (int) preg_replace('/[^0-9]/', '', (string) ($row['progress'] ?? 0))));
            $assignee = trim((string) ($row['assignee_email'] ?? $row['assignee'] ?? $row['assigned_to'] ?? $row['responsible_person'] ?? ''));

            $this->taskImportPreview[] = [
                'row' => $rowNumber,
                'title' => $title,
                'description' => filled($row['description'] ?? null) ? trim((string) $row['description']) : null,
                'item_type' => in_array($type, $allowedTypes, true) ? $type : 'task',
                'assignee' => $assignee,
                'assignee_email' => str_contains($assignee, '@') ? strtolower($assignee) : '',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => in_array($status, $allowedStatuses, true) ? $status : 'not_started',
                'priority' => in_array($priority, $allowedPriorities, true) ? $priority : 'medium',
                'progress' => $status === 'completed' ? 100 : $progress,
                'estimated_hours' => $this->normalizeEstimatedHours($row['estimated_hours'] ?? $row['duration'] ?? null),
            ];
        }

        if ($rows->count() === 0) {
            $this->addError('taskImportFile', 'No task rows were found. The file must contain a title column.');
        }
    }

    public function importTasks(): void
    {
        abort_if(!$this->canCreateTasks, 403, 'You do not have permission to import tasks for this project.');
        if (empty($this->taskImportPreview)) {
            $this->addError('taskImportFile', 'Upload and preview a valid task file first.');
            return;
        }

        $user = auth()->user();
        $canAssign = $user?->isPmoAdmin() || $this->project->userCan($user, 'task.assign');
        $assigneeValues = collect($this->taskImportPreview)->pluck('assignee')->filter()->map(fn ($value) => strtolower(trim($value)));
        $matchedUsers = User::where(function ($query) use ($assigneeValues) {
            foreach ($assigneeValues as $value) {
                $query->orWhereRaw('LOWER(email) = ?', [$value])->orWhereRaw('LOWER(name) = ?', [$value]);
            }
        })->get();
        $usersByIdentity = collect();
        foreach ($matchedUsers as $matchedUser) {
            $usersByIdentity->put(strtolower($matchedUser->email), $matchedUser);
            $usersByIdentity->put(strtolower($matchedUser->name), $matchedUser);
        }
        $nextSort = (int) (WbsItem::where('project_id', $this->project->id)->whereNull('parent_id')->max('sort_order') ?? 0);

        DB::transaction(function () use ($usersByIdentity, $canAssign, $user, &$nextSort) {
            foreach ($this->taskImportPreview as $row) {
                $assignee = $usersByIdentity->get(strtolower($row['assignee'] ?? ''));
                WbsItem::create([
                    'project_id' => $this->project->id,
                    'parent_id' => null,
                    'item_type' => $row['item_type'] === 'subtask' ? 'task' : $row['item_type'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'assigned_user_id' => $canAssign ? $assignee?->id : null,
                    'start_date' => $row['start_date'],
                    'end_date' => $row['end_date'],
                    'status' => $row['status'],
                    'priority' => $row['priority'],
                    'progress' => $row['progress'],
                    'estimated_hours' => $row['estimated_hours'],
                    'weight' => 1,
                    'is_milestone' => $row['item_type'] === 'milestone',
                    'sort_order' => ++$nextSort,
                    'created_by' => $user?->id,
                ]);
            }
        });

        (new WbsNumberingService())->recalculateProjectWbsCodes($this->project->id);
        $count = count($this->taskImportPreview);
        $this->closeImportModal();
        $this->resetPage();
        $this->dispatch('toast', message: "{$count} tasks imported successfully!", type: 'success');
        $this->dispatch('wbsUpdated');
    }

    private function normalizeImportDate(mixed $value): ?string
    {
        if ($value === null || $value === '') return null;
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }
        return \Carbon\Carbon::parse((string) $value)->format('Y-m-d');
    }

    private function normalizeEstimatedHours(mixed $value): ?float
    {
        if ($value === null || trim((string) $value) === '') return null;
        if (is_numeric($value)) return max(0, (float) $value);
        if (preg_match('/([0-9]+(?:\.[0-9]+)?)\s*(?:day|days|d)\b/i', (string) $value, $matches)) return (float) $matches[1] * 8;
        if (preg_match('/([0-9]+(?:\.[0-9]+)?)\s*(?:hour|hours|hr|hrs|h)\b/i', (string) $value, $matches)) return (float) $matches[1];
        return null;
    }

    private function canonicalImportHeading(string $heading): string
    {
        $key = Str::snake(strtolower(trim($heading)));
        return match ($key) {
            'task', 'task_name', 'task_title', 'activity', 'activity_name', 'deliverable', 'task_deliverable', 'name' => 'title',
            'planned_start', 'start', 'start_date', 'planned_start_date', 'start_schedule', 'planned_start_schedule', 'target_start', 'task_start', 'commencement', 'commencement_date', 'actual_start' => 'start_date',
            'planned_finish', 'finish', 'finish_date', 'planned_finish_date', 'end', 'end_date', 'due', 'due_date', 'deadline', 'target_deadline', 'finish_schedule', 'target_finish', 'task_finish', 'completion_date' => 'end_date',
            'assigned_to', 'assignee', 'assignee_name', 'owner', 'responsible', 'responsible_person', 'resource', 'resource_name', 'resource_names' => 'assignee',
            'percent_complete', 'percentage_complete', 'complete', 'completion', 'progress_percent' => 'progress',
            'reported_duration', 'planned_duration', 'duration_days' => 'duration',
            default => $key,
        };
    }

    private function parseDocxTaskRows(string $path): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            throw new \RuntimeException('The DOCX file could not be opened.');
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();
        if (!$xml) {
            throw new \RuntimeException('The DOCX file does not contain a readable document.');
        }

        $dom = new \DOMDocument();
        $dom->loadXML($xml, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $rows = [];

        // Prefer Word tables. The first row is treated as column headings when
        // it contains a title/task heading; otherwise every first cell is a task title.
        foreach ($xpath->query('//w:tbl') as $table) {
            $tableRows = [];
            foreach ($xpath->query('./w:tr', $table) as $tableRow) {
                $cells = [];
                foreach ($xpath->query('./w:tc', $tableRow) as $cell) {
                    $parts = [];
                    foreach ($xpath->query('.//w:t', $cell) as $textNode) $parts[] = $textNode->textContent;
                    $cells[] = trim(implode(' ', $parts));
                }
                if (array_filter($cells, 'strlen')) $tableRows[] = $cells;
            }
            if (!$tableRows) continue;

            $headings = array_map(fn ($value) => $this->canonicalImportHeading($value), $tableRows[0]);
            $hasHeading = in_array('title', $headings, true);
            if (!$hasHeading) $headings = ['title'];
            foreach (array_slice($tableRows, $hasHeading ? 1 : 0) as $cells) {
                $row = [];
                foreach ($cells as $index => $value) {
                    $heading = $headings[$index] ?? null;
                    if ($heading) $row[$heading] = $value;
                }
                if (filled($row['title'] ?? null)) $rows[] = $row;
            }
        }

        if ($rows) return $rows;

        // For a normal Word document, prefer actual bullet/numbered list items.
        // If the document has no list formatting, accept only short paragraphs;
        // long explanatory prose must never become a database task title.
        $paragraphs = [];
        foreach ($xpath->query('//w:body/w:p') as $paragraph) {
            $parts = [];
            foreach ($xpath->query('.//w:t', $paragraph) as $textNode) $parts[] = $textNode->textContent;
            $title = trim(implode('', $parts));
            $title = preg_replace('/^\s*(?:[-•·]|\d+[.)])\s*/u', '', $title);
            if ($title !== '' && !in_array(strtolower($title), ['task', 'tasks', 'task list'], true)) {
                $paragraphs[] = [
                    'title' => $title,
                    'is_list' => $xpath->query('./w:pPr/w:numPr', $paragraph)->length > 0,
                ];
            }
        }

        $hasFormattedList = collect($paragraphs)->contains(fn ($paragraph) => $paragraph['is_list']);
        foreach ($paragraphs as $paragraph) {
            if ($hasFormattedList && !$paragraph['is_list']) continue;
            if (!$hasFormattedList && mb_strlen($paragraph['title']) > 255) continue;
            $row = $this->parseStructuredTaskLine($paragraph['title']);
            if ($row) $rows[] = $row;
        }

        return $rows;
    }

    private function parseStructuredTaskLine(string $line): ?array
    {
        $segments = preg_split('/\s*[|\t]\s*/u', trim($line));
        $row = [];
        foreach ($segments as $index => $segment) {
            if (preg_match('/^(?:planned\s+start|start(?:\s+(?:date|schedule))?|commencement(?:\s+date)?)\s*:?\s*(.+)$/i', $segment, $m)) $row['start_date'] = trim($m[1]);
            elseif (preg_match('/^(?:planned\s+finish|finish|end(?:\s+date)?|due(?:\s+date)?|deadline)\s*:\s*(.+)$/i', $segment, $m)) $row['end_date'] = trim($m[1]);
            elseif (preg_match('/^(?:reported\s+duration|planned\s+duration|duration)\s*:\s*(.+)$/i', $segment, $m)) $row['duration'] = trim($m[1]);
            elseif (preg_match('/^(?:assigned\s+to|assignee|owner|responsible)\s*:\s*(.+)$/i', $segment, $m)) $row['assignee'] = trim($m[1]);
            elseif (preg_match('/^(?:progress|complete)\s*:\s*(.+)$/i', $segment, $m)) $row['progress'] = trim($m[1]);
            elseif ($index === 0) $row['title'] = trim($segment);
        }

        if (!isset($row['title'])) $row['title'] = trim($line);
        if (preg_match('/^(?:reference\s+project|project\s+implementation\s+plan)\b/i', $row['title'])) return null;
        return filled($row['title']) ? $row : null;
    }

    public function updatedStartDate($value): void
    {
        if ($value && (!$this->end_date || $this->end_date < $value)) {
            $this->end_date = $value;
        }
    }

    public function setTimePreset(string $preset): void
    {
        if ($preset === 'morning') {
            $this->start_time = '08:30';
            $this->end_time   = '12:30';
        } elseif ($preset === 'afternoon') {
            $this->start_time = '12:30';
            $this->end_time   = '17:30';
        } elseif ($preset === 'fullday') {
            $this->start_time = '08:30';
            $this->end_time   = '17:30';
        }

        // If title matches a time range pattern or is default, update title to match preset
        if (empty($this->title) || preg_match('/^\d{1,2}:\d{2}\s*(?:AM|PM)\s*[-–—\s]+\s*\d{1,2}:\d{2}\s*(?:AM|PM)$/iu', trim($this->title)) || str_starts_with($this->title, 'Sub-task')) {
            $sFmt = \Carbon\Carbon::parse($this->start_time)->format('h:i A');
            $eFmt = \Carbon\Carbon::parse($this->end_time)->format('h:i A');
            $this->title = "{$sFmt} – {$eFmt}";
        }
    }

    public function updatedSelectedParentId()
    {
        if (!$this->editingItemId) {
            $this->updateDefaultTitle();
        }
    }

    private function updateDefaultTitle()
    {
        $nextNum = WbsItem::where('project_id', $this->project->id)->where('parent_id', $this->selectedParentId)->count() + 1;
        if ($this->selectedParentId) {
            $parentItem = WbsItem::find($this->selectedParentId);
            $pCode = $parentItem ? $parentItem->wbs_code : '1';
            $this->title = "Sub-task " . $pCode . "." . $nextNum;
            $this->item_type = 'subtask';
        } else {
            $this->title = "Task " . $nextNum;
            $this->item_type = 'task';
        }
    }

    public function openEditItemModal(int $id)
    {
        $item = WbsItem::findOrFail($id);
        abort_if(!$this->canEditSpecificItem($item), 403, 'You do not have permission to edit this task.');

        $this->editingItemId = $item->id;
        $this->selectedParentId = $item->parent_id;
        $this->item_type = $item->item_type->value;
        $this->title = $item->title;
        $this->description = $item->description;
        $this->assigned_user_id = $item->assigned_user_id;
        $this->start_date = $item->start_date?->toDateString();
        $this->start_time = $item->start_time ? \Carbon\Carbon::parse($item->start_time)->format('H:i') : null;
        $this->end_date = $item->end_date?->toDateString();
        $this->end_time = $item->end_time ? \Carbon\Carbon::parse($item->end_time)->format('H:i') : null;
        $this->status = $item->status->value;
        $this->priority = $item->priority->value;
        $this->progress = $item->progress;
        $this->estimated_hours = $item->estimated_hours;
        $this->weight = (float) $item->weight;
        $this->is_milestone = (bool) $item->is_milestone;
        $this->showItemModal = true;
    }

    public function saveItem()
    {
        if ($this->editingItemId) {
            $existingItem = WbsItem::find($this->editingItemId);
            abort_if(!$this->canEditSpecificItem($existingItem), 403, 'You do not have permission to edit this task.');
        } else {
            if ($this->selectedParentId || $this->item_type === 'subtask') {
                abort_if(!$this->canCreateSubtasks, 403, 'You do not have permission to create subtasks for this project.');
            } else {
                abort_if(!$this->canCreateTasks, 403, 'You do not have permission to create tasks for this project.');
            }
        }

        if ($this->start_date && $this->end_date && $this->end_date < $this->start_date) {
            $this->end_date = $this->start_date;
        }

        $this->validate();

        if ($this->selectedParentId) {
            if ($this->editingItemId && $this->selectedParentId === $this->editingItemId) {
                $this->addError('selectedParentId', 'An item cannot be its own parent.');
                return;
            }
        }

        $usr = auth()->user();
        $canAssign = $usr?->isPmoAdmin() || $this->project->userCan($usr, 'task.assign');
        $assigneeId = $canAssign ? ($this->assigned_user_id ?: null) : ($this->editingItemId ? WbsItem::find($this->editingItemId)?->assigned_user_id : null);

        // Auto-sync progress & status
        $status = $this->status;
        $progress = (int) $this->progress;
        if ($progress >= 100 && $status !== 'completed') {
            $status = 'completed';
        } elseif ($status === 'completed' && $progress < 100) {
            $progress = 100;
        }

        $data = [
            'project_id' => $this->project->id,
            'parent_id' => $this->selectedParentId,
            'item_type' => $this->item_type,
            'title' => $this->title,
            'description' => $this->description,
            'assigned_user_id' => $assigneeId,
            'start_date' => $this->start_date ?: null,
            'start_time' => $this->start_time ?: null,
            'end_date' => $this->end_date ?: null,
            'end_time' => $this->end_time ?: null,
            'status' => $status,
            'priority' => $this->priority,
            'progress' => $progress,
            'estimated_hours' => $this->estimated_hours,
            'weight' => $this->weight,
            'is_milestone' => $this->is_milestone || $this->item_type === 'milestone',
        ];

        // Auto-sync title if it was formatted as a time range
        if (preg_match('/^\d{1,2}:\d{2}\s*(?:AM|PM)\s*[-–—\s]+\s*\d{1,2}:\d{2}\s*(?:AM|PM)$/iu', trim($this->title)) && !empty($this->start_time) && !empty($this->end_time)) {
            try {
                $sFmt = \Carbon\Carbon::parse($this->start_time)->format('h:i A');
                $eFmt = \Carbon\Carbon::parse($this->end_time)->format('h:i A');
                $data['title'] = "{$sFmt} – {$eFmt}";
            } catch (\Throwable $e) {}
        }

        // Auto-set status to in_progress if start_date has arrived and status is not_started
        if (!empty($data['start_date']) && $data['start_date'] <= now()->today()->toDateString() && $data['status'] === 'not_started') {
            $data['status'] = 'in_progress';
        }

        if ($this->editingItemId) {
            $item = WbsItem::findOrFail($this->editingItemId);
            $previousAssigneeId = $item->assigned_user_id;

            $oldEndDate   = $item->end_date   ? $item->end_date->copy()   : null;
            $oldStartDate = $item->start_date ? $item->start_date->copy() : null;
            $newEndDate   = !empty($data['end_date']) ? \Carbon\Carbon::parse($data['end_date']) : null;
            $daysDelta    = ($oldEndDate && $newEndDate) ? (int) $oldEndDate->diffInDays($newEndDate, false) : 0;

            // Clear any previous reschedule indicator when user manually edits this task
            $data['rescheduled_shift_days'] = null;
            $data['rescheduled_at']         = null;
            $data['rescheduled_reason']     = null;

            // If deadline changed or task marked complete, reset overdue notification flag
            if ($oldEndDate != $newEndDate || $status === 'completed' || $progress >= 100) {
                $data['overdue_notified_at'] = null;
            }

            $item->update($data);

            // Automatically cascade and shift subsequent sibling task times if this task has times
            \App\Services\WbsScheduleCascadeService::cascadeTimeSlotsForSiblings($item);

            // Notify if newly assigned or assignee changed
            if (!empty($item->assigned_user_id) && $item->assigned_user_id !== $previousAssigneeId && $item->assigned_user_id !== auth()->id()) {
                try {
                    $assignee = User::find($item->assigned_user_id);
                    if ($assignee) {
                        $assignee->notify(new TaskAssignedNotification(
                            taskTitle: $item->title,
                            projectName: $this->project->name,
                            assignedByName: auth()->user()->name,
                            dueDate: $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('M d, Y') : null,
                            url: route('my-tasks.index')
                        ));
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('TaskAssignedNotification error: ' . $e->getMessage());
                }
            }

            (new WbsNumberingService())->recalculateProjectWbsCodes($this->project->id);
            (new ProgressCalculationService())->updateItemProgress($item);
            $this->showItemModal = false;

            // --- Show Cascade Impact Notice Modal if deadline was changed (daysDelta != 0) ---
            if ($daysDelta != 0) {
                $cascadeService = app(\App\Services\ScheduleCascadeService::class);
                $preview        = $cascadeService->calculateImpact($item, $daysDelta);

                if (!empty($preview['affectedTasks'])) {
                    $this->cascadePreview         = $preview;
                    $this->cascadeDaysDelta       = $daysDelta;
                    $this->cascadeSourceTaskId    = $item->id;
                    $this->cascadeRevertEndDate   = $oldEndDate ? $oldEndDate->toDateString() : null;
                    $this->cascadeRevertStartDate = $oldStartDate ? $oldStartDate->toDateString() : null;
                    $this->showCascadeModal       = true;
                    return;
                }
            }

            $this->dispatch('toast', message: 'Task updated successfully!', type: 'success');
            $this->dispatch('wbsUpdated');

        } else {
            $lastSort = WbsItem::where('project_id', $this->project->id)->where('parent_id', $this->selectedParentId)->max('sort_order') ?? 0;
            $data['sort_order'] = $lastSort + 1;
            $data['created_by'] = auth()->id();
            $item = WbsItem::create($data);

            // Automatically cascade and shift subsequent sibling task times if this task has times
            \App\Services\WbsScheduleCascadeService::cascadeTimeSlotsForSiblings($item);

            // Notify assignee upon new task creation
            if (!empty($item->assigned_user_id) && $item->assigned_user_id !== auth()->id()) {
                try {
                    $assignee = User::find($item->assigned_user_id);
                    if ($assignee) {
                        $assignee->notify(new TaskAssignedNotification(
                            taskTitle: $item->title,
                            projectName: $this->project->name,
                            assignedByName: auth()->user()->name,
                            dueDate: $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('M d, Y') : null,
                            url: route('my-tasks.index')
                        ));
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('TaskAssignedNotification error: ' . $e->getMessage());
                }
            }

            (new WbsNumberingService())->recalculateProjectWbsCodes($this->project->id);
            (new ProgressCalculationService())->updateItemProgress($item);
            $this->showItemModal = false;
            $this->dispatch('toast', message: 'Task created successfully!', type: 'success');
            $this->dispatch('wbsUpdated');
        }
    }

    // =========================================================================
    // Cascade Impact Modal Actions
    // =========================================================================

    /**
     * User chose "Update Following Tasks" — apply the cascade reschedule.
     */
    public function confirmCascadeOnly(): void
    {
        if (!$this->cascadeSourceTaskId || $this->cascadeDaysDelta === 0) {
            $this->closeCascadeModal();
            return;
        }

        abort_if(!$this->canManageTasks, 403, 'Only PMO Admins and the designated Project Manager can apply cascade rescheduling.');

        $item = WbsItem::find($this->cascadeSourceTaskId);
        if (!$item) {
            $this->closeCascadeModal();
            return;
        }

        $cascadeService = app(\App\Services\ScheduleCascadeService::class);
        $shiftedTasks   = $cascadeService->applyReschedule($item, $this->cascadeDaysDelta);

        $this->closeCascadeModal();

        $count = count($shiftedTasks);
        $this->dispatch('toast',
            message: "✓ Schedule updated! {$count} dependent " . \Illuminate\Support\Str::plural('task', $count) . " rescheduled (+{$this->cascadeDaysDelta}d) & official Project Deadline updated.",
            type: 'success'
        );
        $this->dispatch('wbsUpdated');
    }

    /**
     * Alias method for confirmCascadeOnly (called from WBS Blade view).
     */
    public function applyCascade(): void
    {
        $this->confirmCascadeOnly();
    }

    /**
     * User chose "Update This Task Only" — keep task's new deadline but do NOT cascade.
     * Show a warning that schedule may now be inconsistent.
     */
    public function confirmThisTaskOnly(): void
    {
        $this->closeCascadeModal();
        $this->dispatch('toast',
            message: '⚠️ Task deadline updated. Following tasks were NOT rescheduled — the project schedule may now have gaps or overlaps.',
            type: 'warning'
        );
        $this->dispatch('wbsUpdated');
    }

    /**
     * User chose "Cancel" — revert the task's own date change.
     */
    public function cancelCascade(): void
    {
        if ($this->cascadeSourceTaskId) {
            $item = WbsItem::find($this->cascadeSourceTaskId);
            if ($item) {
                $item->update([
                    'end_date'   => $this->cascadeRevertEndDate,
                    'start_date' => $this->cascadeRevertStartDate,
                ]);
            }
        }
        $this->closeCascadeModal();
        $this->dispatch('toast', message: 'Task date change cancelled. No changes were made.', type: 'info');
        $this->dispatch('wbsUpdated');
    }

    private function closeCascadeModal(): void
    {
        $this->showCascadeModal      = false;
        $this->cascadePreview        = [];
        $this->cascadeDaysDelta      = 0;
        $this->cascadeSourceTaskId   = null;
        $this->cascadeRevertEndDate  = null;
        $this->cascadeRevertStartDate = null;
    }

    // =========================================================================

    public function updateItemStatus(int $itemId, string $status)
    {
        $item = WbsItem::findOrFail($itemId);

        $user = auth()->user();
        if (!$this->project->userCan($user, 'task.change_status', $item)) {
            $this->dispatch('toast', message: 'You are not authorized to update this task status.', type: 'error');
            return;
        }

        $statusEnum = WbsStatus::tryFrom($status);
        if (!$statusEnum) return;

        $item->status = $statusEnum;
        if ($status === 'completed') {
            $item->progress = 100;

            // Notify PM on completion
            try {
                $pm = $this->project->projectManager;
                if ($pm && $pm->id !== $user->id) {
                    $pm->notify(new TaskCompletedNotification(
                        taskTitle: $item->title,
                        projectName: $this->project->name,
                        completedByName: $user->name,
                        url: route('projects.show', $this->project->id)
                    ));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('TaskCompletedNotification error: ' . $e->getMessage());
            }
        } elseif ($status === 'in_progress' && $item->progress == 0) {
            $item->progress = 10;
        } elseif ($status === 'under_review' && $item->progress < 80) {
            $item->progress = 90;
        } elseif ($status === 'not_started') {
            $item->progress = 0;
        }
        $item->save();

        (new ProgressCalculationService())->updateItemProgress($item);

        $this->dispatch('toast', message: 'Status updated to ' . $statusEnum->label(), type: 'success');
        $this->dispatch('wbsUpdated');
    }

    public function updateLeafProgress(int $itemId, int $newProgress)
    {
        $item = WbsItem::findOrFail($itemId);

        $user = auth()->user();
        if (!$this->project->userCan($user, 'task.update_progress', $item)) {
            $this->dispatch('toast', message: 'You are not authorized to update progress on this task.', type: 'error');
            return;
        }

        $item->progress = max(0, min(100, $newProgress));
        if ($newProgress === 100) {
            $item->status = WbsStatus::COMPLETED;

            // Notify PM on completion
            try {
                $pm = $this->project->projectManager;
                if ($pm && $pm->id !== $user->id) {
                    $pm->notify(new TaskCompletedNotification(
                        taskTitle: $item->title,
                        projectName: $this->project->name,
                        completedByName: $user->name,
                        url: route('projects.show', $this->project->id)
                    ));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('TaskCompletedNotification error: ' . $e->getMessage());
            }
        } elseif ($newProgress > 0 && $item->status->value === 'not_started') {
            $item->status = WbsStatus::IN_PROGRESS;
        }
        $item->save();

        (new ProgressCalculationService())->updateItemProgress($item);

        $this->dispatch('toast', message: 'Progress updated & recalculated!', type: 'success');
        $this->dispatch('wbsUpdated');
    }

    public function deleteItem(int $id)
    {
        $item = WbsItem::find($id);
        if (!$item) return;

        if (!$this->canDeleteSpecificItem($item)) {
            $this->dispatch('toast', message: 'You do not have permission to delete this task.', type: 'error');
            return;
        }

        $this->deleteItemRecursive($item);

        (new WbsNumberingService())->recalculateProjectWbsCodes($this->project->id);
        (new ProgressCalculationService())->updateProjectOverallProgress($this->project->id);

        $this->dispatch('toast', message: 'WBS item deleted successfully.', type: 'success');
        $this->dispatch('wbsUpdated');
    }

    private function deleteItemRecursive(WbsItem $item)
    {
        foreach ($item->children as $child) {
            $this->deleteItemRecursive($child);
        }
        
        WbsDependency::where('predecessor_id', $item->id)->orWhere('successor_id', $item->id)->delete();
        
        $item->delete();
    }

    public function openDepModal(int $successorId)
    {
        $this->depSuccessorId = $successorId;
        $this->depPredecessorId = null;
        $this->showDepModal = true;
    }

    public function addDependency()
    {
        if (!$this->depPredecessorId || !$this->depSuccessorId) return;

        try {
            (new DependencyValidationService())->validateDependency($this->depPredecessorId, $this->depSuccessorId);

            WbsDependency::create([
                'predecessor_id' => $this->depPredecessorId,
                'successor_id' => $this->depSuccessorId,
                'dependency_type' => 'finish_to_start',
            ]);

            $this->showDepModal = false;
            $this->dispatch('toast', message: 'Task dependency added successfully!', type: 'success');
            $this->dispatch('wbsUpdated');
        } catch (InvalidArgumentException $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function removeDependency(int $dependencyId)
    {
        $dep = WbsDependency::find($dependencyId);
        if ($dep) {
            $dep->delete();
            $this->dispatch('toast', message: 'Dependency removed.', type: 'success');
            $this->dispatch('wbsUpdated');
        }
    }

    public function setStatusFilter(string $filter)
    {
        $this->statusFilter = in_array($filter, ['all', 'in_progress', 'at_risk', 'blocked', 'completed', 'not_started']) ? $filter : 'all';
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function setAssigneeFilter(string $filter)
    {
        $this->assigneeFilter = in_array($filter, ['all', 'mine']) ? $filter : 'all';
        $this->resetPage();
    }

    public function updatedAssigneeFilter(): void
    {
        $this->resetPage();
    }

    public function setHealthFilter(string $filter)
    {
        $this->setStatusFilter($filter);
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        $canViewAllTasks = $this->project->userCan($user, 'task.view_all');

        $query = WbsItem::with(['children.children', 'children.assignedUser', 'children.risks', 'assignedUser', 'predecessors.predecessor', 'risks'])
            ->where('project_id', $this->project->id);

        if ($this->assigneeFilter === 'mine') {
            $query->where('assigned_user_id', $user->id);
        } elseif ($canViewAllTasks) {
            $query->whereNull('parent_id');
        } else {
            // Collaborators / Team Members with only task.view_assigned: ONLY show tasks assigned directly to them!
            $query->where('assigned_user_id', $user->id);
        }

        $allProjectItems = WbsItem::with(['risks', 'children'])->where('project_id', $this->project->id)->get();
        
        // Scope for status statistics: filter by user if 'mine' or if user can only view assigned tasks
        if ($this->assigneeFilter === 'mine' || !$canViewAllTasks) {
            $scopedProjectItems = $allProjectItems->where('assigned_user_id', $user->id);
        } else {
            $scopedProjectItems = $allProjectItems;
        }

        // Calculate status statistics across scoped items for the current user view
        $statusStats = [
            'total'       => $scopedProjectItems->count(),
            'in_progress' => 0,
            'at_risk'     => 0,
            'blocked'     => 0,
            'completed'   => 0,
        ];

        foreach ($scopedProjectItems as $item) {
            $st = $item->status?->value ?? 'not_started';
            if (isset($statusStats[$st])) {
                $statusStats[$st]++;
            }
        }

        $wbsItems = $query->orderBy('sort_order')->orderBy('id')->get();

        // Filter items if user selected a specific status filter
        if ($this->statusFilter !== 'all') {
            $wbsItems = $wbsItems->filter(function ($item) {
                if ($item->status?->value === $this->statusFilter) {
                    return true;
                }
                // Also show parent if any of its children match the selected status filter
                return $item->children->contains(function ($child) {
                    return $child->status?->value === $this->statusFilter;
                });
            });
        }

        // Filter out child items whose parent or ancestor is already present in the result list.
        // This prevents duplicate rendering of child tasks (both under their parent and at the root level).
        $allProjectItemsKeyed = $allProjectItems->keyBy('id');
        $wbsItemIds = $wbsItems->pluck('id')->toBase();

        $topLevelWbsItems = $wbsItems->filter(function ($item) use ($wbsItemIds, $allProjectItemsKeyed) {
            $curr = $item;
            while ($curr && $curr->parent_id) {
                if ($wbsItemIds->contains($curr->parent_id)) {
                    return false;
                }
                $curr = $allProjectItemsKeyed->get($curr->parent_id);
            }
            return true;
        });

        // Paginate top-level WBS items
        $currentPage = $this->getPage();
        $paginatedWbsItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $topLevelWbsItems->forPage($currentPage, $this->perPage)->values(),
            $topLevelWbsItems->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $projectMembers = $this->project->members;
        $allWbsItems = $allProjectItems;

        return view('livewire.wbs-tree', [
            'wbsItems' => $paginatedWbsItems,
            'projectMembers' => $projectMembers,
            'allWbsItems' => $allWbsItems,
            'statusStats' => $statusStats,
            'healthStats' => $statusStats,
        ]);
    }
}
