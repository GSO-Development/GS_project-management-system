<?php

namespace App\Notifications;

use App\Models\ApprovalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApprovalStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ApprovalRequest $approvalRequest,
        public string $action
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $typeLabel = $this->approvalRequest->request_type->label();
        $projectName = $this->approvalRequest->project->name ?? 'Project';

        return [
            'approval_request_id' => $this->approvalRequest->id,
            'project_id' => $this->approvalRequest->project_id,
            'message' => "Your request for {$typeLabel} in '{$projectName}' was {$this->action}.",
            'action' => $this->action,
            'url' => route('approvals.index'),
        ];
    }
}
