<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProjectStatusUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'wbs_item_id',
        'title',
        'summary',
        'reporting_period',
        'current_progress',
        'work_completed',
        'current_blockers',
        'current_risks',
        'next_steps',
        'updated_status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'updated_status' => ProjectStatus::class,
            'current_progress' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function wbsItem(): BelongsTo
    {
        return $this->belongsTo(WbsItem::class, 'wbs_item_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }
}
