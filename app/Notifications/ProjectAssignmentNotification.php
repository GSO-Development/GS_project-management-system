<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectAssignmentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Project $project,
        public string $role = 'lead'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isLead = ($this->role === 'lead');

        if ($isLead) {
            return [
                'category'     => 'approvals',
                'type'         => 'warning',
                'title'        => '👑 Project Leadership Assignment Required',
                'project_id'   => $this->project->id,
                'project_code' => $this->project->code,
                'project_name' => $this->project->name,
                'role'         => 'lead',
                'message'      => "You have been designated as Project Leader for '{$this->project->name}' ({$this->project->code}). Please review and accept leadership to begin execution.",
                'action'       => 'project_assignment',
                'url'          => route('approvals.index'),
            ];
        }

        return [
            'category'     => 'updates',
            'type'         => 'info',
            'title'        => '👥 Added to Project Team',
            'project_id'   => $this->project->id,
            'project_code' => $this->project->code,
            'project_name' => $this->project->name,
            'role'         => 'member',
            'message'      => "You have been added as a team collaborator to '{$this->project->name}' ({$this->project->code}).",
            'action'       => 'project_team_added',
            'url'          => route('projects.show', $this->project->id),
        ];
    }
}
