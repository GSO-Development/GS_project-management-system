<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuditLogService
{
    protected static ?string $currentActorType = null;
    protected static ?string $currentBatchUuid = null;
    protected static bool $isMuted = false;
    protected static array $silencedModels = [];
    protected static array $requestLoggedKeys = [];

    /**
     * Get active actor type ('user', 'system', 'maintenance').
     */
    public static function getActorType(): string
    {
        if (self::$currentActorType !== null) {
            return self::$currentActorType;
        }

        if (app()->runningInConsole()) {
            return ActivityLog::ACTOR_MAINTENANCE;
        }

        return ActivityLog::ACTOR_USER;
    }

    /**
     * Set active actor type.
     */
    public static function setActorType(?string $type): void
    {
        self::$currentActorType = $type;
    }

    /**
     * Get active batch UUID if inside a batch operation.
     */
    public static function getBatchUuid(): ?string
    {
        return self::$currentBatchUuid;
    }

    /**
     * Set active batch UUID.
     */
    public static function setBatchUuid(?string $uuid): void
    {
        self::$currentBatchUuid = $uuid;
    }

    /**
     * Generate a new batch UUID string.
     */
    public static function newBatchUuid(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Execute a closure under Automated System Action context.
     */
    public static function asSystem(callable $callback, ?string $batchUuid = null): mixed
    {
        $prevActor = self::$currentActorType;
        $prevBatch = self::$currentBatchUuid;

        self::$currentActorType = ActivityLog::ACTOR_SYSTEM;
        if ($batchUuid !== null) {
            self::$currentBatchUuid = $batchUuid;
        }

        try {
            return $callback();
        } finally {
            self::$currentActorType = $prevActor;
            self::$currentBatchUuid = $prevBatch;
        }
    }

    /**
     * Execute a closure under Background Maintenance Action context.
     */
    public static function asMaintenance(callable $callback, ?string $batchUuid = null): mixed
    {
        $prevActor = self::$currentActorType;
        $prevBatch = self::$currentBatchUuid;

        self::$currentActorType = ActivityLog::ACTOR_MAINTENANCE;
        if ($batchUuid !== null) {
            self::$currentBatchUuid = $batchUuid;
        }

        try {
            return $callback();
        } finally {
            self::$currentActorType = $prevActor;
            self::$currentBatchUuid = $prevBatch;
        }
    }

    /**
     * Execute a closure under a specific batch UUID.
     */
    public static function withBatch(string $batchUuid, callable $callback): mixed
    {
        $prevBatch = self::$currentBatchUuid;
        self::$currentBatchUuid = $batchUuid;

        try {
            return $callback();
        } finally {
            self::$currentBatchUuid = $prevBatch;
        }
    }

    /**
     * Temporarily mute AuditLogObserver to avoid duplicate or noisy generic logs.
     */
    public static function withoutObserver(callable $callback): mixed
    {
        $prevMuted = self::$isMuted;
        self::$isMuted = true;

        try {
            return $callback();
        } finally {
            self::$isMuted = $prevMuted;
        }
    }

    public static function isMuted(): bool
    {
        return self::$isMuted;
    }

    /**
     * Silence audit logging for a specific model instance.
     */
    public static function silenceModel(Model $model): void
    {
        $class = get_class($model);
        $key = $model->getKey();
        if ($key) {
            self::$silencedModels[$class][$key] = true;
        }
    }

    /**
     * Check if a specific model instance is silenced.
     */
    public static function isModelSilenced(Model $model): bool
    {
        $class = get_class($model);
        $key = $model->getKey();
        return !empty(self::$silencedModels[$class][$key]);
    }

    /**
     * Deduplication tracking per request.
     */
    public static function markLogged(string $class, $id, string $action): void
    {
        $k = "{$class}:{$id}:{$action}";
        self::$requestLoggedKeys[$k] = true;
    }

    public static function hasLogged(string $class, $id, string $action): bool
    {
        $k = "{$class}:{$id}:{$action}";
        return !empty(self::$requestLoggedKeys[$k]);
    }

    /**
     * Write an audit log record cleanly.
     */
    public static function log(
        string $action,
        string $module,
        Model|string|null $record = null,
        ?array $previous = null,
        ?array $new = null,
        ?string $actorType = null,
        ?string $batchUuid = null,
        ?int $userId = null,
        ?int $recordId = null
    ): ?ActivityLog {
        $actorType = $actorType ?? self::getActorType();
        $batchUuid = $batchUuid ?? self::getBatchUuid();
        $userId    = $userId ?? auth()->id();

        $recordType = null;

        if ($record instanceof Model) {
            $recordType = get_class($record);
            $recordId   = $record->getKey();
        } elseif (is_string($record)) {
            $recordType = $record;
        }

        if ($recordType && $recordId) {
            self::markLogged($recordType, $recordId, $action);
        }

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
            return ActivityLog::create([
                'user_id'         => $userId,
                'action'          => $action,
                'actor_type'      => $actorType,
                'module'          => $module,
                'record_type'     => $recordType,
                'record_id'       => $recordId,
                'batch_uuid'      => $batchUuid,
                'previous_values' => $previous,
                'new_values'      => $new,
                'ip_address'      => $ipAddress,
                'user_agent'      => $userAgent,
            ]);
        } catch (\Throwable $e) {
            Log::warning('AuditLogService: Unable to write audit log.', [
                'action' => $action,
                'record_type' => $recordType,
                'record_id' => $recordId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Write individual audit records for bulk unassignment/disassociation operations.
     * Prevents database updates from silently bypassing audit visibility.
     */
    public static function logBulkDisassociation(
        string $modelClass,
        iterable $records,
        string $fieldCleared,
        mixed $clearedValue,
        string $action,
        string $module,
        string $reason,
        ?string $batchUuid = null,
        ?string $actorType = null
    ): int {
        $batchUuid = $batchUuid ?? self::getBatchUuid() ?? self::newBatchUuid();
        $actorType = $actorType ?? self::getActorType();
        $count     = 0;

        foreach ($records as $record) {
            $recId = is_object($record) ? ($record->id ?? $record->getKey() ?? null) : (int) $record;
            $title = is_object($record) ? ($record->title ?? $record->name ?? "Record #{$recId}") : "Record #{$recId}";
            $code  = is_object($record) ? ($record->wbs_code ?? $record->code ?? null) : null;

            self::log(
                action: $action,
                module: $module,
                record: $record instanceof Model ? $record : $modelClass,
                previous: [
                    $fieldCleared => $clearedValue,
                    'record_title' => $title,
                    'record_code'  => $code,
                ],
                new: [
                    $fieldCleared => null,
                    'reason'      => $reason,
                    'record_title' => $title,
                    'record_code'  => $code,
                ],
                actorType: $actorType,
                batchUuid: $batchUuid,
                recordId: $recId
            );
            $count++;
        }

        return $count;
    }
}
