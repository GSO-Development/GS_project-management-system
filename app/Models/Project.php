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
        'pm_accepted',
        'pm_accepted_at',
        'pm_rejection_reason',
        'pm_rejected_at',
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
        'template_id',
    ];

    protected function casts(): array
    {
        return [
            'priority' => Priority::class,
            'status' => ProjectStatus::class,
            'health' => ProjectHealth::class,
            'pm_accepted' => 'boolean',
            'pm_accepted_at' => 'datetime',
            'pm_rejected_at' => 'datetime',
            'start_date' => 'date',
            'deadline' => 'date',
            'estimated_budget' => 'decimal:2',
            'actual_cost' => 'decimal:2',
            'estimated_hours' => 'decimal:2',
            'actual_hours' => 'decimal:2',
            'overall_progress' => 'integer',
        ];
    }

    public function isPmAccepted(): bool
    {
        return (bool) $this->pm_accepted;
    }

    public function isPmRejected(): bool
    {
        return !$this->pm_accepted && !empty($this->pm_rejection_reason);
    }

    public function isPendingPmAcceptance(): bool
    {
        return !$this->pm_accepted && empty($this->pm_rejection_reason);
    }

    public function acceptByPm(User $user): bool
    {
        if ($this->project_manager_id !== $user->id && !$user->hasRole('super_admin')) {
            return false;
        }

        $this->update([
            'pm_accepted' => true,
            'pm_accepted_at' => now(),
            'pm_rejection_reason' => null,
            'pm_rejected_at' => null,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'accepted_project_assignment',
            'module' => 'projects',
            'record_type' => self::class,
            'record_id' => $this->id,
            'new_values' => [
                'pm_accepted' => true,
                'pm_accepted_at' => now()->toDateTimeString(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Sync any pending ApprovalRequest of type NEW_PROJECT_PLAN
        ApprovalRequest::where('project_id', $this->id)
            ->where('request_type', \App\Enums\ApprovalType::NEW_PROJECT_PLAN)
            ->where('status', \App\Enums\ApprovalStatus::PENDING)
            ->update([
                'status' => \App\Enums\ApprovalStatus::APPROVED,
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
                'review_comment' => 'Project Leadership Accepted & Confirmed by ' . $user->name,
            ]);

        // Notify PMO Super Admins that the project was accepted
        $pmoAdmins = User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->get();
        foreach ($pmoAdmins as $admin) {
            $admin->notify(new \App\Notifications\ProjectLeaderDecisionNotification($this, $user, 'accepted'));
        }

        return true;
    }

    public function rejectByPm(User $user, string $reason): bool
    {
        if ($this->project_manager_id !== $user->id && !$user->hasRole('super_admin')) {
            return false;
        }

        $this->update([
            'pm_accepted' => false,
            'pm_accepted_at' => null,
            'pm_rejection_reason' => $reason,
            'pm_rejected_at' => now(),
        ]);

        // Sync any pending ApprovalRequest of type NEW_PROJECT_PLAN
        ApprovalRequest::where('project_id', $this->id)
            ->where('request_type', \App\Enums\ApprovalType::NEW_PROJECT_PLAN)
            ->where('status', \App\Enums\ApprovalStatus::PENDING)
            ->update([
                'status' => \App\Enums\ApprovalStatus::REJECTED,
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
                'review_comment' => $reason,
            ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'rejected_project_assignment',
            'module' => 'projects',
            'record_type' => self::class,
            'record_id' => $this->id,
            'new_values' => [
                'pm_accepted' => false,
                'pm_rejection_reason' => $reason,
                'pm_rejected_at' => now()->toDateTimeString(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Notify PMO Super Admins about the rejection with the issue
        $pmoAdmins = User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->get();
        foreach ($pmoAdmins as $admin) {
            $admin->notify(new \App\Notifications\ProjectLeaderDecisionNotification($this, $user, 'rejected', $reason));
        }

        return true;
    }

    public static function generateCodeForSubsidiary(?int $subsidiaryId): string
    {
        $sub = $subsidiaryId ? Subsidiary::find($subsidiaryId) : null;
        $prefix = $sub && !empty($sub->code) ? strtoupper(trim($sub->code)) : 'GST';

        $count = static::withTrashed()
            ->where('subsidiary_id', $subsidiaryId)
            ->count() + 1;

        $code = "{$prefix}-PRJ-" . sprintf('%03d', $count);
        while (static::withTrashed()->where('code', $code)->exists()) {
            $count++;
            $code = "{$prefix}-PRJ-" . sprintf('%03d', $count);
        }

        return $code;
    }

    public function subsidiary(): BelongsTo
    {
        return $this->belongsTo(Subsidiary::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ProjectTemplate::class, 'template_id');
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
