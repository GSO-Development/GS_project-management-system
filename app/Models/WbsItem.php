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
        'end_date',
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
            'is_milestone' => 'boolean',
            'weight' => 'decimal:4',
            'estimated_hours' => 'decimal:2',
            'actual_hours' => 'decimal:2',
            'progress' => 'integer',
            'delay_reason_at' => 'datetime',
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
     * Automatically transition tasks whose start_date has arrived to in_progress
     */
    public static function autoStartDueTasks(?int $projectId = null): int
    {
        $today = now()->today()->toDateString();

        $query = static::whereNotNull('start_date')
            ->whereDate('start_date', '<=', $today)
            ->whereIn('status', [
                WbsStatus::NOT_STARTED->value, 
                WbsStatus::BACKLOG->value, 
                'not_started', 
                'backlog', 
                'draft'
            ]);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        return $query->update(['status' => WbsStatus::IN_PROGRESS->value]);
    }
}
