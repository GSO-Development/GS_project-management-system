<?php

namespace App\Services;

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Imports\TaskFileImport;
use App\Models\Project;
use App\Models\ProjectTemplate;
use App\Models\User;
use App\Models\WbsDependency;
use App\Models\WbsItem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Reads the standard task template (Excel/CSV), validates every row against the
 * wbs_items structure and replaces a project's task list with its contents.
 */
class WbsTaskImportService
{
    public const MAX_ROWS = 500;

    /** Column headings of the standard template, in order. */
    public const HEADERS = [
        'number' => 'WBS No',
        'title' => 'Task Name',
        'type' => 'Type',
        'assignee' => 'Assigned To (Email)',
        'start_date' => 'Start Date',
        'start_time' => 'Start Time',
        'end_date' => 'End Date',
        'end_time' => 'End Time',
        'duration' => 'Duration (Days)',
        'status' => 'Status',
        'priority' => 'Priority',
        'progress' => 'Progress %',
        'dependencies' => 'Dependencies',
    ];

    /** Human labels shown in the template dropdowns. */
    public const TYPE_LABELS = ['Phase', 'Task', 'Subtask', 'Milestone'];
    public const STATUS_LABELS = ['Backlog', 'Not Started', 'In Progress', 'At Risk', 'Blocked', 'Under Review', 'Completed', 'On Hold', 'Cancelled'];
    public const PRIORITY_LABELS = ['Low', 'Medium', 'High', 'Critical'];

    /**
     * Parse and validate an uploaded template.
     *
     * @return array{rows: array<int, array<string, mixed>>, errors: array<int, string>}
     */
    public function parse(UploadedFile $file, Project $project): array
    {
        try {
            $sheets = Excel::toArray(new TaskFileImport(), $file);
        } catch (\Throwable $e) {
            report($e);
            return ['rows' => [], 'errors' => ['The file could not be read. Please upload the task template (.xlsx) downloaded from this page.']];
        }

        $sheet = $sheets[0] ?? [];
        if (count($sheet) < 2) {
            return ['rows' => [], 'errors' => ['The file contains no task rows. Fill in the template and upload it again.']];
        }

        $headerRow = array_shift($sheet);
        $columns = [];
        foreach ($headerRow as $position => $heading) {
            $key = $this->headingKey((string) $heading);
            if ($key && !isset($columns[$key])) $columns[$key] = $position;
        }
        $missing = array_diff(['number', 'title'], array_keys($columns));
        if ($missing) {
            $names = collect($missing)->map(fn ($key) => self::HEADERS[$key])->implode(', ');
            return ['rows' => [], 'errors' => ["Required column(s) missing: {$names}. Please download the template and use it without changing the headings."]];
        }

        $members = $this->projectMembers($project);
        $errors = [];
        $rows = [];
        $seen = [];

        foreach ($sheet as $offset => $line) {
            $excelRow = $offset + 2;
            $raw = [];
            $supportedKeys = array_unique(array_merge(array_keys(self::HEADERS), ['description', 'estimated_hours']));
            foreach ($supportedKeys as $key) {
                $raw[$key] = isset($columns[$key]) ? $this->cell($line[$columns[$key]] ?? null, $key) : '';
            }
            if ($this->isBlankRow($raw)) continue;

            if (count($rows) >= self::MAX_ROWS) {
                $errors[] = 'The file has more than '.self::MAX_ROWS.' tasks. Split it into smaller files.';
                break;
            }

            $rowErrors = [];
            $number = $this->normalizeNumber($raw['number']);
            if ($number === null) {
                $rowErrors[] = $raw['number'] === ''
                    ? 'WBS No is required (e.g. 1, 1.1, 1.2.1).'
                    : "WBS No '{$raw['number']}' is invalid. Use numbers separated by dots, e.g. 1, 1.1, 1.2.1.";
            } elseif (isset($seen[$number])) {
                $rowErrors[] = "WBS No {$number} is already used in row {$seen[$number]}.";
            }

            if ($raw['title'] === '') $rowErrors[] = 'Task Name is required.';
            elseif (mb_strlen($raw['title']) > 255) $rowErrors[] = 'Task Name must be 255 characters or fewer.';

            $row = [
                'row' => $excelRow,
                'number' => $number ?? $raw['number'],
                'parent_number' => $number !== null && str_contains($number, '.') ? substr($number, 0, strrpos($number, '.')) : null,
                'depth' => $number !== null ? substr_count($number, '.') : 0,
                'title' => $raw['title'],
                'description' => ($raw['description'] ?? '') !== '' ? $raw['description'] : null,
            ];

            // Type
            $type = null;
            if ($raw['type'] !== '') {
                $type = $this->enumValue($raw['type'], array_map(fn (ItemType $case) => $case->value, ItemType::cases()));
                if ($type === null) $rowErrors[] = "Type '{$raw['type']}' is not valid. Use Phase, Task, Subtask or Milestone.";
            }
            $row['type'] = $type;

            // Assignee
            $row['assigned_user_id'] = null;
            $row['assignee'] = null;
            if ($raw['assignee'] !== '') {
                $member = $this->matchMember($members, $raw['assignee']);
                if ($member) {
                    $row['assigned_user_id'] = $member->id;
                    $row['assignee'] = $member->name;
                } else {
                    $rowErrors[] = "'{$raw['assignee']}' is not a member of this project. Use a project member's email address.";
                }
            }

            // Dates, times, duration
            $row['start_date'] = $this->tryParse(fn () => $this->parseDate($raw['start_date']), $rowErrors, 'Start Date');
            $row['end_date'] = $this->tryParse(fn () => $this->parseDate($raw['end_date']), $rowErrors, 'End Date');
            $row['start_time'] = $this->tryParse(fn () => $this->parseTime($raw['start_time']), $rowErrors, 'Start Time');
            $row['end_time'] = $this->tryParse(fn () => $this->parseTime($raw['end_time']), $rowErrors, 'End Time');

            $row['duration'] = null;
            if ($raw['duration'] !== '') {
                if (is_numeric($raw['duration']) && (float) $raw['duration'] >= 1) $row['duration'] = (int) ceil((float) $raw['duration']);
                else $rowErrors[] = "Duration '{$raw['duration']}' must be a whole number of days (1 or more).";
            }
            if ($row['start_date'] && !$row['end_date'] && $row['duration']) {
                $row['end_date'] = \Carbon\Carbon::parse($row['start_date'])->addDays($row['duration'] - 1)->toDateString();
            }
            if ($row['start_date'] && $row['end_date'] && $row['end_date'] < $row['start_date']) {
                $rowErrors[] = 'End Date must be on or after Start Date.';
            }
            if ($row['start_date'] && $row['end_date'] && $row['start_date'] === $row['end_date']
                && $row['start_time'] && $row['end_time'] && $row['end_time'] < $row['start_time']) {
                $rowErrors[] = 'End Time must be after Start Time on the same day.';
            }

            // Status / priority
            $status = $raw['status'] === '' ? 'not_started'
                : $this->enumValue($raw['status'], array_map(fn (WbsStatus $case) => $case->value, WbsStatus::cases()));
            if ($status === null) $rowErrors[] = "Status '{$raw['status']}' is not valid. Use one of: ".implode(', ', self::STATUS_LABELS).'.';
            $row['status'] = $status ?? 'not_started';

            $priority = $raw['priority'] === '' ? 'medium'
                : $this->enumValue($raw['priority'], array_map(fn (Priority $case) => $case->value, Priority::cases()));
            if ($priority === null) $rowErrors[] = "Priority '{$raw['priority']}' is not valid. Use Low, Medium, High or Critical.";
            $row['priority'] = $priority ?? 'medium';

            // Progress
            $progress = 0;
            if ($raw['progress'] !== '') {
                $clean = rtrim(str_replace(',', '.', $raw['progress']), '% ');
                if (is_numeric($clean) && (float) $clean >= 0 && (float) $clean <= 100) {
                    $value = (float) $clean;
                    $progress = (int) round(($value > 0 && $value < 1 && !str_contains($raw['progress'], '%')) ? $value * 100 : $value);
                } else {
                    $rowErrors[] = "Progress '{$raw['progress']}' must be a number between 0 and 100.";
                }
            } elseif ($row['status'] === 'completed') {
                $progress = 100;
            }
            $row['progress'] = $progress;

            // Estimated hours
            $row['estimated_hours'] = null;
            if ($raw['estimated_hours'] !== '') {
                if (is_numeric($raw['estimated_hours']) && (float) $raw['estimated_hours'] >= 0) $row['estimated_hours'] = (float) $raw['estimated_hours'];
                else $rowErrors[] = "Estimated Hours '{$raw['estimated_hours']}' must be a number.";
            }

            // Dependencies (validated against the whole file below)
            $row['dependencies'] = [];
            if ($raw['dependencies'] !== '') {
                foreach (preg_split('/[,;]+/', $raw['dependencies']) as $reference) {
                    $normalized = $this->normalizeNumber($reference);
                    if ($normalized === null) $rowErrors[] = "Dependency '".trim($reference)."' is not a valid WBS No.";
                    else $row['dependencies'][] = $normalized;
                }
                $row['dependencies'] = array_values(array_unique($row['dependencies']));
            }

            foreach ($rowErrors as $message) $errors[] = $this->prefix($row, $message);
            if ($number !== null) $seen[$number] = $excelRow;
            $rows[] = $row;
        }

        if (!$rows && !$errors) {
            return ['rows' => [], 'errors' => ['No tasks were found. Enter a Task Name for each task you want to create.']];
        }

        // Structure checks across the whole file.
        $byNumber = collect($rows)->filter(fn ($row) => $this->normalizeNumber($row['number']) !== null)->keyBy('number');
        foreach ($rows as $row) {
            if ($row['parent_number'] !== null && !$byNumber->has($row['parent_number'])) {
                $errors[] = $this->prefix($row, "Parent task {$row['parent_number']} is missing. Add it (with a Task Name) or renumber this task.");
            }
            foreach ($row['dependencies'] as $reference) {
                if ($reference === $row['number']) $errors[] = $this->prefix($row, 'A task cannot depend on itself.');
                elseif (!$byNumber->has($reference)) $errors[] = $this->prefix($row, "Dependency {$reference} does not exist in this file.");
            }
        }

        usort($rows, fn ($a, $b) => $this->compareNumbers((string) $a['number'], (string) $b['number']));

        return ['rows' => $rows, 'errors' => array_values(array_unique($errors))];
    }

    /**
     * Rows (in template column order) that pre-fill the downloadable template from a
     * project template: WBS numbers follow the template hierarchy (1, 1.1, 1.2, 2 ...).
     *
     * @return array<int, array<int, mixed>>
     */
    public function templateRows(?ProjectTemplate $template): array
    {
        if (!$template || $template->tasks->isEmpty()) return [];

        $children = $template->tasks->sortBy('order_index')
            ->groupBy(fn ($task) => $task->parent_id ? (string) $task->parent_id : 'root');
        $rows = [];
        $walk = function (string $parentKey, ?string $parentCode, int $depth) use (&$walk, &$rows, $children) {
            foreach (($children->get($parentKey) ?? collect())->values() as $index => $task) {
                $code = $parentCode === null ? (string) ($index + 1) : $parentCode.'.'.($index + 1);
                $type = $depth > 0 ? 'Subtask' : ($children->has((string) $task->id) ? 'Phase' : 'Task');
                $rows[] = [
                    $code, $task->name, $type, null, null, null, null, null,
                    TemplateWbsGeneratorService::getDurationInDays($task->duration, $task->unit),
                    'Not Started', 'Medium', 0, null,
                ];
                $walk((string) $task->id, $code, $depth + 1);
            }
        };
        $walk('root', null, 0);

        return $rows;
    }

    /**
     * Replace every task of the project with the validated rows, atomically.
     *
     * @param array<int, array<string, mixed>> $rows Rows returned by parse() (error free).
     * @return array{created: int, replaced: int, dependencies_skipped: int}
     */
    public function replace(Project $project, array $rows, ?User $user, bool $canAssign): array
    {
        $skippedDependencies = 0;

        $result = DB::transaction(function () use ($project, $rows, $user, $canAssign, &$skippedDependencies) {
            $oldIds = WbsItem::where('project_id', $project->id)->pluck('id');
            if ($oldIds->isNotEmpty()) {
                WbsDependency::whereIn('predecessor_id', $oldIds)->orWhereIn('successor_id', $oldIds)->delete();
                WbsItem::where('project_id', $project->id)->delete();
            }

            $hasChildren = collect($rows)->pluck('parent_number')->filter()->flip();
            $siblingCounter = [];
            $items = [];

            foreach ($rows as $row) {
                $parentKey = $row['parent_number'] ?? '';
                $siblingCounter[$parentKey] = ($siblingCounter[$parentKey] ?? 0) + 1;

                $type = $row['type'] ?? ($row['parent_number'] !== null
                    ? ItemType::SUBTASK->value
                    : ($hasChildren->has($row['number']) ? ItemType::PHASE->value : ItemType::TASK->value));

                $items[$row['number']] = WbsItem::create([
                    'project_id' => $project->id,
                    'parent_id' => $row['parent_number'] !== null ? $items[$row['parent_number']]->id : null,
                    'wbs_code' => $row['number'],
                    'item_type' => $type,
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'assigned_user_id' => $canAssign ? $row['assigned_user_id'] : null,
                    'source_assignee' => null,
                    'start_date' => $row['start_date'],
                    'start_time' => $row['start_time'],
                    'end_date' => $row['end_date'],
                    'end_time' => $row['end_time'],
                    'duration' => $row['duration'],
                    'status' => $row['status'],
                    'priority' => $row['priority'],
                    'progress' => $row['progress'],
                    'estimated_hours' => $row['estimated_hours'],
                    'weight' => 1,
                    'is_milestone' => $type === ItemType::MILESTONE->value,
                    'sort_order' => $siblingCounter[$parentKey],
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);
            }

            // Containers without their own dates span their children's dates.
            $childrenOf = collect($rows)->groupBy('parent_number');
            $parents = collect($rows)->filter(fn ($row) => $hasChildren->has($row['number']))->sortByDesc('depth');
            foreach ($parents as $row) {
                $parent = $items[$row['number']]->fresh();
                $children = collect($childrenOf[$row['number']] ?? [])->map(fn ($child) => $items[$child['number']]->fresh());
                $starts = $children->pluck('start_date')->filter()->sort()->values();
                $ends = $children->pluck('end_date')->filter()->sort()->values();
                $updates = [];
                if (!$parent->start_date && $starts->isNotEmpty()) $updates['start_date'] = $starts->first()->toDateString();
                if (!$parent->end_date && $ends->isNotEmpty()) $updates['end_date'] = $ends->last()->toDateString();
                if ($updates) $parent->forceFill($updates)->saveQuietly();
            }

            foreach ($rows as $row) {
                $successor = $items[$row['number']];
                foreach ($row['dependencies'] as $reference) {
                    $predecessor = $items[$reference] ?? null;
                    if (!$predecessor) continue;
                    try {
                        (new DependencyValidationService())->validateDependency($predecessor->id, $successor->id);
                        WbsDependency::create([
                            'predecessor_id' => $predecessor->id,
                            'successor_id' => $successor->id,
                            'dependency_type' => 'finish_to_start',
                            'lag_days' => 0,
                        ]);
                    } catch (\Throwable $e) {
                        $skippedDependencies++;
                    }
                }
            }

            (new WbsNumberingService())->recalculateProjectWbsCodes($project->id);

            // Roll leaf progress up through the parents, then the project.
            $progress = new ProgressCalculationService();
            foreach ($items as $item) {
                if ($item->parent_id) $progress->updateItemProgress($item->fresh());
            }
            $progress->updateProjectOverallProgress($project->id);

            return ['created' => count($rows), 'replaced' => $oldIds->count()];
        });

        return $result + ['dependencies_skipped' => $skippedDependencies];
    }

    private function headingKey(string $heading): ?string
    {
        $normalized = preg_replace('/[^a-z0-9#]/', '', strtolower(trim($heading)));
        return match ($normalized) {
            '#', 'no', 'number', 'wbsno', 'wbsnumber', 'wbscode', 'wbs', 'taskno', 'tasknumber' => 'number',
            'taskname', 'task', 'title', 'name', 'taskdeliverable', 'deliverable', 'activity' => 'title',
            'type', 'itemtype' => 'type',
            'assignedtoemail', 'assignedto', 'assignee', 'assigneeemail', 'owner', 'responsible' => 'assignee',
            'startdate', 'start', 'plannedstart' => 'start_date',
            'starttime' => 'start_time',
            'enddate', 'end', 'finishdate', 'duedate', 'deadline', 'plannedfinish' => 'end_date',
            'endtime', 'finishtime', 'duetime' => 'end_time',
            'durationdays', 'duration' => 'duration',
            'status' => 'status',
            'priority' => 'priority',
            'progress', 'progress%', 'percentcomplete' => 'progress',
            'estimatedhours', 'estimatehours', 'hours' => 'estimated_hours',
            'description', 'notes' => 'description',
            'dependencies', 'dependson', 'predecessors' => 'dependencies',
            default => null,
        };
    }

    private function cell(mixed $value, string $key): string
    {
        if ($value === null) return '';
        if (is_bool($value)) return $value ? '1' : '0';
        if (is_int($value) || is_float($value)) {
            // Keep numeric cells such as WBS "1" or "1.1" readable; dates stay as serial numbers.
            if (in_array($key, ['start_date', 'end_date', 'start_time', 'end_time'], true)) return (string) $value;
            return rtrim(rtrim(number_format((float) $value, 10, '.', ''), '0'), '.');
        }
        return trim((string) preg_replace('/\s+/u', ' ', (string) $value));
    }

    private function isBlankRow(array $raw): bool
    {
        foreach ($raw as $key => $value) {
            if ($key === 'number' || $value === '') continue;
            $defaults = ['status' => 'not started', 'priority' => 'medium', 'progress' => '0'];
            if (isset($defaults[$key]) && strtolower($value) === $defaults[$key]) continue;
            return false;
        }
        return true;
    }

    private function normalizeNumber(string $value): ?string
    {
        $value = trim(preg_replace('/^#\s*/', '', trim($value)));
        if (!preg_match('/^\d+(\.\d+)*$/', $value)) return null;
        // Remove leading zeros of each segment so 01.02 equals 1.2.
        return implode('.', array_map(fn ($part) => (string) (int) $part, explode('.', $value)));
    }

    private function compareNumbers(string $a, string $b): int
    {
        $left = array_map('intval', explode('.', $a));
        $right = array_map('intval', explode('.', $b));
        foreach (range(0, max(count($left), count($right)) - 1) as $index) {
            $diff = ($left[$index] ?? -1) <=> ($right[$index] ?? -1);
            if ($diff !== 0) return $diff;
        }
        return 0;
    }

    private function enumValue(string $input, array $allowed): ?string
    {
        $candidate = preg_replace('/[\s\-]+/', '_', strtolower(trim($input)));
        return in_array($candidate, $allowed, true) ? $candidate : null;
    }

    private function prefix(array $row, string $message): string
    {
        $label = ($row['number'] !== '' ? $row['number'].' ' : '').($row['title'] !== '' ? '"'.$row['title'].'"' : '');
        return "Row {$row['row']}".(trim($label) !== '' ? ' ('.trim($label).')' : '').": {$message}";
    }

    private function tryParse(callable $parser, array &$errors, string $label): ?string
    {
        try {
            return $parser();
        } catch (\InvalidArgumentException $e) {
            $errors[] = "{$label}: ".$e->getMessage();
            return null;
        }
    }

    private function parseDate(string $value): ?string
    {
        if ($value === '') return null;
        if (is_numeric($value)) {
            if ((float) $value < 1) throw new \InvalidArgumentException("'{$value}' is not a valid date.");
            return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }
        // Drop a trailing time portion (e.g. "2026-09-21 08:30 AM").
        $date = trim((string) preg_replace('/[\sT]+\d{1,2}:\d{2}(:\d{2})?\s*([AP]M)?$/i', '', $value));
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'Y/m/d', 'd M Y', 'j M Y', 'M j, Y', 'F j, Y', 'j F Y'] as $format) {
            $parsed = \DateTimeImmutable::createFromFormat('!'.$format, $date);
            $problems = \DateTimeImmutable::getLastErrors();
            if ($parsed && (!$problems || ($problems['warning_count'] === 0 && $problems['error_count'] === 0))) {
                return $parsed->format('Y-m-d');
            }
        }
        throw new \InvalidArgumentException("'{$value}' is not a valid date. Use YYYY-MM-DD, e.g. 2026-10-27.");
    }

    private function parseTime(string $value): ?string
    {
        if ($value === '') return null;
        if (is_numeric($value)) {
            $fraction = (float) $value - floor((float) $value);
            if ((float) $value >= 0 && ($fraction > 0 || (float) $value == 0)) {
                return ExcelDate::excelToDateTimeObject($fraction)->format('H:i:s');
            }
            throw new \InvalidArgumentException("'{$value}' is not a valid time.");
        }
        foreach (['H:i', 'H:i:s', 'G:i', 'g:i A', 'g:iA', 'h:i A', 'g:i a', 'g:ia'] as $format) {
            $parsed = \DateTimeImmutable::createFromFormat('!'.$format, $value);
            $problems = \DateTimeImmutable::getLastErrors();
            if ($parsed && (!$problems || ($problems['warning_count'] === 0 && $problems['error_count'] === 0))) {
                return $parsed->format('H:i:s');
            }
        }
        throw new \InvalidArgumentException("'{$value}' is not a valid time. Use HH:MM, e.g. 08:30 or 17:30.");
    }

    private function projectMembers(Project $project)
    {
        return $project->members()->get()
            ->push($project->projectManager)
            ->filter()
            ->unique('id')
            ->values();
    }

    private function matchMember($members, string $identity): ?User
    {
        $needle = mb_strtolower(trim($identity));
        $matches = $members->filter(fn ($member) => in_array($needle, array_filter([
            mb_strtolower((string) $member->email),
            mb_strtolower((string) $member->name),
            mb_strtolower((string) ($member->username ?? '')),
        ]), true));

        return $matches->count() === 1 ? $matches->first() : null;
    }
}
