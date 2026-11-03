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
        $userId = auth()->id();
        if (!$userId) return; // Only log authenticated user actions

        $class = get_class($model);
        $baseName = class_basename($class);

        $prevValues = null;
        $newValues = null;

        // Do not store credentials, framework timestamps, or other noisy fields.
        $hiddenFields = ['password', 'remember_token', 'azure_token', 'created_at', 'updated_at'];

        if ($eventType === 'create') {
            $newValues = array_diff_key($model->getAttributes(), array_flip($hiddenFields));
        } elseif ($eventType === 'update') {
            $changes = array_diff_key($model->getChanges(), array_flip($hiddenFields));
            if (empty($changes)) {
                return;
            }

            $prevValues = array_intersect_key($model->getOriginal(), $changes);
            $newValues = $changes;
        } elseif ($eventType === 'delete') {
            $prevValues = array_diff_key($model->getOriginal(), array_flip($hiddenFields));
        }

        $action = match ($baseName) {
            'Project'             => $eventType === 'create' ? 'created_project' : ($eventType === 'update' ? 'updated_project' : 'deleted_project'),
            'WbsItem'             => $this->taskAction($eventType, array_keys($newValues ?? [])),
            'ProjectStatusUpdate' => $eventType === 'create' ? 'created_status_update' : ($eventType === 'update' ? 'updated_status_update' : 'deleted_status_update'),
            'ProjectRisk'         => $eventType === 'create' ? 'created_risk' : ($eventType === 'update' ? 'updated_risk' : 'deleted_risk'),
            'TaskBlocker'         => $eventType === 'create' ? 'created_blocker' : ($eventType === 'update' ? 'updated_blocker' : 'deleted_blocker'),
            'ProjectDocument'     => $eventType === 'create' ? 'uploaded_document' : ($eventType === 'update' ? 'updated_document' : 'deleted_document'),
            'Comment'             => $eventType === 'create' ? 'added_comment' : ($eventType === 'update' ? 'updated_comment' : 'deleted_comment'),
            'User'                => $eventType === 'create' ? 'created_user' : ($eventType === 'update' ? 'updated_user' : 'deleted_user'),
            'Subsidiary'          => $eventType === 'create' ? 'created_subsidiary' : ($eventType === 'update' ? 'updated_subsidiary' : 'deleted_subsidiary'),
            'ApprovalRequest'     => $eventType === 'create' ? 'submitted_approval_request' : ($eventType === 'update' ? 'updated_approval_request' : 'deleted_approval_request'),
            default               => strtolower($eventType . '_' . preg_replace('/(?<!^)[A-Z]/', '_$0', $baseName)),
        };

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

        try {
            ActivityLog::create([
                'user_id'         => $userId,
                'action'          => $action,
                'module'          => $module,
                'record_type'     => $class,
                'record_id'       => $model->getKey(),
                'previous_values' => $prevValues,
                'new_values'      => $newValues,
                'ip_address'      => request()->ip(),
                'user_agent'      => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Audit failures must not break the business action, but must remain diagnosable.
            Log::warning('Unable to write audit log.', [
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
    protected function taskAction(string $eventType, array $changedFields): string
    {
        if ($eventType === 'create') {
            return 'created_wbs_item';
        }

        if ($eventType === 'delete') {
            return 'deleted_wbs_item';
        }

        return match (true) {
            in_array('status', $changedFields, true) => 'changed_task_status',
            in_array('assigned_user_id', $changedFields, true) => 'reassigned_task',
            in_array('progress', $changedFields, true) => 'updated_task_progress',
            count(array_intersect($changedFields, ['start_date', 'end_date', 'start_time', 'end_time'])) > 0 => 'rescheduled_task',
            default => 'updated_wbs_item',
        };
    }
}
