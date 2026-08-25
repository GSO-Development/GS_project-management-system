<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Models\WbsItem;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RbacService
{
    /**
     * Cache for role permissions during the current request lifecycle.
     */
    protected static array $rolePermissionsCache = [];

    /**
     * Map of standard project role codes to human-readable names and icons.
     */
    public const ROLES = [
        'sponsor' => [
            'name' => 'Project Sponsor',
            'code' => 'sponsor',
            'icon' => '💼',
            'badge' => 'bg-amber-50 text-amber-800 border-amber-200',
            'description' => 'Executive sponsor with oversight, budget governance, and final sign-off authority.',
        ],
        'owner' => [
            'name' => 'Project Owner',
            'code' => 'owner',
            'icon' => '👑',
            'badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'description' => 'Business owner responsible for project outcomes, scope verification, and milestone sign-offs.',
        ],
        'steering_committee' => [
            'name' => 'Steering Committee',
            'code' => 'steering_committee',
            'icon' => '🏛️',
            'badge' => 'bg-violet-50 text-violet-800 border-violet-200',
            'description' => 'Governance board member reviewing strategic alignment, risks, and high-level milestones.',
        ],
        'lead' => [
            'name' => 'Project Manager',
            'code' => 'lead',
            'icon' => '⭐',
            'badge' => 'bg-rose-50 text-[#c3122e] border-rose-200',
            'description' => 'Operational leader managing day-to-day WBS scheduling, task execution, and team assignments.',
        ],
        'member' => [
            'name' => 'Core Project Team',
            'code' => 'member',
            'icon' => '🤝',
            'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
            'description' => 'Active team collaborator executing assigned WBS deliverables and providing status updates.',
        ],
    ];

    /**
     * Module definitions with all 73 granular permission codes.
     */
    public const MODULES = [
        'project' => [
            'key' => 'project',
            'name' => 'Project',
            'icon' => '📁',
            'permissions' => [
                'project.view' => 'View Project',
                'project.view_all' => 'View All Projects',
                'project.view_assigned' => 'View Assigned Projects',
                'project.create' => 'Create Project',
                'project.edit' => 'Edit Project',
                'project.delete' => 'Delete Project',
                'project.archive' => 'Archive Project',
                'project.change_status' => 'Change Project Status',
                'project.manage_scope' => 'Manage Scope',
                'project.manage_schedule' => 'Manage Schedule',
                'project.manage_milestones' => 'Manage Milestones',
            ],
        ],
        'scope' => [
            'key' => 'scope',
            'name' => 'Scope / Project Details',
            'icon' => '📝',
            'permissions' => [
                'project_details.view' => 'View Project Details',
                'project_details.edit' => 'Edit Project Details',
                'scope.view' => 'View Scope',
                'scope.edit' => 'Edit Scope',
                'scope.approve' => 'Approve Scope Change',
                'schedule.edit' => 'Edit Project Dates',
            ],
        ],
        'task' => [
            'key' => 'task',
            'name' => 'WBS & Tasks',
            'icon' => '📋',
            'permissions' => [
                'task.view' => 'View Tasks',
                'task.view_all' => 'View All Tasks',
                'task.view_assigned' => 'View Assigned Tasks',
                'task.create' => 'Create New Task',
                'task.edit' => 'Edit Task',
                'task.edit_assigned' => 'Edit Assigned Task',
                'task.delete' => 'Delete Task',
                'task.assign' => 'Assign Task',
                'task.reassign' => 'Reassign Task',
                'task.change_status' => 'Update Task Status',
                'task.update_progress' => 'Update Task Progress',
                'task.change_priority' => 'Change Task Priority',
                'task.change_due_date' => 'Change Due Date',
                'task.create_subtask' => 'Create Subtask',
                'task.comment' => 'Add Comment',
                'task.upload_attachment' => 'Upload Attachment',
                'task.complete' => 'Complete Task',
            ],
        ],
        'team' => [
            'key' => 'team',
            'name' => 'Team',
            'icon' => '👥',
            'permissions' => [
                'team.view' => 'View Project Team',
                'team.add' => 'Add Project Member',
                'team.remove' => 'Remove Project Member',
                'team.assign_role' => 'Assign Project Role',
                'team.change_role' => 'Change Project Role',
                'team.view_member' => 'View Member Details',
            ],
        ],
        'budget' => [
            'key' => 'budget',
            'name' => 'Budget / Finance',
            'icon' => '💰',
            'permissions' => [
                'budget.view' => 'View Budget',
                'budget.view_estimated' => 'View Estimated Budget',
                'budget.edit_estimated' => 'Edit Estimated Budget',
                'budget.view_actual' => 'View Actual Cost',
                'budget.edit_actual' => 'Edit Actual Cost',
                'budget.submit_change' => 'Submit Budget Change',
                'budget.approve' => 'Approve Budget Change',
                'budget.report' => 'View Financial Reports',
            ],
        ],
        'risks' => [
            'key' => 'risks',
            'name' => 'Risks & Blockers',
            'icon' => '⚠️',
            'permissions' => [
                'risk.view' => 'View Risks',
                'risk.create' => 'Create Risk',
                'risk.edit' => 'Edit Risk',
                'risk.delete' => 'Delete Risk',
                'risk.assign' => 'Assign Risk',
                'risk.escalate' => 'Escalate Risk',
                'risk.resolve' => 'Resolve Risk',
                'blocker.create' => 'Create Blocker',
                'blocker.edit' => 'Edit Blocker',
                'blocker.resolve' => 'Resolve Blocker',
            ],
        ],
        'approvals' => [
            'key' => 'approvals',
            'name' => 'Approvals',
            'icon' => '✅',
            'permissions' => [
                'approval.view' => 'View Approval Requests',
                'approval.submit' => 'Submit Approval Request',
                'approval.approve' => 'Approve Request',
                'approval.reject' => 'Reject Request',
                'approval.return' => 'Return for Changes',
                'approval.final_approve' => 'Final Approval',
            ],
        ],
        'reports' => [
            'key' => 'reports',
            'name' => 'Reports',
            'icon' => '📊',
            'permissions' => [
                'report.view' => 'View Reports',
                'report.project' => 'View Project Reports',
                'report.financial' => 'View Financial Reports',
                'report.performance' => 'View Performance Reports',
                'report.export' => 'Export Reports',
            ],
        ],
        'settings' => [
            'key' => 'settings',
            'name' => 'Project Settings',
            'icon' => '⚙️',
            'permissions' => [
                'project_settings.view' => 'View Project Settings',
                'project_settings.edit' => 'Edit Project Settings',
                'project_settings.configure' => 'Manage Project Configuration',
                'project_settings.manage_roles' => 'Manage Project Roles',
            ],
        ],
    ];

    /**
     * Check if a user has a specific permission in a project context.
     */
    public static function checkPermission(?User $user, string $permission, Project|int|null $project = null, ?WbsItem $task = null): bool
    {
        if (!$user) {
            return false;
        }

        // PMO Admin / Super Admin always has 100% full system access
        if ($user->isSuperAdmin() || $user->isPmoAdmin()) {
            return true;
        }

        // 1. If project context is provided
        if ($project) {
            $projectModel = $project instanceof Project ? $project : Project::find($project);
            if (!$projectModel) {
                return false;
            }

            $roleCode = $projectModel->getUserRole($user);

            // User has no role in this project
            if (!$roleCode) {
                // If user is assigned to a specific task, check task-level access
                if ($task && $task->assigned_user_id === $user->id) {
                    $memberPermissions = self::getPermissionsForRole('member');
                    if (in_array($permission, ['task.view', 'task.view_assigned', 'task.edit_assigned', 'task.change_status', 'task.update_progress', 'task.comment', 'task.upload_attachment', 'task.complete'])) {
                        return in_array($permission, $memberPermissions);
                    }
                }
                return false;
            }

            // Get permissions granted to this role
            $rolePermissions = self::getPermissionsForRole($roleCode);

            // If task-specific edit check:
            if ($permission === 'task.edit') {
                if (in_array('task.edit', $rolePermissions)) {
                    return true;
                }
                // If user has edit_assigned and is assigned to this task
                if (in_array('task.edit_assigned', $rolePermissions) && $task && $task->assigned_user_id === $user->id) {
                    return true;
                }
                return false;
            }

            // If task-specific view check:
            if ($permission === 'task.view') {
                if (in_array('task.view', $rolePermissions) || in_array('task.view_all', $rolePermissions)) {
                    return true;
                }
                if (in_array('task.view_assigned', $rolePermissions) && $task && $task->assigned_user_id === $user->id) {
                    return true;
                }
                return in_array('task.view_assigned', $rolePermissions);
            }

            return in_array($permission, $rolePermissions);
        }

        // 2. Global / Unscoped Check (checks all projects the user is involved with or system roles)
        foreach ($user->projects as $prj) {
            $roleCode = $prj->pivot->role ?? 'member';
            if (in_array($permission, self::getPermissionsForRole($roleCode))) {
                return true;
            }
        }

        if ($user->managedProjects()->exists() && in_array($permission, self::getPermissionsForRole('lead'))) {
            return true;
        }

        return false;
    }

    /**
     * Get all active permission codes for a role (with in-memory caching).
     */
    public static function getPermissionsForRole(string $roleCode): array
    {
        if (isset(self::$rolePermissionsCache[$roleCode])) {
            return self::$rolePermissionsCache[$roleCode];
        }

        $role = Role::where('name', $roleCode)->first();
        if (!$role) {
            // Check aliases
            $alias = match($roleCode) {
                'lead' => 'project_manager',
                'member' => 'team_member',
                'project_manager' => 'lead',
                'team_member' => 'member',
                default => null,
            };
            if ($alias) {
                $role = Role::where('name', $alias)->first();
            }
        }

        if (!$role) {
            return self::$rolePermissionsCache[$roleCode] = [];
        }

        $perms = $role->permissions->pluck('name')->toArray();
        return self::$rolePermissionsCache[$roleCode] = $perms;
    }

    /**
     * Clear the in-memory and Spatie permission caches.
     */
    public static function clearCache(): void
    {
        self::$rolePermissionsCache = [];
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Compute matrix module status for a role.
     * Returns: ['status' => 'Full Access'|'View Only'|'Assigned Only'|'Custom'|'No Access', 'badgeClass' => '...']
     */
    public static function computeModuleSummary(string $roleCode, string $moduleKey): array
    {
        $rolePermissions = self::getPermissionsForRole($roleCode);
        $moduleDef = self::MODULES[$moduleKey] ?? null;

        if (!$moduleDef) {
            return ['label' => 'No Access', 'badgeClass' => 'bg-slate-100 text-slate-500 border-slate-200'];
        }

        $modulePermKeys = array_keys($moduleDef['permissions']);
        $totalModulePerms = count($modulePermKeys);
        $grantedPerms = array_intersect($modulePermKeys, $rolePermissions);
        $grantedCount = count($grantedPerms);

        if ($grantedCount === 0) {
            return [
                'label' => 'No Access',
                'badgeClass' => 'bg-rose-50 text-rose-700 border-rose-200 font-bold',
                'count' => 0,
                'total' => $totalModulePerms,
            ];
        }

        if ($grantedCount === $totalModulePerms) {
            return [
                'label' => 'Full Access',
                'badgeClass' => 'bg-emerald-50 text-emerald-700 border-emerald-200 font-extrabold',
                'count' => $grantedCount,
                'total' => $totalModulePerms,
            ];
        }

        // Check if only view permissions are granted
        $isOnlyView = true;
        foreach ($grantedPerms as $p) {
            if (!str_contains($p, 'view') && !str_contains($p, 'report')) {
                $isOnlyView = false;
                break;
            }
        }

        if ($isOnlyView) {
            return [
                'label' => 'View Only',
                'badgeClass' => 'bg-blue-50 text-blue-700 border-blue-200 font-bold',
                'count' => $grantedCount,
                'total' => $totalModulePerms,
            ];
        }

        // Check if assigned-only permissions
        if ($moduleKey === 'task' && in_array('task.view_assigned', $grantedPerms) && !in_array('task.view_all', $grantedPerms) && !in_array('task.create', $grantedPerms)) {
            return [
                'label' => 'Assigned Only',
                'badgeClass' => 'bg-purple-50 text-purple-700 border-purple-200 font-bold',
                'count' => $grantedCount,
                'total' => $totalModulePerms,
            ];
        }

        return [
            'label' => 'Custom',
            'badgeClass' => 'bg-amber-50 text-amber-800 border-amber-200 font-bold',
            'count' => $grantedCount,
            'total' => $totalModulePerms,
        ];
    }
}
