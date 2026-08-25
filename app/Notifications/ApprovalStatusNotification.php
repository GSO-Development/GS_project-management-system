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
        public string $action // 'submitted', 'approved', 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $typeLabel = $this->approvalRequest->request_type->label();
        $projectName = $this->approvalRequest->project->name ?? 'Project';
        $requesterName = $this->approvalRequest->requester->name ?? 'User';

        if ($this->action === 'submitted') {
            $title = 'New Approval Request';
            $message = "New {$typeLabel} approval request submitted for '{$projectName}' by {$requesterName}.";
            $type = 'warning';
        } elseif ($this->action === 'approved') {
            $title = 'Approval Request Approved';
            $message = "Your request for {$typeLabel} in '{$projectName}' was approved.";
            $type = 'success';
        } else {
            $title = 'Approval Request Rejected';
            $message = "Your request for {$typeLabel} in '{$projectName}' was rejected.";
            $type = 'error';
        }

        return [
            'category'            => 'approvals',
            'approval_request_id' => $this->approvalRequest->id,
            'project_id'          => $this->approvalRequest->project_id,
            'title'               => $title,
            'message'             => $message,
            'action'              => $this->action,
            'type'                => $type,
            'url'                 => route('approvals.index'),
        ];
    }
}
