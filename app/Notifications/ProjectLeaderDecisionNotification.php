<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectLeaderDecisionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Project $project,
        public User $leader,
        public string $decision, // 'accepted' or 'rejected'
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        if ($this->decision === 'accepted') {
            return [
                'project_id'   => $this->project->id,
                'project_code' => $this->project->code,
                'project_name' => $this->project->name,
                'leader_name'  => $this->leader->name,
                'decision'     => 'accepted',
                'message'      => "Project Leader {$this->leader->name} accepted project '{$this->project->name}' ({$this->project->code}). Execution has been initialized.",
                'action'       => 'project_accepted',
                'url'          => route('projects.show', $this->project->id),
            ];
        }

        return [
            'project_id'   => $this->project->id,
            'project_code' => $this->project->code,
            'project_name' => $this->project->name,
            'leader_name'  => $this->leader->name,
            'decision'     => 'rejected',
            'reason'       => $this->reason,
            'message'      => "⚠️ Project Leader {$this->leader->name} declined assignment for '{$this->project->name}' ({$this->project->code}). Issue/Feedback: \"{$this->reason}\"",
            'action'       => 'project_rejected',
            'url'          => route('projects.show', $this->project->id),
        ];
    }
}
