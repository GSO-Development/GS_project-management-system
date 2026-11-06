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
        'admin_viewer',
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
        'admin_viewer' => [
            'name' => 'Admin Viewer',
            'code' => 'admin_viewer',
            'icon' => 'AV',
            'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200 font-extrabold',
            'description' => 'Organization-wide, read-only access to the PMO administration interface.',
            'is_system' => true,
            'is_protected' => true,
        ],
        'lead' => [
            'name' => 'Project Manager',
            'code' => 'lead',
            'icon' => '⭐',
            'badge' => 'bg-rose-50 text-[#c3122e] border-rose-200',
            'description' => 'Project-level leader with full access to project details & financials, WBS scheduling, task & subtask management, team roster, and risk management.',
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
            'admin_viewer' => 2,
            'lead' => 3,
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
        'project' => [
            'key' => 'project',
            'name' => 'Projects',
            'icon' => '📁',
            'permissions' => [
                'project.view' => 'View Project',
                'project.view_all' => 'View All Projects',
                'project.view_assigned' => 'View Assigned Projects',
                'project.create' => 'Create Project',
                'project.edit' => 'Update Project Status & Workspace Setup',
                'project.manage_schedule' => 'Manage Project Schedule',
            ],
        ],
        'scope' => [
            'key' => 'scope',
            'name' => 'Scope & Project Details',
            'icon' => '📝',
            'permissions' => [
                'project_details.view' => 'View Project Details',
                'project_details.edit' => 'Edit Project Details',
                'scope.approve' => 'Approve Scope Changes',
                'schedule.edit' => 'Edit Schedule / Timeline',
            ],
        ],
        'budget' => [
            'key' => 'budget',
            'name' => 'Budget & Finance',
            'icon' => '💰',
            'permissions' => [
                'budget.edit' => 'Edit Financials & Budget (Estimated Cost & Actual Spend)',
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
                'task.sheet_view' => 'View Task Sheet (Work Plan)',
                'task.sheet_edit' => 'Edit Task Sheet (Bulk Dates & Schedule Cascade)',
                'task.create' => 'Create New Task',
                'task.edit' => 'Edit Task',
                'task.edit_assigned' => 'Edit Assigned Task',
                'task.delete' => 'Delete Task',
                'task.assign' => 'Assign Team Members to Task',
                'task.change_status' => 'Update Task Status',
                'task.change_priority' => 'Update Task Priority',
                'task.update_progress' => 'Update Task Progress',
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
                'team.view_member' => 'View Team Member Details',
                'team.add' => 'Add Project Member',
                'team.remove' => 'Remove Project Member',
            ],
        ],
        'risk' => [
            'key' => 'risk',
            'name' => 'Risks & Blockers',
            'icon' => '⚠️',
            'permissions' => [
                'risk.view' => 'View Risks & Blockers',
                'risk.create' => 'Report / Log Risk',
                'risk.edit' => 'Edit Risk Details & Mitigation',
                'risk.delete' => 'Delete Risk',
                'risk.assign' => 'Assign Risk Owner',
                'risk.escalate' => 'Escalate Risk',
                'risk.resolve' => 'Resolve / Close Risk',
                'blocker.create' => 'Log Blocker Item',
                'blocker.edit' => 'Edit Blocker Details',
                'blocker.resolve' => 'Resolve Blocker Item',
            ],
        ],
        'calendar' => [
            'key' => 'calendar',
            'name' => 'Calendar & Meetings',
            'icon' => '📅',
            'permissions' => [
                'calendar.view' => 'View Calendar & Meetings',
                'calendar.view_all' => 'View All Project Calendars',
                'calendar.create' => 'Schedule Meetings (All Projects)',
                'calendar.create_assigned' => 'Schedule Meetings (Assigned Projects Only)',
                'calendar.edit' => 'Edit Meetings & Events',
                'calendar.delete' => 'Delete Meetings & Events',
                'calendar.sync_outlook' => 'Sync with Microsoft Outlook',
                'calendar.export' => 'Export Calendar (.ics)',
            ],
        ],
    ];

    /**
     * Platform-level (page / action) access that is enforced by routes and role checks rather than by
     * Spatie permissions. Shown on the Roles & Permissions page so PMO Admin, Admin Viewer and
     * Project Manager access can be compared at a glance.
     *
     * Access values: yes | no | view (read-only) | scoped (conditional – see note).
     */
    public const SYSTEM_ACCESS = [
        [
            'label' => 'Create a new project',
            'note' => 'Only PMO Administrators can open the Create Project page.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'no', 'lead' => 'no'],
        ],
        [
            'label' => 'Reassign project leadership',
            'note' => 'Reassign a declined or rejected project to another Project Manager.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'no', 'lead' => 'no'],
        ],
        [
            'label' => 'Accept / decline an assigned project',
            'note' => 'Only the Project Manager a project is assigned to can accept or decline it.',
            'access' => ['pmo_admin' => 'scoped', 'admin_viewer' => 'no', 'lead' => 'yes'],
            'scoped_note' => 'Only if assigned as the Project Manager',
        ],
        [
            'label' => 'Change data inside projects',
            'note' => 'Project Managers act within their own projects, limited to the permissions granted above.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'no', 'lead' => 'scoped'],
            'scoped_note' => 'Own projects, per the permission matrix',
        ],
        [
            'label' => 'Organisation-wide monitors (All Tasks, Project Monitor, Team Monitor)',
            'note' => 'Admin Viewer sees everything but cannot change anything.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'view', 'lead' => 'no'],
        ],
        [
            'label' => 'User Directory (participants)',
            'note' => 'Create, edit and deactivate users, including Azure AD provisioning.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'view', 'lead' => 'no'],
        ],
        [
            'label' => 'Subsidiaries',
            'note' => 'Manage subsidiary companies.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'view', 'lead' => 'no'],
        ],
        [
            'label' => 'Audit logs',
            'note' => 'Review the system activity trail.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'view', 'lead' => 'no'],
        ],
        [
            'label' => 'Project templates & Gantt builder',
            'note' => 'Create and manage reusable project templates.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'no', 'lead' => 'no'],
        ],
        [
            'label' => 'Roles & Permissions',
            'note' => 'This page – edit what each project role is allowed to do.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'no', 'lead' => 'no'],
        ],
        [
            'label' => 'System settings',
            'note' => 'Platform-wide configuration.',
            'access' => ['pmo_admin' => 'yes', 'admin_viewer' => 'no', 'lead' => 'no'],
        ],
    ];

    /**
     * Roles whose access is enforced by the platform itself (route / role checks), not by editable
     * permission assignments. Their permissions are displayed but cannot be edited.
     */
    public const SYSTEM_LOCKED_ROLES = ['super_admin', 'pmo_admin'];

    public static function isSystemLockedRole(?string $code): bool
    {
        return $code !== null && in_array($code, self::SYSTEM_LOCKED_ROLES, true);
    }

    /**
     * Project creation and portfolio-wide viewing are platform governance actions,
     * not permissions that can be granted to a Project Manager project role.
     */
    public static function canRoleReceivePermission(?string $roleCode, string $permission): bool
    {
        if ($permission === 'project.create' || $permission === 'calendar.create') {
            return in_array($roleCode, ['super_admin', 'pmo_admin'], true);
        }

        if ($permission === 'project.view_all') {
            return !in_array($roleCode, ['lead', 'project_manager'], true);
        }

        return true;
    }

    /**
     * Admin Viewer is a read-only observer: every "view" permission plus report export.
     * Single source of truth, used both for enforcement and for display.
     */
    public static function adminViewerAllows(string $permission): bool
    {
        // Admin Viewer default = every view permission only. No create/edit/delete/export.
        return str_contains($permission, 'view');
    }

    /**
     * Request-level cache of the merged permission catalog.
     */
    protected static ?array $modulesCache = null;

    /**
     * Ensure all defined module permissions exist in Spatie permissions table.
     */
    public static function ensureAllPermissionsExist(): void
    {
        $existing = Permission::where('guard_name', 'web')->pluck('name')->all();

        foreach (self::MODULES as $module) {
            foreach (array_keys($module['permissions']) as $permName) {
                if (!in_array($permName, $existing, true)) {
                    Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
                }
            }
        }
    }

    /**
     * Get all active module definitions and permissions.
     *
     * Returns only the curated MODULES catalog. Database-only permissions that are not
     * in the catalog (legacy migrations, old report/budget phantom rows) are intentionally
     * excluded so the UI shows only permissions the codebase actually enforces.
     */
    public static function getAllModules(): array
    {
        if (self::$modulesCache !== null) {
            return self::$modulesCache;
        }

        self::ensureAllPermissionsExist();

        return self::$modulesCache = self::MODULES;
    }

    /**
     * Every permission code shown in the catalog.
     *
     * @return array<int, string>
     */
    public static function allPermissionCodes(): array
    {
        $codes = [];
        foreach (self::getAllModules() as $module) {
            foreach (array_keys($module['permissions']) as $code) {
                $codes[] = (string) $code;
            }
        }

        return array_values(array_unique($codes));
    }

    /**
     * Permissions a platform-enforced role effectively has (for display only).
     * PMO Administrator = everything; Admin Viewer = read-only subset.
     *
     * @return array<int, string>
     */
    public static function getEffectivePermissionsForRole(string $roleCode): array
    {
        $all = self::allPermissionCodes();

        if ($roleCode === 'admin_viewer') {
            return array_values(array_filter($all, fn (string $code) => self::adminViewerAllows($code)));
        }

        return $all;
    }

    /**
     * Check if a user has a specific permission in a project context.
     */
    public static function checkPermission(?User $user, string $permission, Project|int|null $project = null, ?WbsItem $task = null): bool
    {
        if (!$user) {
            return false;
        }

        // Organization-wide observer: permissions are dynamically configured via RBAC
        if ($user->isAdminViewer()) {
            return in_array($permission, self::getPermissionsForRole('admin_viewer'), true);
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

            // If user has no explicit role in this project, check if assigned to a task
            if (!$roleCode) {
                if ($task && $task->assigned_user_id === $user->id) {
                    if (in_array($permission, ['task.view', 'task.view_assigned', 'task.edit_assigned', 'task.change_status', 'task.update_progress', 'approval.submit', 'approval.view', 'risk.create', 'risk.view'], true)) {
                        return true;
                    }
                }
                return false;
            }

            // Fetch permissions dynamically assigned to this role in database (Spatie RBAC)
            $rolePermissions = self::getPermissionsForRole($roleCode);

            // If requested permission is not in the granted permissions array, deny access
            if (!self::canRoleReceivePermission($roleCode, $permission)
                || !in_array($permission, $rolePermissions, true)) {
                return false;
            }

            // Task-specific assignment scoping:
            // If checking access on a specific $task model (e.g. view, edit assigned, status update, progress update)
            if ($task && in_array($permission, ['task.view', 'task.edit_assigned', 'task.change_status', 'task.update_progress'], true)) {
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
            if (self::canRoleReceivePermission($roleCode, $permission)
                && in_array($permission, self::getPermissionsForRole($roleCode))) {
                return true;
            }
        }

        if ($user->managedProjects()->exists()
            && self::canRoleReceivePermission('lead', $permission)
            && in_array($permission, self::getPermissionsForRole('lead'))) {
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

        // Platform-enforced roles: report what they can effectively do, not stale database rows.
        if (self::isSystemLockedRole($roleCode)) {
            return self::$rolePermissionsCache[$roleCode] = self::getEffectivePermissionsForRole($roleCode);
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
            return self::$rolePermissionsCache[$roleCode] = self::getDefaultPermissionsForRole($roleCode);
        }

        $perms = $role->permissions->pluck('name')->toArray();
        return self::$rolePermissionsCache[$roleCode] = $perms;
    }

    /**
     * Return project-member role codes that currently hold at least one of the
     * requested permissions. Used by project listings so an unchecked project-view
     * permission hides the project as well as blocking a direct URL.
     *
     * @param array<int, string> $permissions
     * @return array<int, string>
     */
    public static function getRoleCodesWithAnyPermission(array $permissions): array
    {
        if ($permissions === []) {
            return [];
        }

        return Role::query()
            ->whereHas('permissions', fn ($query) => $query->whereIn('name', $permissions))
            ->pluck('name')
            ->filter(fn (string $roleCode) => collect($permissions)
                ->contains(fn (string $permission) => self::canRoleReceivePermission($roleCode, $permission)))
            ->all();
    }

    /**
     * Get default permissions mapping for standard project governance & system roles.
     */
    public static function getDefaultPermissionsForRole(string $roleCode): array
    {
        $allCurrentPermissions = self::allPermissionCodes();

        return match($roleCode) {
            'super_admin', 'pmo_admin' => $allCurrentPermissions,
            // Project creation is a platform-level PMO action (User::canCreateProject), not a
            // project-role permission. A Project Manager is also intentionally scoped to projects
            // they lead or are assigned to, rather than receiving portfolio-wide project visibility.
            'lead', 'project_manager' => array_values(array_diff($allCurrentPermissions, [
                'project.create',
                'project.view_all',
                'calendar.create',
            ])),
            'admin_viewer' => array_values(array_filter($allCurrentPermissions, fn (string $code) => self::adminViewerAllows($code))),
            'member', 'team_member' => [
                'project.view_assigned',
                'task.view', 'task.view_assigned', 'task.sheet_view', 'task.edit_assigned', 'task.change_status', 'task.update_progress', 'task.log_daily',
                'risk.view', 'risk.create', 'blocker.create',
                'calendar.view', 'calendar.sync_outlook', 'calendar.export',
            ],
            'collaborator' => [
                'project.view_assigned',
                'task.view', 'task.view_assigned', 'task.edit_assigned', 'task.change_status', 'task.update_progress', 'task.log_daily',
                'risk.view', 'risk.create', 'blocker.create',
                'calendar.view',
            ],
            'sponsor' => [
                'project.view', 'project.view_assigned', 'project.view_all',
                'project_details.view',
                'task.view', 'task.view_all', 'task.sheet_view',
                'team.view', 'team.view_member',
                'risk.view',
                'calendar.view', 'calendar.view_all', 'calendar.export',
            ],
            'owner' => [
                'project.view', 'project.view_assigned',
                'project_details.view',
                'task.view', 'task.view_all', 'task.sheet_view',
                'team.view', 'team.view_member',
                'risk.view',
                'calendar.view', 'calendar.view_all', 'calendar.export',
            ],
            'steering_committee' => [
                'project.view', 'project.view_assigned',
                'project_details.view',
                'task.view', 'task.view_all', 'task.sheet_view',
                'team.view', 'team.view_member',
                'risk.view',
                'calendar.view', 'calendar.view_all', 'calendar.export',
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
        self::$modulesCache = null;
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

        // Project creation is granted solely by the PMO platform role and is intentionally
        // not an editable project-role permission. Keep the matrix total consistent with
        // the permissions shown in non-PMO role drawers.
        if ($moduleKey === 'project' || $moduleKey === 'calendar') {
            $modulePermKeys = array_values(array_filter(
                $modulePermKeys,
                fn (string $permission) => self::canRoleReceivePermission($roleCode, $permission),
            ));
        }
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
