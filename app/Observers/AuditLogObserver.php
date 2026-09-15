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

        $action = match ($baseName) {
            'Project'             => $eventType === 'create' ? 'created_project' : ($eventType === 'update' ? 'updated_project' : 'deleted_project'),
            'WbsItem'             => $eventType === 'create' ? 'created_wbs_item' : ($eventType === 'update' ? 'updated_wbs_item' : 'deleted_wbs_item'),
            'ProjectStatusUpdate' => $eventType === 'create' ? 'created_status_update' : ($eventType === 'update' ? 'updated_status_update' : 'deleted_status_update'),
            'ProjectRisk'         => $eventType === 'create' ? 'created_risk' : ($eventType === 'update' ? 'updated_risk' : 'deleted_risk'),
            'TaskBlocker'         => $eventType === 'create' ? 'created_blocker' : ($eventType === 'update' ? 'updated_blocker' : 'deleted_blocker'),
            'ProjectDocument'     => $eventType === 'create' ? 'uploaded_document' : ($eventType === 'update' ? 'updated_document' : 'deleted_document'),
            'Comment'             => $eventType === 'create' ? 'added_comment' : ($eventType === 'update' ? 'updated_comment' : 'deleted_comment'),
            'User'                => $eventType === 'create' ? 'created_user' : ($eventType === 'update' ? 'updated_user' : 'deleted_user'),
            'Subsidiary'          => $eventType === 'create' ? 'created_subsidiary' : ($eventType === 'update' ? 'updated_subsidiary' : 'deleted_subsidiary'),
            default               => strtolower($eventType . '_' . preg_replace('/(?<!^)[A-Z]/', '_$0', $baseName)),
        };

        $module = match ($baseName) {
            'Project', 'ProjectStatusUpdate', 'ProjectRisk' => 'projects',
            'WbsItem', 'TaskBlocker'                        => 'wbs_items',
            'ProjectDocument'                               => 'documents',
            'Comment'                                       => 'projects',
            'User'                                          => 'users',
            'Subsidiary'                                    => 'subsidiaries',
            default                                         => 'general',
        };

        // Prevent duplicate logging if manual ActivityLog was created within the last 3 seconds
        $recentDuplicateExists = ActivityLog::where('user_id', $userId)
            ->where('record_type', $class)
            ->where('record_id', $model->getKey())
            ->where('created_at', '>=', now()->subSeconds(3))
            ->exists();

        if ($recentDuplicateExists) {
            return;
        }

        $prevValues = null;
        $newValues = null;

        // Filter out sensitive fields
        $hiddenFields = ['password', 'remember_token', 'azure_token'];

        if ($eventType === 'create') {
            $newValues = array_diff_key($model->getAttributes(), array_flip($hiddenFields));
        } elseif ($eventType === 'update') {
            $changes = $model->getChanges();
            if (empty($changes)) {
                return; // Nothing changed
            }
            $prevValues = array_intersect_key($model->getOriginal(), $changes);
            $newValues = $changes;

            $prevValues = array_diff_key($prevValues, array_flip($hiddenFields));
            $newValues = array_diff_key($newValues, array_flip($hiddenFields));
        } elseif ($eventType === 'delete') {
            $prevValues = array_diff_key($model->getOriginal(), array_flip($hiddenFields));
        }

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
            // Silently absorb audit logging exceptions to prevent breaking business operations
        }
    }
}
