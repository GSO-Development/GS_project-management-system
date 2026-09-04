<?php

namespace App\Services;

use App\Mail\ProjectScheduleChangedMail;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectScheduleChangedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProjectScheduleNotificationService
{
    /**
     * Notify all PMO Admins via In-App Notification and Email when project schedule dates change.
     */
    public function notifyPmoAdminsOfDateChange(
        Project $project,
        User $editor,
        ?string $oldStartDate,
        ?string $newStartDate,
        ?string $oldDeadline,
        ?string $newDeadline,
        bool $startDateChanged = false,
        bool $deadlineChanged = false
    ): void {
        if (!$startDateChanged && !$deadlineChanged) {
            return;
        }

        // Calculate variance text
        $varianceParts = [];
        if ($deadlineChanged && $oldDeadline && $newDeadline) {
            try {
                $daysDiff = (int) Carbon::parse($oldDeadline)->diffInDays(Carbon::parse($newDeadline), false);
                if ($daysDiff > 0) {
                    $varianceParts[] = "Deadline extended by {$daysDiff} " . ($daysDiff === 1 ? 'day' : 'days');
                } elseif ($daysDiff < 0) {
                    $absDays = abs($daysDiff);
                    $varianceParts[] = "Deadline moved earlier by {$absDays} " . ($absDays === 1 ? 'day' : 'days');
                }
            } catch (\Throwable $e) {}
        }

        if ($startDateChanged && $oldStartDate && $newStartDate) {
            try {
                $daysDiff = (int) Carbon::parse($oldStartDate)->diffInDays(Carbon::parse($newStartDate), false);
                if ($daysDiff > 0) {
                    $varianceParts[] = "Start date shifted forward by {$daysDiff} " . ($daysDiff === 1 ? 'day' : 'days');
                } elseif ($daysDiff < 0) {
                    $absDays = abs($daysDiff);
                    $varianceParts[] = "Start date moved earlier by {$absDays} " . ($absDays === 1 ? 'day' : 'days');
                }
            } catch (\Throwable $e) {}
        }

        $varianceText = !empty($varianceParts) ? implode(' • ', $varianceParts) : null;

        // Retrieve all active PMO Admins (super_admin & pmo_admin roles)
        $pmoAdmins = User::where('is_active', true)
            ->where(function ($q) {
                $q->whereHas('roles', fn ($rq) => $rq->whereIn('name', ['super_admin', 'pmo_admin']))
                  ->orWhere('email', 'admin@nexuspm.local')
                  ->orWhere('email', 'superadmin@georgesteuart.com');
            })
            ->get()
            ->unique('id');

        // Exclude the editor from receiving duplicate self-notifications if the editor is already a PMO Admin
        $targetAdmins = $pmoAdmins->where('id', '!=', $editor->id);
        if ($targetAdmins->isEmpty() && $pmoAdmins->isNotEmpty()) {
            // If the only PMO admin is the editor, send to them so test/single-admin environments still record notification
            $targetAdmins = $pmoAdmins;
        }

        foreach ($targetAdmins as $admin) {
            // 1. Send Database Notification (for Bell icon and Notification center)
            try {
                $admin->notify(new ProjectScheduleChangedNotification(
                    project: $project,
                    editor: $editor,
                    oldStartDate: $oldStartDate,
                    newStartDate: $newStartDate,
                    oldDeadline: $oldDeadline,
                    newDeadline: $newDeadline,
                    startDateChanged: $startDateChanged,
                    deadlineChanged: $deadlineChanged,
                    varianceText: $varianceText
                ));
            } catch (\Throwable $e) {
                Log::error("Failed to dispatch ProjectScheduleChangedNotification to user #{$admin->id}: " . $e->getMessage());
            }

            // 2. Send Executive Branded Email
            try {
                if (!empty($admin->email)) {
                    Mail::to($admin->email)->send(new ProjectScheduleChangedMail(
                        project: $project,
                        editor: $editor,
                        oldStartDate: $oldStartDate,
                        newStartDate: $newStartDate,
                        oldDeadline: $oldDeadline,
                        newDeadline: $newDeadline,
                        startDateChanged: $startDateChanged,
                        deadlineChanged: $deadlineChanged,
                        varianceText: $varianceText
                    ));
                }
            } catch (\Throwable $e) {
                Log::error("Failed to send ProjectScheduleChangedMail to {$admin->email}: " . $e->getMessage());
            }
        }

        // 3. Log Activity for PMO Governance Audit
        try {
            ActivityLog::create([
                'user_id' => $editor->id,
                'action' => 'updated_project_schedule',
                'module' => 'projects',
                'record_type' => Project::class,
                'record_id' => $project->id,
                'old_values' => [
                    'start_date' => $oldStartDate,
                    'deadline' => $oldDeadline,
                ],
                'new_values' => [
                    'start_date' => $newStartDate,
                    'deadline' => $newDeadline,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            Log::warning("ActivityLog entry for project schedule change failed: " . $e->getMessage());
        }
    }
}
