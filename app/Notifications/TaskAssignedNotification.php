<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $taskTitle,
        public string $projectName,
        public string $assignedByName,
        public ?string $dueDate = null,
        public ?string $url = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $dueStr = $this->dueDate ? " (Due: {$this->dueDate})" : "";

        return [
            'category'       => 'task_assigned',
            'action'         => 'task_assigned',
            'title'          => 'New Task Assigned',
            'message'        => "{$this->assignedByName} assigned you to task '{$this->taskTitle}' in '{$this->projectName}'{$dueStr}.",
            'task_title'     => $this->taskTitle,
            'project_name'   => $this->projectName,
            'type'           => 'info',
            'url'            => $this->url ?? route('my-tasks.index'),
            'assigned_by'    => $this->assignedByName,
        ];
    }
}
