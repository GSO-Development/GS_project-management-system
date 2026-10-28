<?php

namespace App\Services;

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\ProjectTemplate;
use App\Models\WbsItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TemplateWbsGeneratorService
{
    /**
     * Generate WBS tasks and subtasks from a ProjectTemplate.
     *
     * @param Project $project
     * @param int $templateId
     * @param Carbon|null $startDate
     * @param bool $replaceExisting If true, deletes existing WBS items before generating.
     * @return int Number of tasks created
     */
    public function generate(Project $project, int $templateId, ?Carbon $startDate = null, bool $replaceExisting = false): int
    {
        $template = ProjectTemplate::with('tasks')->findOrFail($templateId);
        $startDate = $startDate ? $startDate->copy()->startOfDay() : ($project->start_date ? Carbon::parse($project->start_date)->startOfDay() : now()->startOfDay());

        return DB::transaction(function () use ($project, $template, $startDate, $replaceExisting) {
            if ($replaceExisting) {
                WbsItem::withTrashed()->where('project_id', $project->id)->update(['parent_id' => null]);
                WbsItem::withTrashed()->where('project_id', $project->id)->forceDelete();
            }

            $templateTasks = $template->tasks;
            if ($templateTasks->isEmpty()) {
                return 0;
            }

            // Group tasks by parent_id (handle both integer and string keys)
            $tasksByParent = $templateTasks->groupBy(function ($task) {
                return $task->parent_id ? (string) $task->parent_id : 'root';
            });

            $nextSort = (int) (WbsItem::where('project_id', $project->id)->whereNull('parent_id')->max('sort_order') ?? 0);
            $createdCount = 0;

            $this->createBranch(
                $project,
                $tasksByParent,
                'root',
                $startDate,
                null,
                $createdCount,
                $nextSort
            );

            // Cascade hierarchical schedule dates (Days, Weeks, Months, Hours)
            WbsScheduleCascadeService::cascadeProjectSchedule($project->id, true);

            // Recalculate WBS numbering and progress
            (new WbsNumberingService())->recalculateProjectWbsCodes($project->id);
            (new ProgressCalculationService())->updateProjectOverallProgress($project->id);

            return $createdCount;
        });
    }

    private function createBranch(
        Project $project,
        $tasksByParent,
        string $parentIdKey,
        Carbon $currentStartDate,
        ?int $parentWbsItemId,
        int &$createdCount,
        int &$nextSort
    ): Carbon {
        $tasks = $tasksByParent->get($parentIdKey)?->sortBy('order_index');
        if (!$tasks || $tasks->isEmpty()) {
            return $currentStartDate;
        }

        $lastEndDate = $currentStartDate->copy();

        foreach ($tasks as $task) {
            $durationInDays = self::getDurationInDays($task->duration, $task->unit);
            $taskStartDate = $lastEndDate->copy();
            $taskEndDate = $taskStartDate->copy()->addDays(max(1, $durationInDays))->subDay();

            // Check if this task has subtasks
            $hasSubtasks = $tasksByParent->has((string) $task->id) && $tasksByParent->get((string) $task->id)->isNotEmpty();

            // If parentWbsItemId is set: it is a subtask
            // If parentWbsItemId is null:
            //   If it has subtasks, it is a phase / container
            //   If it does NOT have subtasks, it is a standalone task
            if ($parentWbsItemId) {
                $itemType = ItemType::SUBTASK->value;
            } else {
                $itemType = $hasSubtasks ? ItemType::PHASE->value : ItemType::TASK->value;
            }

            $wbsItem = WbsItem::create([
                'project_id'       => $project->id,
                'parent_id'        => $parentWbsItemId,
                'wbs_code'         => 'TEMP',
                'item_type'        => $itemType,
                'title'            => $task->name ?? 'Task',
                'description'      => null,
                'duration'         => $durationInDays,
                'start_date'       => $taskStartDate->toDateString(),
                'end_date'         => $taskEndDate->toDateString(),
                'status'           => WbsStatus::NOT_STARTED->value,
                'priority'         => Priority::MEDIUM->value,
                'progress'         => 0,
                'sort_order'       => $parentWbsItemId ? (int) ($task->order_index ?? 0) : ++$nextSort,
                'is_milestone'     => false,
                'created_by'       => auth()->id(),
                'assigned_user_id' => null,
                'source_assignee'  => null,
            ]);

            $createdCount++;

            // If this task has subtasks, recursively create them
            // If it does NOT have subtasks, no subtasks are created
            if ($hasSubtasks) {
                $childEndDate = $this->createBranch(
                    $project,
                    $tasksByParent,
                    (string) $task->id,
                    $taskStartDate,
                    $wbsItem->id,
                    $createdCount,
                    $nextSort
                );

                if ($childEndDate->gt($taskEndDate)) {
                    $taskEndDate = $childEndDate;
                    $wbsItem->update(['end_date' => $taskEndDate->toDateString()]);
                }
            }

            $lastEndDate = $taskEndDate->copy()->addDay();
        }

        return $lastEndDate;
    }

    public static function getDurationInDays($duration, $unit): int
    {
        $duration = max(1, (int) $duration);
        if ($unit === 'weeks') {
            return $duration * 7;
        } elseif ($unit === 'months') {
            return $duration * 30;
        }
        return $duration;
    }
}
