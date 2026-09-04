<?php

namespace App\Observers;

use App\Models\Project;
use App\Services\ProjectScheduleNotificationService;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
    /**
     * In-memory cache of schedule changes pending commit, keyed by object ID.
     */
    private static array $pendingChanges = [];

    /**
     * Handle the Project "updating" event.
     */
    public function updating(Project $project): void
    {
        $oldStartRaw = $project->getRawOriginal('start_date');
        $oldDeadRaw  = $project->getRawOriginal('deadline');

        $newStartRaw = $project->start_date ? (is_object($project->start_date) ? $project->start_date->toDateString() : substr((string) $project->start_date, 0, 10)) : null;
        $newDeadRaw  = $project->deadline ? (is_object($project->deadline) ? $project->deadline->toDateString() : substr((string) $project->deadline, 0, 10)) : null;

        $startDateChanged = ($oldStartRaw !== $newStartRaw);
        $deadlineChanged  = ($oldDeadRaw !== $newDeadRaw);

        if ($startDateChanged || $deadlineChanged) {
            self::$pendingChanges[spl_object_id($project)] = [
                'oldStartDate'      => $oldStartRaw,
                'newStartDate'      => $newStartRaw,
                'oldDeadline'       => $oldDeadRaw,
                'newDeadline'       => $newDeadRaw,
                'startDateChanged'  => $startDateChanged,
                'deadlineChanged'   => $deadlineChanged,
            ];
        }
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $key = spl_object_id($project);
        if (!isset(self::$pendingChanges[$key])) {
            return;
        }

        $change = self::$pendingChanges[$key];
        unset(self::$pendingChanges[$key]);

        // Only trigger when an authenticated user is modifying the schedule
        $editor = Auth::user();
        if (!$editor) {
            return;
        }

        $service = app(ProjectScheduleNotificationService::class);
        $service->notifyPmoAdminsOfDateChange(
            project: $project,
            editor: $editor,
            oldStartDate: $change['oldStartDate'],
            newStartDate: $change['newStartDate'],
            oldDeadline: $change['oldDeadline'],
            newDeadline: $change['newDeadline'],
            startDateChanged: $change['startDateChanged'],
            deadlineChanged: $change['deadlineChanged']
        );
    }
}
