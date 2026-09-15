<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Models\WbsItem;
use App\Notifications\TaskOverdueAlertNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskOverdueNotificationService
{
    /**
     * Check for overdue tasks and send notifications + emails to PMO Admins and Project Leaders.
     *
     * @param int|null $projectId Optional project ID to limit check
     * @return int Number of overdue alerts dispatched
     */
    public static function checkAndNotifyOverdueTasks(?int $projectId = null): int
    {
        $now = Carbon::now();

        $query = WbsItem::with(['project.projectManager', 'assignedUser'])
            ->whereNull('overdue_notified_at')
            ->whereNotNull('end_date')
            ->whereNotIn('status', ['completed'])
            ->where('progress', '<', 100);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $items = $query->limit(5)->get();
        if ($items->isEmpty()) {
            return 0;
        }

        // Retrieve all active PMO Admins and Super Admins
        $pmoAdmins = User::where('is_active', true)
            ->where(function ($q) {
                $q->whereHas('roles', function ($rq) {
                    $rq->whereIn('name', ['pmo_admin', 'super_admin']);
                })
                ->orWhere('email', 'admin@nexuspm.local')
                ->orWhere('email', 'superadmin@georgesteuart.com');
            })
            ->get();

        $alertCount = 0;

        foreach ($items as $item) {
            /** @var \App\Models\WbsItem $item */
            $endDateTime = null;
            $eDateStr = $item->end_date instanceof Carbon ? $item->end_date->format('Y-m-d') : substr((string) $item->end_date, 0, 10);
            $eTimeStr = $item->end_time ?: '23:59:59';
            try {
                $endDateTime = Carbon::parse("{$eDateStr} {$eTimeStr}");
            } catch (\Throwable $e) {
                $endDateTime = Carbon::parse($eDateStr)->endOfDay();
            }

            // Check if deadline has actually passed
            if ($endDateTime && $now->greaterThan($endDateTime)) {
                // Immediately mark task as notified to prevent duplicate processing
                $item->updateQuietly([
                    'overdue_notified_at' => $now,
                ]);

                $diffHours = (int) $endDateTime->diffInHours($now);
                $diffDays = (int) $endDateTime->diffInDays($now);

                if ($diffDays > 0) {
                    $overdueText = "Overdue by {$diffDays} " . ($diffDays === 1 ? 'day' : 'days');
                } elseif ($diffHours > 0) {
                    $overdueText = "Overdue by {$diffHours} " . ($diffHours === 1 ? 'hour' : 'hours');
                } else {
                    $diffMins = max(1, (int) $endDateTime->diffInMinutes($now));
                    $overdueText = "Overdue by {$diffMins} minutes";
                }

                $project = $item->project;
                $projectName = $project ? $project->name : 'Project';
                $assignedName = $item->assignedUser ? $item->assignedUser->name : 'Unassigned';
                $deadlineFormatted = $item->end_date ? $item->end_date->format('M d, Y') : null;
                $dueTimeFormatted = $item->end_time_formatted;

                // Build recipient list (PMO Admins + Project Leader)
                $recipients = collect($pmoAdmins);
                if ($project && $project->projectManager && $project->projectManager->is_active) {
                    $recipients->push($project->projectManager);
                }
                $recipients = $recipients->unique('id');

                $notification = new TaskOverdueAlertNotification(
                    taskTitle: $item->title,
                    projectName: $projectName,
                    projectId: $item->project_id,
                    assignedUserName: $assignedName,
                    deadlineFormatted: $deadlineFormatted,
                    dueTimeFormatted: $dueTimeFormatted,
                    progress: (int) $item->progress,
                    overdueDuration: $overdueText,
                    url: route('projects.show', $item->project_id)
                );

                // Dispatch in-app system notifications to PMO Admins & Project Manager
                foreach ($recipients as $recipient) {
                    try {
                        $recipient->notify($notification);
                    } catch (\Throwable $e) {
                        Log::warning("Failed to dispatch in-app TaskOverdueAlertNotification to user #{$recipient->id}: " . $e->getMessage());
                    }
                }

                // Dispatch emails EXCLUSIVELY to the recipient email address(es) configured in Admin Settings when toggle is Enabled
                $enableEmailAlerts = filter_var(
                    \App\Models\SystemSetting::where('key', 'enable_overdue_email_alerts')->value('value') ?? true,
                    FILTER_VALIDATE_BOOLEAN
                );
                $overdueAlertEmailStr = trim((string) \App\Models\SystemSetting::where('key', 'overdue_notification_email')->value('value'));

                if ($enableEmailAlerts && !empty($overdueAlertEmailStr)) {
                    $targetEmails = array_map('trim', preg_split('/[,;]+/', $overdueAlertEmailStr));
                    foreach ($targetEmails as $targetEmail) {
                        if (!empty($targetEmail) && filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
                            try {
                                \Illuminate\Support\Facades\Notification::route('mail', $targetEmail)->notify($notification);
                            } catch (\Throwable $e) {
                                Log::warning("Failed to dispatch overdue alert email to {$targetEmail}: " . $e->getMessage());
                            }
                        }
                    }
                }

                $alertCount++;
            } else {
                // If not overdue, skip
            }
        }

        return $alertCount;
    }
}
