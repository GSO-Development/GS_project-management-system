<?php

namespace App\Models;

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WbsItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'parent_id',
        'wbs_code',
        'item_type',
        'title',
        'description',
        'assigned_user_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'duration',
        'status',
        'priority',
        'progress',
        'estimated_hours',
        'actual_hours',
        'weight',
        'is_milestone',
        'sort_order',
        'delay_reason',
        'delay_reason_by',
        'delay_reason_at',
        'rescheduled_shift_days',
        'rescheduled_at',
        'rescheduled_reason',
        'overdue_notified_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'item_type' => ItemType::class,
            'status' => WbsStatus::class,
            'priority' => Priority::class,
            'start_date' => 'date',
            'end_date' => 'date',
            'overdue_notified_at' => 'datetime',
            'is_milestone' => 'boolean',
            'weight' => 'decimal:4',
            'estimated_hours' => 'decimal:2',
            'actual_hours' => 'decimal:2',
            'progress' => 'integer',
            'delay_reason_at' => 'datetime',
            'rescheduled_at' => 'datetime',
            'rescheduled_shift_days' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(WbsItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(WbsItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function delayReporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delay_reason_by');
    }

    public function predecessors(): HasMany
    {
        return $this->hasMany(WbsDependency::class, 'successor_id');
    }

    public function successors(): HasMany
    {
        return $this->hasMany(WbsDependency::class, 'predecessor_id');
    }

    public function blockers(): HasMany
    {
        return $this->hasMany(TaskBlocker::class);
    }

    public function risks(): HasMany
    {
        return $this->hasMany(ProjectRisk::class, 'wbs_item_id');
    }

    public function openRisks(): HasMany
    {
        return $this->hasMany(ProjectRisk::class, 'wbs_item_id')->where('status', 'open');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function isLeaf(): bool
    {
        return $this->children()->count() === 0;
    }

    public function isOverdue(): bool
    {
        return $this->end_date && $this->end_date->isPast() && $this->status !== WbsStatus::COMPLETED;
    }

    /**
     * Get RAG Traffic Light health data
     */
    public function getTrafficLightAttribute(): array
    {
        return \App\Services\TaskHealthCalculationService::calculate($this);
    }

    public function getTrafficLightStatusAttribute(): string
    {
        return $this->traffic_light['status'];
    }

    public function getTrafficLightReasonAttribute(): string
    {
        return $this->traffic_light['reason'];
    }

    public function getStartTimeFormattedAttribute(): ?string
    {
        if (!$this->start_time) return null;
        try {
            return \Carbon\Carbon::parse($this->start_time)->format('h:i A');
        } catch (\Throwable $e) {
            return (string) $this->start_time;
        }
    }

    public function getEndTimeFormattedAttribute(): ?string
    {
        if (!$this->end_time) return null;
        try {
            return \Carbon\Carbon::parse($this->end_time)->format('h:i A');
        } catch (\Throwable $e) {
            return (string) $this->end_time;
        }
    }

    /**
     * Automatically transition tasks based on start_date schedule:
     * - Tasks whose start_date has arrived (start_date <= today) -> in_progress (if currently not_started)
     * - Future tasks (start_date > today) with 0% progress -> not_started (if incorrectly marked in_progress)
     */
    public static function autoStartDueTasks(?int $projectId = null): int
    {
        $today = now()->today()->toDateString();

        // 1. Advance due tasks to in_progress
        $dueQuery = static::whereNotNull('start_date')
            ->whereDate('start_date', '<=', $today)
            ->whereIn('status', [
                WbsStatus::NOT_STARTED->value, 
                WbsStatus::BACKLOG->value, 
                'not_started', 
                'backlog', 
                'draft'
            ]);

        if ($projectId) {
            $dueQuery->where('project_id', $projectId);
        }

        $started = $dueQuery->update(['status' => WbsStatus::IN_PROGRESS->value]);

        // 2. Revert future tasks (start_date in future) that have 0% progress back to not_started
        $futureQuery = static::whereNotNull('start_date')
            ->whereDate('start_date', '>', $today)
            ->where('progress', 0)
            ->whereIn('status', [
                WbsStatus::IN_PROGRESS->value,
                'in_progress'
            ]);

        if ($projectId) {
            $futureQuery->where('project_id', $projectId);
        }

        $futureQuery->update(['status' => WbsStatus::NOT_STARTED->value]);

        return $started;
    }
}

