<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class ProjectDeletionService
{
    /**
     * Permanently purge a project and all associated cascading records from the entire system,
     * recording a comprehensive PMO audit log entry.
     */
    public function purgeProject(Project|int $project, User $user): bool
    {
        $projectModel = $project instanceof Project ? $project : Project::withTrashed()->find($project);
        if (!$projectModel) {
            return false;
        }

        if (!$user->isPmoAdmin()) {
            throw new InvalidArgumentException("Unauthorized: Only PMO Admins can permanently delete projects.");
        }

        $projectId = $projectModel->id;
        $projectName = $projectModel->name;
        $projectCode = $projectModel->code;

        return DB::transaction(function () use ($projectModel, $projectId, $projectName, $projectCode, $user) {
            // 1. Gather all WBS items belonging to this project
            $wbsItemIds = DB::table('wbs_items')->where('project_id', $projectId)->pluck('id')->toArray();

            // Count related items for audit snapshot
            $tasksCount = count($wbsItemIds);
            $docsCount = DB::table('project_documents')->where('project_id', $projectId)->count();
            $risksCount = DB::table('project_risks')->where('project_id', $projectId)->count();
            $membersCount = DB::table('project_members')->where('project_id', $projectId)->count();
            $updatesCount = DB::table('project_status_updates')->where('project_id', $projectId)->count();
            $approvalsCount = DB::table('approval_requests')->where('project_id', $projectId)->count();

            // 2. Delete task dependencies
            if (!empty($wbsItemIds)) {
                DB::table('wbs_dependencies')
                    ->whereIn('predecessor_id', $wbsItemIds)
                    ->orWhereIn('successor_id', $wbsItemIds)
                    ->delete();

                // 3. Delete task blockers
                DB::table('task_blockers')
                    ->whereIn('wbs_item_id', $wbsItemIds)
                    ->delete();

                // 4. Delete task comments
                DB::table('comments')
                    ->where('commentable_type', 'App\Models\WbsItem')
                    ->whereIn('commentable_id', $wbsItemIds)
                    ->delete();
            }

            // 5. Delete project-level comments
            DB::table('comments')
                ->where('commentable_type', 'App\Models\Project')
                ->where('commentable_id', $projectId)
                ->delete();

            // 6. Delete uploaded document files from disk & delete document records
            $documents = DB::table('project_documents')->where('project_id', $projectId)->get();
            foreach ($documents as $doc) {
                if (!empty($doc->file_path)) {
                    try {
                        Storage::disk('public')->delete($doc->file_path);
                    } catch (\Throwable $e) {
                        Log::warning("Could not delete file {$doc->file_path}: " . $e->getMessage());
                    }
                }
            }
            DB::table('project_documents')->where('project_id', $projectId)->delete();

            // 7. Delete project risks
            DB::table('project_risks')->where('project_id', $projectId)->delete();

            // 8. Delete project status updates
            DB::table('project_status_updates')->where('project_id', $projectId)->delete();

            // 9. Delete approval requests
            DB::table('approval_requests')->where('project_id', $projectId)->delete();

            // 10. Delete WBS baselines and versions
            DB::table('wbs_baselines')->where('project_id', $projectId)->delete();
            DB::table('wbs_versions')->where('project_id', $projectId)->delete();

            // 11. Delete project members pivot
            DB::table('project_members')->where('project_id', $projectId)->delete();

            // 12. Delete WBS items
            DB::table('wbs_items')->where('project_id', $projectId)->delete();

            // 13. Delete any orphaned notifications referencing this project
            try {
                DB::table('notifications')
                    ->where('data', 'like', '%"project_id":' . $projectId . '%')
                    ->orWhere('data', 'like', '%"project_id":"' . $projectId . '"%')
                    ->delete();
            } catch (\Throwable $e) {
                Log::warning("Could not delete notifications for project #{$projectId}: " . $e->getMessage());
            }

            // 14. Capture complete pre-deletion snapshot for Audit Log
            $previousSnapshot = [
                'id'               => $projectModel->id,
                'code'             => $projectModel->code,
                'name'             => $projectModel->name,
                'subsidiary'       => $projectModel->subsidiary?->name ?? 'Corporate / Group',
                'project_manager'  => $projectModel->projectManager?->name ?? 'Unassigned',
                'start_date'       => $projectModel->start_date?->toDateString(),
                'deadline'         => $projectModel->deadline?->toDateString(),
                'status'           => is_object($projectModel->status) ? $projectModel->status->value : (string) $projectModel->status,
                'priority'         => is_object($projectModel->priority) ? $projectModel->priority->value : (string) $projectModel->priority,
                'overall_progress' => (int) $projectModel->overall_progress,
                'estimated_budget' => (float) ($projectModel->estimated_budget ?? 0),
                'actual_cost'      => (float) ($projectModel->actual_cost ?? 0),
                'stats'            => [
                    'tasks_count'     => $tasksCount,
                    'documents_count' => $docsCount,
                    'risks_count'     => $risksCount,
                    'members_count'   => $membersCount,
                    'updates_count'   => $updatesCount,
                    'approvals_count' => $approvalsCount,
                ],
            ];

            // 15. Permanently remove the project record from database (Force Delete)
            $projectModel->forceDelete();

            // 16. Record Comprehensive Audit Log
            ActivityLog::create([
                'user_id'         => $user->id,
                'action'          => 'deleted_project',
                'module'          => 'projects',
                'record_type'     => Project::class,
                'record_id'       => $projectId,
                'previous_values' => $previousSnapshot,
                'new_values'      => [
                    'status'           => 'permanently_deleted',
                    'deleted_by'       => $user->name,
                    'deleted_by_email' => $user->email,
                    'deleted_at'       => now()->toDateTimeString(),
                    'summary'          => "Project '{$projectName}' ({$projectCode}) and all {$tasksCount} WBS tasks, {$docsCount} documents, and related records were permanently purged from the system by PMO Admin {$user->name}.",
                ],
                'ip_address'      => request()->ip(),
                'user_agent'      => request()->userAgent(),
            ]);

            return true;
        });
    }
}
