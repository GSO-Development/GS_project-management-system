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
        return isset(self::ROLES[$code]) && !empty(self::ROLES[$code]['is_protected']) || in_array($code, self::PROTECTED_ROLES, true);
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
            'is_protected' => false,
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

        // Sort roles by logical governance hierarchy order
        $priorityOrder = [
            'pmo_admin' => 1,
            'lead' => 2,
            'sponsor' => 3,
            'owner' => 4,
            'steering_committee' => 5,
            'member' => 6,
            'collaborator' => 7,
        ];

        uksort($roles, function ($a, $b) use ($priorityOrder) {
            $posA = $priorityOrder[$a] ?? 99;
            $posB = $priorityOrder[$b] ?? 99;
            if ($posA === $posB) {
                return strcmp($a, $b);
            }
            return $posA <=> $posB;
        });

        return $roles;
    }

    /**
     * Module definitions with granular permission codes.
     */
    public const MODULES = [
        'scope' => [
            'key' => 'scope',
            'name' => 'Scope / Project Details',
            'icon' => '📝',
            'permissions' => [
                'project_details.view' => 'View Project Details',
                'project_details.edit' => 'Edit Project Details',
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
                'task.change_status' => 'Update Task Status',
                'task.change_priority' => 'Change Task Priority',
                'task.create_subtask' => 'Create Subtask',
                'task.log_daily' => 'Daily Progress Log',
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
        return self::MODULES;
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

            // Project Manager (lead or designated manager on this project) has 100% FULL ACCESS
            if ($projectModel->project_manager_id === $user->id) {
                return true;
            }

            $roleCode = $projectModel->getUserRole($user);

            // If user has no explicit role in this project, check if assigned to a task
            if (!$roleCode) {
                if ($task && $task->assigned_user_id === $user->id) {
                    if (in_array($permission, ['task.view', 'task.view_assigned', 'task.edit_assigned', 'task.change_status', 'approval.submit', 'approval.view', 'risk.create', 'risk.view'], true)) {
                        return true;
                    }
                }
                return false;
            }

            // Role: Project Lead/Manager (Full Access on PM project)
            if ($roleCode === 'lead' || $roleCode === 'project_manager') {
                return true;
            }

            // Fetch permissions dynamically assigned to this role in database (Spatie RBAC)
            $rolePermissions = self::getPermissionsForRole($roleCode);

            // If requested permission is not in the granted permissions array, deny access
            if (!in_array($permission, $rolePermissions, true)) {
                return false;
            }

            // Task-specific assignment scoping:
            // If checking access on a specific $task model (e.g. view, edit assigned, status update)
            if ($task && in_array($permission, ['task.view', 'task.edit_assigned', 'task.change_status'], true)) {
                // Roles with 'task.view_all' can view and act on any task across the project
                if (in_array('task.view_all', $rolePermissions, true)) {
                    return true;
                }
                // Otherwise, restricted to tasks assigned to this user
                return $task->assigned_user_id === $user->id;
            }

            return true;
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
                'project_details.view', 'project_details.edit',
                'task.view', 'task.view_all', 'task.view_assigned', 'task.create', 'task.edit', 'task.edit_assigned', 'task.delete', 'task.assign', 'task.change_status', 'task.change_priority', 'task.create_subtask', 'task.log_daily',
                'team.view', 'team.add', 'team.remove',
                'calendar.view', 'calendar.create', 'calendar.edit', 'calendar.delete', 'calendar.sync_outlook', 'calendar.export',
            ],
            'member', 'team_member' => [
                // WBS & Tasks permissions only
                'task.view', 'task.view_assigned', 'task.edit_assigned', 'task.change_status', 'task.log_daily',
            ],
            'collaborator' => [
                // WBS & Tasks permissions only
                'task.view', 'task.view_assigned', 'task.edit_assigned', 'task.change_status', 'task.log_daily',
            ],
            'sponsor' => [
                // WBS & Tasks permissions only
                'task.view', 'task.view_all', 'task.view_assigned',
            ],
            'owner' => [
                // WBS & Tasks permissions only
                'task.view', 'task.view_assigned',
            ],
            'steering_committee' => [
                // WBS & Tasks permissions only
                'task.view', 'task.view_assigned',
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
        $allModules = self::getAllModules();
        $moduleDef = $allModules[$moduleKey] ?? (self::MODULES[$moduleKey] ?? null);

        if (!$moduleDef) {
            return ['label' => 'No Access', 'badgeClass' => 'bg-slate-100 text-slate-500 border-slate-200', 'count' => 0, 'total' => 0];
        }

        $modulePermKeys = array_keys($moduleDef['permissions']);
        $totalModulePerms = count($modulePermKeys);

        if (in_array($roleCode, ['super_admin', 'pmo_admin'], true)) {
            return [
                'label' => 'Full Access',
                'badgeClass' => 'bg-emerald-50 text-emerald-700 border-emerald-200 font-extrabold',
                'count' => $totalModulePerms,
                'total' => $totalModulePerms,
            ];
        }

        $rolePermissions = self::getPermissionsForRole($roleCode);
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
