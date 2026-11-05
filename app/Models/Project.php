<?php

namespace App\Models;

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\ProjectStatus;
use App\Enums\WbsStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $deadline
 * @property \Illuminate\Support\Carbon|null $pm_accepted_at
 * @property \Illuminate\Support\Carbon|null $pm_rejected_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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

    protected static function booted(): void
    {
        static::deleting(function (Project $project) {
            ApprovalRequest::where('project_id', $project->id)->delete();
        });
    }

    public function computeAutoStatus(): ProjectStatus
    {
        if (!$this->exists || !$this->isPmAccepted()) {
            return ProjectStatus::PLANNING;
        }

        // Only deliverables can complete a project, but any WBS item or active risk can start or flag it as At Risk.
        $taskTypes = [ItemType::TASK, ItemType::SUBTASK, ItemType::MILESTONE];
        $allItems = $this->relationLoaded('wbsItems')
            ? $this->wbsItems
            : $this->wbsItems()->get();
        $tasks = $allItems->filter(fn (WbsItem $item) => in_array($item->item_type, $taskTypes, true));

        $hasOpenRisks = $this->relationLoaded('risks')
            ? $this->risks->whereIn('status', ['open', 'monitoring', 'active', 'identified'])->isNotEmpty()
            : $this->risks()->whereIn('status', ['open', 'monitoring', 'active', 'identified'])->exists();

        $hasAtRiskItem = $allItems->contains(fn (WbsItem $item) =>
            in_array($item->status, [WbsStatus::AT_RISK, WbsStatus::BLOCKED], true)
        );

        $hasStarted = $hasOpenRisks || $hasAtRiskItem || $allItems->contains(fn (WbsItem $item) =>
            in_array($item->status, [
                WbsStatus::IN_PROGRESS,
                WbsStatus::COMPLETED,
                WbsStatus::UNDER_REVIEW,
                WbsStatus::AT_RISK,
                WbsStatus::ON_HOLD,
                WbsStatus::BLOCKED,
            ], true)
        );

        // Acceptance, elapsed dates and aggregate progress alone do not start a project.
        if (!$hasStarted) {
            return ProjectStatus::PLANNING;
        }

        if ((int) $this->overall_progress >= 100 || ($tasks->isNotEmpty() && $tasks->every(fn (WbsItem $item) =>
            $item->status === WbsStatus::COMPLETED || (int) $item->progress >= 100
        ))) {
            return ProjectStatus::COMPLETED;
        }

        if ($this->deadline && $this->deadline->copy()->endOfDay()->isPast()) {
            return ProjectStatus::DELAYED;
        }

        if ($hasOpenRisks || $hasAtRiskItem) {
            return ProjectStatus::AT_RISK;
        }

        return ProjectStatus::IN_PROGRESS;
    }

    public function computeAutoPriority(): Priority
    {
        $taskTypes = [ItemType::TASK, ItemType::SUBTASK, ItemType::MILESTONE];
        $items = $this->relationLoaded('wbsItems')
            ? $this->wbsItems
            : $this->wbsItems()->whereIn('item_type', array_map(fn (ItemType $type) => $type->value, $taskTypes))->get();

        $priorityCounts = $items
            ->filter(fn (WbsItem $item) => in_array($item->item_type, $taskTypes, true))
            ->map(fn (WbsItem $item) => $item->priority)
            ->filter(fn ($priority) => $priority instanceof Priority)
            ->countBy(fn (Priority $priority) => $priority->value);

        $highestCount = (int) ($priorityCounts->max() ?? 0);
        if ($highestCount === 0) {
            return Priority::MEDIUM;
        }

        foreach ([Priority::CRITICAL, Priority::HIGH, Priority::MEDIUM, Priority::LOW] as $priority) {
            if ($priorityCounts->get($priority->value, 0) === $highestCount) {
                return $priority;
            }
        }

        return Priority::MEDIUM;
    }
    public function syncAutoPriority(bool $save = true): Priority
    {
        $computed = $this->computeAutoPriority();
        $raw = $this->getRawOriginal('priority');
        if (is_object($raw) && enum_exists(get_class($raw))) {
            $raw = $raw->value;
        }

        if ($raw !== $computed->value) {
            $this->attributes['priority'] = $computed->value;
            if ($save && $this->exists) {
                $this->saveQuietly();
            }
        }

        return $computed;
    }

    public static function syncAllProjectPriorities(): void
    {
        $projects = static::with('wbsItems')->get();
        foreach ($projects as $project) {
            $project->syncAutoPriority(true);
        }
    }
    public function syncAutoStatus(bool $save = true): ProjectStatus
    {
        $computed = $this->computeAutoStatus();
        $raw = $this->getRawOriginal('status');
        if (is_object($raw) && enum_exists(get_class($raw))) {
            $raw = $raw->value;
        }

        if ($raw !== $computed->value) {
            $this->attributes['status'] = $computed->value;
            if ($save && $this->exists) {
                $this->saveQuietly();
                \App\Services\AuditLogService::log(
                    action: 'auto_synced_project_status',
                    module: 'projects',
                    record: $this,
                    previous: ['status' => $raw],
                    new: ['status' => $computed->value, 'reason' => 'Automatically synchronized from task schedule & progress'],
                    actorType: \App\Models\ActivityLog::ACTOR_SYSTEM
                );
            }
        }

        return $computed;
    }

    public static function syncAllProjectStatuses(): void
    {
        \App\Services\AuditLogService::asMaintenance(function () {
            $projects = static::with(['wbsItems', 'risks'])->get();
            foreach ($projects as $project) {
                $project->syncAutoStatus(true);
            }
        });
    }

    public function getComputedHealthAttribute(): string
    {
        $statusVal = is_object($this->status) ? $this->status->value : (string) ($this->status ?? 'in_progress');

        if ($statusVal === 'completed') {
            return 'completed';
        }

        // Check if project deadline is overdue
        $isOverdue = $this->deadline
            && \Carbon\Carbon::parse($this->deadline)->endOfDay()->isPast()
            && ($this->overall_progress ?? 0) < 100
            && !in_array($statusVal, ['completed', 'cancelled']);

        if ($statusVal === 'delayed' || $isOverdue) {
            return 'delayed';
        }

        // Check if project has open risks or at-risk/blocked tasks
        $hasOpenRisks = $this->relationLoaded('risks')
            ? $this->risks->whereIn('status', ['open', 'monitoring', 'active', 'identified'])->isNotEmpty()
            : $this->risks()->whereIn('status', ['open', 'monitoring', 'active', 'identified'])->exists();

        $hasBlockedTasks = $this->relationLoaded('wbsItems')
            ? ($this->wbsItems->contains(fn (WbsItem $w) => in_array($w->status, [WbsStatus::AT_RISK, WbsStatus::BLOCKED], true)) || $this->wbsItems->some(fn($w) => $w->relationLoaded('blockers') && $w->blockers->where('status', 'open')->isNotEmpty()))
            : $this->wbsItems()->whereIn('status', ['at_risk', 'blocked'])->exists();

        if ($statusVal === 'at_risk' || $hasOpenRisks || $hasBlockedTasks) {
            return 'at_risk';
        }

        if ($statusVal === 'on_hold') {
            return 'on_hold';
        }

        if ($statusVal === 'planning') {
            return 'planning';
        }

        if ($statusVal === 'in_progress') {
            return 'in_progress';
        }

        return 'in_progress';
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

    public function canApproveAssignment(User $user): bool
    {
        return $this->project_manager_id === $user->id;
    }

    public function acceptByPm(User $user): bool
    {
        return DB::transaction(function () use ($user): bool {
            $current = static::query()->lockForUpdate()->findOrFail($this->getKey());
            if (!$current->canApproveAssignment($user)) {
                return false;
            }
            $this->setRawAttributes($current->getAttributes(), true);

            $this->update([
                'pm_accepted' => true,
                'pm_accepted_at' => now(),
                'pm_rejection_reason' => null,
                'pm_rejected_at' => null,
            ]);

            $this->syncAutoStatus();

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

            // Notify all team members (sponsors, owners, committee, members) that project is now active & accepted
            try {
                $members = $this->members()->where('users.id', '!=', $user->id)->get();
                foreach ($members as $member) {
                    $role = $member->pivot->role ?? 'member';
                    $member->notify(new \App\Notifications\ProjectAssignmentNotification($this, $role));
                    \Illuminate\Support\Facades\Mail::to($member->email)
                        ->send(new \App\Mail\ProjectAssignedMail($this, $member, $role));
                }

                // Notify any non-PM users assigned to tasks created before PM acceptance
                $assignedTasks = $this->wbsItems()
                    ->whereNotNull('assigned_user_id')
                    ->where('assigned_user_id', '!=', $user->id)
                    ->with('assignedUser')
                    ->get();
                foreach ($assignedTasks as $task) {
                    if ($task->assignedUser) {
                        $task->assignedUser->notify(new \App\Notifications\TaskAssignedNotification(
                            taskTitle: $task->title,
                            projectName: $this->name,
                            assignedByName: $user->name,
                            dueDate: $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('M d, Y') : null,
                            url: route('my-tasks.index')
                        ));
                    }
                }
            } catch (\Throwable $e) {
                \Log::error('ProjectAssignmentNotification upon PM acceptance failed: ' . $e->getMessage());
            }


            return true;
        });
    }

    public function rejectByPm(User $user, string $reason): bool
    {
        return DB::transaction(function () use ($user, $reason): bool {
            $current = static::query()->lockForUpdate()->findOrFail($this->getKey());
            if ($current->project_manager_id !== $user->id) {
                return false;
            }
            $this->setRawAttributes($current->getAttributes(), true);

            $this->update([
            'pm_accepted' => false,
            'pm_accepted_at' => null,
            'pm_rejection_reason' => $reason,
            'pm_rejected_at' => now(),
        ]);

        $this->syncAutoStatus();

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
        });
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

    /**
     * Check if a user is a member or manager of this project.
     */
    public function isMember(User|int|null $user): bool
    {
        if (!$user) return false;
        $userId = $user instanceof User ? $user->id : (int) $user;
        if ($this->project_manager_id === $userId) {
            return true;
        }
        return $this->members()->where('users.id', $userId)->exists();
    }

    /**
     * Get the specific role of a user in this project.
     * Returns: 'lead', 'sponsor', 'owner', 'steering_committee', 'member', or null.
     */
    public function getUserRole(User|int|null $user): ?string
    {
        if (!$user) return null;
        $userId = $user instanceof User ? $user->id : (int) $user;
        $uObj = $user instanceof User ? $user : User::find($userId);

        if ($this->project_manager_id === $userId) {
            return 'lead';
        }

        $member = $this->members()->where('users.id', $userId)->first();
        $pivotRole = $member?->pivot?->role;

        if ($pivotRole && $pivotRole !== 'member') {
            return $pivotRole;
        }

        if ($uObj) {
            $spatieRole = $uObj->roles->first()?->name;
            if ($spatieRole && !in_array($spatieRole, ['super_admin', 'pmo_admin', 'lead', 'project_manager', 'member', 'team_member', 'regular_user'])) {
                return $spatieRole;
            }
        }

        return $pivotRole ?: ($member ? 'member' : null);
    }

    /**
     * Check if a user is part of the project leadership or governance panel
     * (Project Leader/PM, Project Sponsor, Project Owner, or Steering Committee).
     */
    public function isGovernanceMember(User|int|null $user): bool
    {
        if (!$user) return false;
        $role = $this->getUserRole($user);
        return in_array($role, ['lead', 'sponsor', 'owner', 'steering_committee']);
    }

    /**
     * Check if a user has management permissions on this project
     * (PMO Admins, Super Admins, or any project governance member: Lead, Sponsor, Owner, Steering Committee).
     */
    public function canUserManage(User|int|null $user): bool
    {
        if (!$user) return false;
        $u = $user instanceof User ? $user : User::find($user);
        if (!$u || $u->isAdminViewer()) return false;

        if ($u->isSuperAdmin() || $u->hasRole('pmo_admin')) {
            return true;
        }

        if (!$this->pm_accepted && $this->project_manager_id !== $u->id) {
            return false;
        }

        return $this->isGovernanceMember($u);
    }

    /**
     * Check if a user has permission to view this project's details.
     */
    public function canUserView(User|int|null $user): bool
    {
        if (!$user) return false;
        $u = $user instanceof User ? $user : User::find($user);
        if (!$u) return false;

        if ($u->hasAdminInterfaceAccess()) {
            return true;
        }

        if ($this->project_manager_id === $u->id) {
            return true;
        }

        if (!$this->pm_accepted) {
            return false;
        }

        if ($this->members()->where('users.id', $u->id)->exists()) {
            return true;
        }

        return $this->wbsItems()->where('assigned_user_id', $u->id)->exists();
    }

    /**
     * Check if a user has a specific granular project permission on this project.
     */
    public function userCan(User|int|null $user, string $permission, ?WbsItem $task = null): bool
    {
        if (!$user) return false;
        $u = $user instanceof User ? $user : User::find($user);
        return $u ? $u->hasProjectPermission($permission, $this, $task) : false;
    }
}

