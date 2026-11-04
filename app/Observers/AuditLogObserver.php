<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectRisk;
use App\Models\ProjectStatusUpdate;
use App\Models\Subsidiary;
use App\Models\TaskBlocker;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\AuditLogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AuditLogObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $this->logActivity($model, 'create');
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        $this->logActivity($model, 'update');
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->logActivity($model, 'delete');
    }

    /**
     * Dispatch structured audit log record.
     */
    protected function logActivity(Model $model, string $eventType): void
    {
        // 1. Check if auditing is globally muted or specifically silenced for this model
        if (AuditLogService::isMuted() || AuditLogService::isModelSilenced($model)) {
            return;
        }

        $userId    = auth()->id();
        $actorType = AuditLogService::getActorType();

        // 2. Allow unauthenticated logging only if in automated system or background maintenance context
        if (!$userId && $actorType === ActivityLog::ACTOR_USER) {
            return;
        }

        $class    = get_class($model);
        $baseName = class_basename($class);

        $prevValues = null;
        $newValues  = null;

        // Do not store credentials, framework timestamps, or noisy internal fields.
        $hiddenFields = ['password', 'remember_token', 'azure_token', 'created_at', 'updated_at'];

        if ($eventType === 'create') {
            $newValues = array_diff_key($model->getAttributes(), array_flip($hiddenFields));
        } elseif ($eventType === 'update') {
            $changes = array_diff_key($model->getChanges(), array_flip($hiddenFields));
            if (empty($changes)) {
                return;
            }

            $prevValues = array_intersect_key($model->getOriginal(), $changes);
            $newValues  = $changes;
        } elseif ($eventType === 'delete') {
            $prevValues = array_diff_key($model->getOriginal(), array_flip($hiddenFields));
        }

        $changedKeys = array_keys($newValues ?? []);

        // 3. Resolve semantic audit action
        $action = match ($baseName) {
            'Project'             => $this->projectAction($eventType, $changedKeys, $newValues),
            'WbsItem'             => $this->taskAction($eventType, $changedKeys, $newValues),
            'ProjectStatusUpdate' => $eventType === 'create' ? 'created_status_update' : ($eventType === 'update' ? 'updated_status_update' : 'deleted_status_update'),
            'ProjectRisk'         => $eventType === 'create' ? 'created_risk' : ($eventType === 'update' ? 'updated_risk' : 'deleted_risk'),
            'TaskBlocker'         => $eventType === 'create' ? 'created_blocker' : ($eventType === 'update' ? 'updated_blocker' : 'deleted_blocker'),
            'ProjectDocument'     => $eventType === 'create' ? 'uploaded_document' : ($eventType === 'update' ? 'updated_document' : 'deleted_document'),
            'Comment'             => $eventType === 'create' ? 'added_comment' : ($eventType === 'update' ? 'updated_comment' : 'deleted_comment'),
            'User'                => $this->userAction($eventType, $changedKeys, $newValues),
            'Subsidiary'          => $eventType === 'create' ? 'created_subsidiary' : ($eventType === 'update' ? 'updated_subsidiary' : 'deleted_subsidiary'),
            'ApprovalRequest'     => $eventType === 'create' ? 'submitted_approval_request' : ($eventType === 'update' ? 'updated_approval_request' : 'deleted_approval_request'),
            default               => strtolower($eventType . '_' . preg_replace('/(?<!^)[A-Z]/', '_$0', $baseName)),
        };

        // 4. Prevent duplicate semantic/observer logs in the same request lifecycle
        if (AuditLogService::hasLogged($class, $model->getKey(), $action)) {
            return;
        }

        $module = match ($baseName) {
            'Project', 'ProjectStatusUpdate', 'ProjectRisk' => 'projects',
            'WbsItem', 'TaskBlocker'                        => 'wbs_items',
            'ProjectDocument'                               => 'documents',
            'Comment'                                       => 'projects',
            'User'                                          => 'users',
            'Subsidiary'                                    => 'subsidiaries',
            'ApprovalRequest'                               => 'approvals',
            default                                         => 'general',
        };

        $ipAddress = null;
        $userAgent = null;

        if (!app()->runningInConsole() && request()) {
            $ipAddress = request()->ip();
            $userAgent = request()->userAgent();
        } elseif (app()->runningInConsole()) {
            $ipAddress = 'CLI / Background Worker';
            $userAgent = 'Artisan / Cron Console Process';
        }

        try {
            AuditLogService::markLogged($class, $model->getKey(), $action);

            ActivityLog::create([
                'user_id'         => $userId,
                'action'          => $action,
                'actor_type'      => $actorType,
                'module'          => $module,
                'record_type'     => $class,
                'record_id'       => $model->getKey(),
                'batch_uuid'      => AuditLogService::getBatchUuid(),
                'previous_values' => $prevValues,
                'new_values'      => $newValues,
                'ip_address'      => $ipAddress,
                'user_agent'      => $userAgent,
            ]);
        } catch (\Throwable $e) {
            // Audit failures must not break the business action, but must remain diagnosable.
            Log::warning('AuditLogObserver: Unable to write audit log.', [
                'model' => $class,
                'record_id' => $model->getKey(),
                'event' => $eventType,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Give task changes explicit, searchable audit actions.
     */
    protected function taskAction(string $eventType, array $changedFields, ?array $newValues): string
    {
        if ($eventType === 'create') {
            return 'created_wbs_item';
        }

        if ($eventType === 'delete') {
            return 'deleted_wbs_item';
        }

        if (in_array('assigned_user_id', $changedFields, true)) {
            return empty($newValues['assigned_user_id']) ? 'unassigned_task' : 'reassigned_task';
        }

        return match (true) {
            in_array('status', $changedFields, true) => 'changed_task_status',
            in_array('progress', $changedFields, true) => 'updated_task_progress',
            count(array_intersect($changedFields, ['start_date', 'end_date', 'start_time', 'end_time'])) > 0 => 'rescheduled_task',
            default => 'updated_wbs_item',
        };
    }

    /**
     * Project specific semantic actions.
     */
    protected function projectAction(string $eventType, array $changedFields, ?array $newValues): string
    {
        if ($eventType === 'create') return 'created_project';
        if ($eventType === 'delete') return 'deleted_project';

        if (in_array('project_manager_id', $changedFields, true)) {
            return empty($newValues['project_manager_id']) ? 'unassigned_project_manager' : 'reassigned_project_leader';
        }

        if (in_array('status', $changedFields, true)) {
            return 'changed_project_status';
        }

        if (in_array('deadline', $changedFields, true)) {
            return 'updated_project_schedule';
        }

        return 'updated_project';
    }

    /**
     * User specific semantic actions (e.g. deactivation, activation).
     */
    protected function userAction(string $eventType, array $changedFields, ?array $newValues): string
    {
        if ($eventType === 'create') return 'created_user';
        if ($eventType === 'delete') return 'deleted_user';

        if (in_array('is_active', $changedFields, true)) {
            return (!empty($newValues['is_active'])) ? 'activated_user' : 'deactivated_user';
        }

        return 'updated_user';
    }
}
