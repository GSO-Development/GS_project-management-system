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
     * Core system roles that are permanently protected and cannot be deleted.
     * project manager (lead/project_manager), PMO admin/superadmin (pmo_admin, super_admin),
     * Project owner (owner), Project Sponsor (sponsor), Core Project Team (member/team_member).
     */
    public const PROTECTED_ROLES = [
        'super_admin',
        'pmo_admin',
        'lead',
        'project_manager',
        'sponsor',
        'owner',
        'steering_committee',
        'member',
        'team_member',
        'collaborator',
    ];

    /**
     * Check if a role code is one of the core protected roles.
     */
    public static function isProtectedRole(?string $code): bool
    {
        if (!$code) {
            return false;
        }
        $code = strtolower($code);
        return isset(self::ROLES[$code]) || in_array($code, self::PROTECTED_ROLES, true);
    }

    /**
     * Standard built-in role definitions with metadata.
     */
    public const ROLES = [
        'pmo_admin' => [
            'name' => 'PMO Administrator',
            'code' => 'pmo_admin',
            'icon' => '🏛️',
            'badge' => 'bg-rose-50 text-[#c3122e] border-rose-200 font-extrabold',
            'description' => 'Enterprise PMO authority overseeing subsidiary portfolios, workflows, and cross-project governance.',
            'is_system' => true,
            'is_protected' => true,
        ],
        'lead' => [
            'name' => 'Project Manager',
            'code' => 'lead',
            'icon' => '⭐',
            'badge' => 'bg-rose-50 text-[#c3122e] border-rose-200',
            'description' => 'Project-level leader with full access to project details, WBS scheduling, task creation, editing & deletion.',
            'is_system' => true,
            'is_protected' => true,
        ],
        'sponsor' => [
            'name' => 'Project Sponsor',
            'code' => 'sponsor',
            'icon' => '💼',
            'badge' => 'bg-amber-50 text-amber-800 border-amber-200',
            'description' => 'Project-level executive sponsor with view-only oversight on project details & tasks, plus budget sign-off.',
            'is_system' => true,
            'is_protected' => true,
        ],
        'owner' => [
            'name' => 'Project Owner',
            'code' => 'owner',
            'icon' => '👑',
            'badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'description' => 'Project-level business owner with view-only access to project details & tasks, and scope sign-off authority.',
            'is_system' => true,
            'is_protected' => true,
        ],
        'steering_committee' => [
            'name' => 'Steering Committee',
            'code' => 'steering_committee',
            'icon' => '🏛️',
            'badge' => 'bg-violet-50 text-violet-800 border-violet-200',
            'description' => 'Project-level governance board member with view-only access to project details, tasks, and strategic milestones.',
            'is_system' => true,
            'is_protected' => true,
        ],
        'member' => [
            'name' => 'Core Project Team',
            'code' => 'member',
            'icon' => '🤝',
            'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
            'description' => 'Project team member assigned to specific projects. Can view assigned project & tasks, update status/progress (no task create/delete).',
            'is_system' => true,
            'is_protected' => true,
        ],
        'collaborator' => [
            'name' => 'Collaborator / Specialist',
            'code' => 'collaborator',
            'icon' => '⚡',
            'badge' => 'bg-cyan-50 text-cyan-800 border-cyan-200',
            'description' => 'Specialist contributor assisting on specific deliverables with view-only project access & task status updates.',
            'is_system' => true,
            'is_protected' => true,
        ],
    ];

    /**
     * Dynamically get all roles from DB merged with standard metadata.
     */
    public static function getAllRoles(): array
    {
        $dbRoles = Role::all();
        $roles = [];

        foreach ($dbRoles as $role) {
            $code = $role->name;
            // Filter out super_admin as Superadmin & PMO admin are unified under PMO Administrator
            if ($code === 'super_admin') {
                continue;
            }
            // Normalize aliases
            if ($code === 'project_manager' && !isset(self::ROLES['project_manager'])) {
                continue; // mapped under lead
            }
            if ($code === 'team_member' && !isset(self::ROLES['team_member'])) {
                continue; // mapped under member
            }

            if (isset(self::ROLES[$code])) {
                $roles[$code] = self::ROLES[$code];
                $roles[$code]['id'] = $role->id;
                $roles[$code]['users_count'] = $role->users()->count();
                $roles[$code]['is_protected'] = self::isProtectedRole($code);
            } else {
                $roles[$code] = [
                    'id' => $role->id,
                    'name' => ucwords(str_replace(['_', '-'], ' ', $code)),
                    'code' => $code,
                    'icon' => '🏷️',
                    'badge' => 'bg-slate-100 text-slate-800 border-slate-200',
                    'description' => 'Custom user-defined governance role in GS NexusPM.',
                    'is_system' => false,
                    'is_protected' => false,
                    'users_count' => $role->users()->count(),
                ];
            }
        }

        // Ensure ONLY core protected built-in roles appear even if not yet in DB table
        foreach (self::ROLES as $code => $def) {
            if (!empty($def['is_protected']) && !isset($roles[$code])) {
                $roles[$code] = $def;
                $roles[$code]['id'] = null;
                $roles[$code]['users_count'] = 0;
            }
        }

        return $roles;
    }

    /**
     * Module definitions with granular permission codes.
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
                'schedule.view_impact' => 'View Schedule Impact Preview',
                'schedule.apply_cascade' => 'Apply Cascade Rescheduling',
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
        'calendar' => [
            'key' => 'calendar',
            'name' => 'Calendar & Microsoft 365',
            'icon' => '📅',
            'permissions' => [
                'calendar.view' => 'View Calendar',
                'calendar.view_all' => 'View All Project Calendars',
                'calendar.create' => 'Schedule Event / Meeting',
                'calendar.edit' => 'Edit Calendar Event',
                'calendar.delete' => 'Delete Calendar Event',
                'calendar.sync_outlook' => 'Sync to Microsoft Outlook',
                'calendar.export' => 'Export Calendar (.ICS / Feed)',
            ],
        ],
    ];

    /**
     * Dynamically discover all permissions from DB and map into modules.
     */
    public static function getAllModules(): array
    {
        $modules = self::MODULES;
        $dbPermissions = Permission::all();

        // Check if there are any DB permissions not listed in standard modules
        $knownCodes = [];
        foreach ($modules as $m) {
            $knownCodes = array_merge($knownCodes, array_keys($m['permissions']));
        }

        foreach ($dbPermissions as $perm) {
            if (!in_array($perm->name, $knownCodes)) {
                $prefix = explode('.', $perm->name)[0] ?? 'custom';
                if (isset($modules[$prefix])) {
                    $modules[$prefix]['permissions'][$perm->name] = ucwords(str_replace(['_', '.'], ' ', $perm->name));
                } else {
                    if (!isset($modules['custom'])) {
                        $modules['custom'] = [
                            'key' => 'custom',
                            'name' => 'Custom & Additional Rights',
                            'icon' => '🔐',
                            'permissions' => [],
                        ];
                    }
                    $modules['custom']['permissions'][$perm->name] = ucwords(str_replace(['_', '.'], ' ', $perm->name));
                }
            }
        }

        return $modules;
    }

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

        // 2. Global / Unscoped Check
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
     * Get default permissions mapping for standard project governance & system roles.
     */
    public static function getDefaultPermissionsForRole(string $roleCode): array
    {
        return match($roleCode) {
            'super_admin', 'pmo_admin' => Permission::pluck('name')->toArray(),
            'lead', 'project_manager' => [
                'project.view', 'project.view_all', 'project.view_assigned', 'project.edit', 'project.manage_scope', 'project.manage_schedule', 'project.manage_milestones',
                'project_details.view', 'project_details.edit', 'scope.view', 'scope.edit', 'schedule.edit',
                'task.view', 'task.view_all', 'task.view_assigned', 'task.create', 'task.edit', 'task.edit_assigned', 'task.delete', 'task.assign', 'task.reassign', 'task.change_status', 'task.update_progress', 'task.change_priority', 'task.change_due_date', 'task.create_subtask', 'task.comment', 'task.upload_attachment', 'task.complete', 'schedule.view_impact', 'schedule.apply_cascade',
                'team.view', 'team.add', 'team.remove', 'team.assign_role', 'team.change_role', 'team.view_member',
                'budget.view', 'budget.view_estimated', 'budget.view_actual', 'budget.edit_estimated', 'budget.edit_actual', 'budget.submit_change', 'budget.report',
                'risk.view', 'risk.create', 'risk.edit', 'risk.assign', 'risk.escalate', 'risk.resolve', 'blocker.create', 'blocker.edit', 'blocker.resolve',
                'approval.view', 'approval.submit',
                'report.view', 'report.project', 'report.financial', 'report.performance', 'report.export',
                'project_settings.view', 'project_settings.edit',
                'calendar.view', 'calendar.create', 'calendar.edit', 'calendar.delete', 'calendar.sync_outlook', 'calendar.export',
            ],
            'member', 'team_member' => [
                'project.view_assigned',
                'project_details.view', 'scope.view',
                'task.view', 'task.view_assigned', 'task.edit_assigned', 'task.change_status', 'task.update_progress', 'task.comment', 'task.upload_attachment', 'task.complete', 'schedule.view_impact',
                'team.view', 'team.view_member',
                'risk.view', 'risk.create', 'blocker.create',
                'approval.view', 'approval.submit',
                'calendar.view', 'calendar.export',
            ],
            'collaborator' => [
                'project.view_assigned',
                'project_details.view', 'scope.view',
                'task.view', 'task.view_assigned', 'task.edit_assigned', 'task.change_status', 'task.update_progress', 'task.comment', 'task.upload_attachment', 'task.complete',
                'team.view', 'team.view_member',
                'risk.view', 'blocker.create',
                'calendar.view', 'calendar.export',
            ],
            'sponsor' => [
                'project.view', 'project.view_all', 'project.view_assigned',
                'project_details.view', 'scope.view',
                'task.view', 'task.view_all', 'task.view_assigned', 'schedule.view_impact',
                'team.view', 'team.view_member',
                'budget.view', 'budget.view_estimated', 'budget.view_actual', 'budget.report',
                'risk.view',
                'approval.view', 'approval.final_approve',
                'report.view', 'report.project', 'report.financial', 'report.performance', 'report.export',
                'project_settings.view',
                'calendar.view', 'calendar.export',
            ],
            'owner' => [
                'project.view', 'project.view_assigned',
                'project_details.view', 'scope.view',
                'task.view', 'task.view_all', 'task.view_assigned', 'schedule.view_impact',
                'team.view', 'team.view_member',
                'budget.view', 'budget.view_estimated', 'budget.view_actual', 'budget.submit_change', 'budget.report',
                'risk.view',
                'approval.view', 'approval.submit', 'approval.approve', 'approval.reject', 'approval.return',
                'report.view', 'report.project', 'report.financial', 'report.performance', 'report.export',
                'project_settings.view',
                'calendar.view', 'calendar.export',
            ],
            'steering_committee' => [
                'project.view', 'project.view_assigned',
                'project_details.view', 'scope.view',
                'task.view', 'task.view_all', 'task.view_assigned', 'schedule.view_impact',
                'team.view', 'team.view_member',
                'budget.view', 'budget.view_estimated', 'budget.view_actual', 'budget.report',
                'risk.view',
                'approval.view', 'approval.approve', 'approval.reject',
                'report.view', 'report.project', 'report.financial', 'report.performance', 'report.export',
                'project_settings.view',
                'calendar.view', 'calendar.export',
            ],
            default => [],
        };
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
     */
    public static function computeModuleSummary(string $roleCode, string $moduleKey): array
    {
        $rolePermissions = self::getPermissionsForRole($roleCode);
        $allModules = self::getAllModules();
        $moduleDef = $allModules[$moduleKey] ?? (self::MODULES[$moduleKey] ?? null);

        if (!$moduleDef) {
            return ['label' => 'No Access', 'badgeClass' => 'bg-slate-100 text-slate-500 border-slate-200', 'count' => 0, 'total' => 0];
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

        $isOnlyView = true;
        foreach ($grantedPerms as $p) {
            if (!str_contains($p, 'view') && !str_contains($p, 'report') && !str_contains($p, 'sync') && !str_contains($p, 'export')) {
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
