<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

use Illuminate\Notifications\Messages\MailMessage;

class ProjectAssignmentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Project $project,
        public string $role = 'lead'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $roleData = $this->toArray($notifiable);
        $targetUrl = $roleData['url'] ?? route('projects.show', $this->project->id);

        return (new MailMessage)
            ->subject("📌 [NexusPM] {$roleData['title']}: {$this->project->name} ({$this->project->code})")
            ->greeting("Hello {$notifiable->name},")
            ->line($roleData['message'])
            ->line("**Project Name:** {$this->project->name}")
            ->line("**Project Code:** {$this->project->code}")
            ->line("**Role Assigned:** {$roleData['role_label']}")
            ->line("**Start Date:** " . ($this->project->start_date ? $this->project->start_date->format('M d, Y') : 'N/A'))
            ->line("**Deadline:** " . ($this->project->deadline ? $this->project->deadline->format('M d, Y') : 'N/A'))
            ->action('Review Project Assignment', $targetUrl)
            ->line('Thank you for your leadership and commitment to GS NexusPM excellence.');
    }

    public function toArray(object $notifiable): array
    {
        $roleInfo = match($this->role) {
            'lead' => [
                'category' => 'approvals',
                'type' => 'warning',
                'title' => '👑 Project Leadership Assignment Required',
                'role_label' => 'Project Leader',
                'message' => "You have been designated as Project Leader for '{$this->project->name}' ({$this->project->code}). Please review and accept leadership to begin execution.",
                'action' => 'project_assignment',
                'url' => route('approvals.index'),
            ],
            'sponsor' => [
                'category' => 'updates',
                'type' => 'info',
                'title' => '💼 Assigned as Project Sponsor',
                'role_label' => 'Project Sponsor',
                'message' => "You have been assigned as Project Sponsor for '{$this->project->name}' ({$this->project->code}).",
                'action' => 'project_sponsor_added',
                'url' => route('projects.show', $this->project->id),
            ],
            'owner' => [
                'category' => 'updates',
                'type' => 'info',
                'title' => '👑 Assigned as Project Owner',
                'role_label' => 'Project Owner',
                'message' => "You have been assigned as Project Owner for '{$this->project->name}' ({$this->project->code}).",
                'action' => 'project_owner_added',
                'url' => route('projects.show', $this->project->id),
            ],
            'steering_committee' => [
                'category' => 'updates',
                'type' => 'info',
                'title' => '🏛️ Added to Steering Committee',
                'role_label' => 'Steering Committee',
                'message' => "You have been appointed to the Steering Committee for '{$this->project->name}' ({$this->project->code}).",
                'action' => 'project_steering_added',
                'url' => route('projects.show', $this->project->id),
            ],
            default => [
                'category' => 'updates',
                'type' => 'info',
                'title' => '👥 Added to Core Project Team',
                'role_label' => 'Core Project Team',
                'message' => "You have been added to the Core Project Team for '{$this->project->name}' ({$this->project->code}).",
                'action' => 'project_team_added',
                'url' => route('projects.show', $this->project->id),
            ],
        };

        return [
            'category'     => $roleInfo['category'],
            'type'         => $roleInfo['type'],
            'title'        => $roleInfo['title'],
            'project_id'   => $this->project->id,
            'project_code' => $this->project->code,
            'project_name' => $this->project->name,
            'role'         => $this->role,
            'role_label'   => $roleInfo['role_label'],
            'message'      => $roleInfo['message'],
            'action'       => $roleInfo['action'],
            'url'          => $roleInfo['url'],
        ];
    }
}
