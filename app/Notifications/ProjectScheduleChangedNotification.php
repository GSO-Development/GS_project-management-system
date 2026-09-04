<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectScheduleChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Project $project,
        public User $editor,
        public ?string $oldStartDate,
        public ?string $newStartDate,
        public ?string $oldDeadline,
        public ?string $newDeadline,
        public bool $startDateChanged = false,
        public bool $deadlineChanged = false,
        public ?string $varianceText = null
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $oldStartFmt = $this->oldStartDate ? Carbon::parse($this->oldStartDate)->format('M d, Y') : 'Not Set';
        $newStartFmt = $this->newStartDate ? Carbon::parse($this->newStartDate)->format('M d, Y') : 'Not Set';
        $oldDeadFmt  = $this->oldDeadline  ? Carbon::parse($this->oldDeadline)->format('M d, Y')  : 'Not Set';
        $newDeadFmt  = $this->newDeadline  ? Carbon::parse($this->newDeadline)->format('M d, Y')  : 'Not Set';

        if ($this->startDateChanged && $this->deadlineChanged) {
            $title   = '📅 Project Start Date & Deadline Updated';
            $message = "Project Manager {$this->editor->name} updated the schedule for '{$this->project->name}' ({$this->project->code}). Start: {$oldStartFmt} → {$newStartFmt}, Deadline: {$oldDeadFmt} → {$newDeadFmt}.";
        } elseif ($this->deadlineChanged) {
            $title   = '🎯 Project Deadline Updated';
            $message = "Project Manager {$this->editor->name} changed the deadline for '{$this->project->name}' ({$this->project->code}) from {$oldDeadFmt} to {$newDeadFmt}.";
        } elseif ($this->startDateChanged) {
            $title   = '🛫 Project Start Date Updated';
            $message = "Project Manager {$this->editor->name} updated the start date for '{$this->project->name}' ({$this->project->code}) from {$oldStartFmt} to {$newStartFmt}.";
        } else {
            $title   = '📅 Project Schedule Updated';
            $message = "Project Manager {$this->editor->name} updated the project timeline for '{$this->project->name}' ({$this->project->code}).";
        }

        return [
            'category'            => 'schedule_change',
            'action'              => 'project_schedule_updated',
            'type'                => 'warning',
            'title'               => $title,
            'message'             => $message,
            'project_id'          => $this->project->id,
            'project_code'        => $this->project->code,
            'project_name'        => $this->project->name,
            'editor_name'         => $this->editor->name,
            'editor_id'           => $this->editor->id,
            'old_start_date'      => $this->oldStartDate,
            'new_start_date'      => $this->newStartDate,
            'old_deadline'        => $this->oldDeadline,
            'new_deadline'        => $this->newDeadline,
            'start_date_changed'  => $this->startDateChanged,
            'deadline_changed'    => $this->deadlineChanged,
            'variance_text'       => $this->varianceText,
            'url'                 => route('projects.show', $this->project->id),
        ];
    }
}
