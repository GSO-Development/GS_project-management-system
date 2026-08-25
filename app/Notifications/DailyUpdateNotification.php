<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DailyUpdateNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $updateTitle,
        public string $reporterName,
        public string $projectName,
        public ?string $url = null,
        public string $actionType = 'daily_update',
        public ?string $summary = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $title = match($this->actionType) {
            'comment' => 'New Feedback Comment',
            'task_log' => 'Task Progress Logged',
            default => 'Daily Project Update',
        };

        $message = match($this->actionType) {
            'comment' => "{$this->reporterName} commented on '{$this->updateTitle}' in {$this->projectName}.",
            'task_log' => "{$this->reporterName} logged progress on task '{$this->updateTitle}' in {$this->projectName}.",
            default => "{$this->reporterName} published a daily update for '{$this->projectName}': {$this->updateTitle}",
        };

        return [
            'category'    => 'updates',
            'action'      => $this->actionType,
            'title'       => $title,
            'message'     => $message,
            'summary'     => $this->summary,
            'project_name'=> $this->projectName,
            'type'        => 'info',
            'url'         => $this->url ?? route('daily-updates.index'),
            'reporter'    => $this->reporterName,
        ];
    }
}
