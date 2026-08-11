<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $taskTitle,
        public string $projectName,
        public string $completedByName,
        public ?string $url = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'category'    => 'task_completed',
            'title'       => 'Task Completed',
            'message'     => "Task '{$this->taskTitle}' in '{$this->projectName}' was completed by {$this->completedByName}.",
            'type'        => 'success',
            'url'         => $this->url ?? route('projects.index'),
            'completed_by'=> $this->completedByName,
        ];
    }
}
