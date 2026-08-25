<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskBlockerNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $taskTitle,
        public string $projectName,
        public string $reportedByName,
        public string $blockerReason,
        public ?string $url = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'category'       => 'blocker',
            'action'         => 'blocker_reported',
            'title'          => '🚨 Task Blocker / Delay Reported',
            'message'        => "{$this->reportedByName} flagged a blocker on '{$this->taskTitle}' in '{$this->projectName}': {$this->blockerReason}",
            'task_title'     => $this->taskTitle,
            'project_name'   => $this->projectName,
            'blocker_reason' => $this->blockerReason,
            'type'           => 'error',
            'url'            => $this->url ?? route('risks.index'),
            'reported_by'    => $this->reportedByName,
        ];
    }
}
