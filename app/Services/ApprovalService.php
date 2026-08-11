<?php

namespace App\Services;

use App\Enums\ApprovalStatus;
use App\Models\ActivityLog;
use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\User;
use App\Models\WbsBaseline;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ApprovalService
{
    /**
     * Approves an approval request and applies official updates in a DB transaction.
     */
    public function approve(ApprovalRequest $request, User $reviewer, ?string $comment = null): void
    {
        if ($request->requested_by === $reviewer->id && !$reviewer->hasRole('super_admin')) {
            throw new InvalidArgumentException("Users cannot approve their own requests.");
        }

        DB::transaction(function () use ($request, $reviewer, $comment) {
            $project = $request->project;

            $request->update([
                'status' => ApprovalStatus::APPROVED,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
                'review_comment' => $comment,
            ]);

            // Apply specific official changes depending on request_type
            $reqValue = $request->requested_value ?? [];

            switch ($request->request_type->value) {
                case 'deadline_extension':
                    if (isset($reqValue['new_deadline'])) {
                        $project->deadline = $reqValue['new_deadline'];
                        $project->save();
                    }
                    break;

                case 'budget_change':
                    if (isset($reqValue['new_budget'])) {
                        $project->estimated_budget = $reqValue['new_budget'];
                        $project->save();
                    }
                    break;

                case 'project_completion':
                    $project->status = \App\Enums\ProjectStatus::COMPLETED;
                    $project->overall_progress = 100;
                    $project->save();
                    break;

                case 'project_cancellation':
                    $project->status = \App\Enums\ProjectStatus::CANCELLED;
                    $project->save();
                    break;

                case 'wbs_baseline':
                    // Snapshot the current WBS items (safe serializable fields only)
                    $wbsItems = $project->wbsItems()->get()->map(fn ($item) => [
                        'id'           => $item->id,
                        'wbs_code'     => $item->wbs_code,
                        'item_type'    => $item->item_type?->value ?? $item->item_type,
                        'title'        => $item->title,
                        'description'  => $item->description,
                        'status'       => $item->status,
                        'start_date'   => $item->start_date?->toDateString(),
                        'end_date'     => $item->end_date?->toDateString(),
                        'progress'     => $item->progress,
                        'assigned_user_id' => $item->assigned_user_id,
                        'estimated_hours'  => $item->estimated_hours,
                        'actual_hours'     => $item->actual_hours,
                        'wbs_version_id'   => $item->wbs_version_id,
                    ])->toArray();

                    $lastBaseline = WbsBaseline::where('project_id', $project->id)->max('baseline_number') ?? 0;

                    // Find existing WBS version or auto-create one for this baseline
                    $versionId = $reqValue['version_id'] ?? null;

                    if (!$versionId) {
                        // Auto-create a WBS version snapshot
                        $lastVersion = \App\Models\WbsVersion::where('project_id', $project->id)->max('version_number') ?? 0;
                        $wbsVersion  = \App\Models\WbsVersion::create([
                            'project_id'     => $project->id,
                            'version_number' => $lastVersion + 1,
                            'change_summary' => 'Baseline created via approval: ' . ($request->reason ?? 'WBS Baseline'),
                            'created_by'     => $request->requested_by,
                            'status'         => 'approved',
                            'reviewed_by'    => $reviewer->id,
                            'reviewed_at'    => now(),
                            'submitted_at'   => $request->submitted_at ?? now(),
                        ]);
                        $versionId = $wbsVersion->id;
                    }

                    WbsBaseline::create([
                        'project_id'         => $project->id,
                        'wbs_version_id'     => $versionId,
                        'baseline_number'    => $lastBaseline + 1,
                        'original_start_date' => $project->start_date,
                        'original_deadline'  => $project->deadline,
                        'baseline_data'      => $wbsItems,
                        'created_by'         => $request->requested_by,
                        'approved_by'        => $reviewer->id,
                    ]);
                    break;
            }

            // Log activity
            ActivityLog::create([
                'user_id' => $reviewer->id,
                'action' => 'approved_request',
                'module' => 'approvals',
                'record_type' => ApprovalRequest::class,
                'record_id' => $request->id,
                'new_values' => [
                    'request_type' => $request->request_type->value,
                    'project_id' => $project->id,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Database Notification to requester
            $request->requester->notify(new \App\Notifications\ApprovalStatusNotification($request, 'approved'));
        });
    }

    /**
     * Rejects an approval request.
     */
    public function reject(ApprovalRequest $request, User $reviewer, string $reason): void
    {
        if ($request->requested_by === $reviewer->id && !$reviewer->hasRole('super_admin')) {
            throw new InvalidArgumentException("Users cannot reject their own requests.");
        }

        $request->update([
            'status' => ApprovalStatus::REJECTED,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_comment' => $reason,
        ]);

        ActivityLog::create([
            'user_id' => $reviewer->id,
            'action' => 'rejected_request',
            'module' => 'approvals',
            'record_type' => ApprovalRequest::class,
            'record_id' => $request->id,
            'new_values' => ['reason' => $reason],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $request->requester->notify(new \App\Notifications\ApprovalStatusNotification($request, 'rejected'));
    }
}
