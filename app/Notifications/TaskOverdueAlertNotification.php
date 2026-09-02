<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdueAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $taskTitle,
        public string $projectName,
        public int $projectId,
        public ?string $assignedUserName = null,
        public ?string $deadlineFormatted = null,
        public ?string $dueTimeFormatted = null,
        public int $progress = 0,
        public string $overdueDuration = '',
        public ?string $url = null
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $targetUrl = $this->url ?? route('projects.show', $this->projectId);
        $assignee = $this->assignedUserName ?: 'Unassigned';
        $deadlineText = $this->deadlineFormatted ? ($this->deadlineFormatted . ($this->dueTimeFormatted ? " at {$this->dueTimeFormatted}" : '')) : 'Not specified';

        return (new MailMessage)
            ->subject("🚨 [PMO Alert] Deliverable Delayed: {$this->taskTitle} ({$this->projectName})")
            ->greeting("Hello {$notifiable->name},")
            ->line("This is an automated PMO governance alert. A project deliverable has passed its scheduled completion time without being marked as completed.")
            ->line("**Project:** {$this->projectName}")
            ->line("**Task:** {$this->taskTitle}")
            ->line("**Assigned Collaborator:** {$assignee}")
            ->line("**Scheduled Deadline:** {$deadlineText}")
            ->line("**Current Progress:** {$this->progress}%")
            ->line("**Overdue Status:** {$this->overdueDuration}")
            ->action('Review Task in Project Workspace', $targetUrl)
            ->line('Please review the project schedule or coordinate with the project team to address potential bottlenecks.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'category'          => 'task_delay',
            'action'            => 'task_overdue',
            'type'              => 'error',
            'title'             => '🚨 Task Overdue / Delayed Alert',
            'message'           => "Task '{$this->taskTitle}' in '{$this->projectName}' missed its scheduled deadline ({$this->overdueDuration}). Current progress is {$this->progress}%.",
            'task_title'        => $this->taskTitle,
            'project_name'      => $this->projectName,
            'project_id'        => $this->projectId,
            'assigned_user'     => $this->assignedUserName,
            'progress'          => $this->progress,
            'overdue_duration'  => $this->overdueDuration,
            'url'               => $this->url ?? route('projects.show', $this->projectId),
        ];
    }
}
