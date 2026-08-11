<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\ProjectHealth;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'subsidiary_id',
        'category',
        'project_manager_id',
        'created_by',
        'priority',
        'start_date',
        'deadline',
        'status',
        'health',
        'overall_progress',
        'estimated_budget',
        'actual_cost',
        'estimated_hours',
        'actual_hours',
        'wbs_breakdown_type',
    ];

    protected function casts(): array
    {
        return [
            'priority' => Priority::class,
            'status' => ProjectStatus::class,
            'health' => ProjectHealth::class,
            'start_date' => 'date',
            'deadline' => 'date',
            'estimated_budget' => 'decimal:2',
            'actual_cost' => 'decimal:2',
            'estimated_hours' => 'decimal:2',
            'actual_hours' => 'decimal:2',
            'overall_progress' => 'integer',
        ];
    }

    public static function generateCodeForSubsidiary(?int $subsidiaryId): string
    {
        $sub = $subsidiaryId ? Subsidiary::find($subsidiaryId) : null;
        $prefix = $sub && !empty($sub->code) ? strtoupper(trim($sub->code)) : 'GST';

        $count = static::withTrashed()
            ->where('subsidiary_id', $subsidiaryId)
            ->count() + 1;

        $code = "{$prefix}-PRJ-" . sprintf('%03d', $count);
        while (static::where('code', $code)->exists()) {
            $count++;
            $code = "{$prefix}-PRJ-" . sprintf('%03d', $count);
        }

        return $code;
    }

    public function subsidiary(): BelongsTo

    {
        return $this->belongsTo(Subsidiary::class);
    }

    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function wbsItems(): HasMany
    {
        return $this->hasMany(WbsItem::class);
    }

    public function wbsVersions(): HasMany
    {
        return $this->hasMany(WbsVersion::class);
    }

    public function wbsBaselines(): HasMany
    {
        return $this->hasMany(WbsBaseline::class);
    }

    public function approvalRequests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class);
    }

    public function statusUpdates(): HasMany
    {
        return $this->hasMany(ProjectStatusUpdate::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function risks(): HasMany
    {
        return $this->hasMany(ProjectRisk::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
