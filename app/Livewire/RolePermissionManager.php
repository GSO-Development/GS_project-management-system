<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Services\RbacService;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionManager extends Component
{
    use WithPagination;

    public string $searchRole = '';
    public string $searchPermission = '';
    public int $perPage = 10;

    public function updatingSearchRole(): void
    {
        $this->resetPage();
    }

    public ?string $selectedRole = null;
    public bool $showManageDrawer = false;
    public string $searchDrawerPermission = '';

    /**
     * Map of permission_code => bool for the currently edited role.
     */
    public array $rolePermissions = [];

    /**
     * Snapshot to detect unsaved changes.
     */
    public array $initialRolePermissions = [];

    // Create Role Modal
    public bool $showCreateRoleModal = false;
    public string $newRoleName = '';
    public string $newRoleCode = '';
    public string $newRoleDescription = '';
    public string $newRoleIcon = '🏷️';
    public ?string $clonePermissionsFrom = null;

    // Create Permission Modal
    public bool $showCreatePermissionModal = false;
    public string $newPermissionName = '';
    public string $newPermissionCode = '';
    public string $newPermissionModule = 'project';

    // Delete Role Confirmation Modal
    public bool $showDeleteRoleModal = false;
    public ?string $roleToDelete = null;
    public string $roleNameToDelete = '';
    public int $roleUsersCountToDelete = 0;

    public ?string $successToast = null;

    public function mount(): void
    {
        abort_if(!auth()->check() || (!auth()->user()->isSuperAdmin() && !auth()->user()->isPmoAdmin()), 403, 'Unauthorized access to PMO Admin Role & Permission Management.');
    }

    public function openManageModal(string $roleCode): void
    {
        $allRoles = RbacService::getAllRoles();
        if (!isset($allRoles[$roleCode])) {
            return;
        }

        $this->selectedRole = $roleCode;
        $activePerms = RbacService::getPermissionsForRole($roleCode);

        $isPmoAdminRole = in_array($roleCode, ['super_admin', 'pmo_admin'], true);
        $isLeadRole = in_array($roleCode, ['lead', 'project_manager'], true);

        // Strip project.create for non-PMO admin roles, and project.delete for non-lead/non-PMO roles
        if (!$isPmoAdminRole) {
            $activePerms = array_diff($activePerms, ['project.create']);
            if (!$isLeadRole) {
                $activePerms = array_diff($activePerms, ['project.delete']);
            }
        }

        $this->rolePermissions = [];
        $allModules = RbacService::getAllModules();
        foreach ($allModules as $module) {
            foreach (array_keys($module['permissions']) as $p) {
                $this->rolePermissions[$p] = in_array($p, $activePerms, true);
            }
        }

        $this->initialRolePermissions = $this->rolePermissions;
        $this->searchDrawerPermission = '';
        $this->showManageDrawer = true;
        $this->successToast = null;
    }

    public function closeManageModal(): void
    {
        $this->showManageDrawer = false;
        $this->selectedRole = null;
        $this->rolePermissions = [];
        $this->initialRolePermissions = [];
    }

    public function togglePermission(string $permCode): void
    {
        $isPmoAdminRole = in_array($this->selectedRole, ['super_admin', 'pmo_admin'], true);

        if ($permCode === 'project.create' && !$isPmoAdminRole) {
            return;
        }

        $this->rolePermissions[$permCode] = !($this->rolePermissions[$permCode] ?? false);
    }

    public function selectAllForModule(string $moduleKey): void
    {
        $allModules = RbacService::getAllModules();
        $module = $allModules[$moduleKey] ?? null;
        if (!$module) return;

        $isPmoAdminRole = in_array($this->selectedRole, ['super_admin', 'pmo_admin'], true);

        foreach (array_keys($module['permissions']) as $permCode) {
            if ($permCode === 'project.create' && !$isPmoAdminRole) {
                continue;
            }
            $this->rolePermissions[$permCode] = true;
        }
    }

    public function clearAllForModule(string $moduleKey): void
    {
        $allModules = RbacService::getAllModules();
        $module = $allModules[$moduleKey] ?? null;
        if (!$module) return;

        foreach (array_keys($module['permissions']) as $permCode) {
            $this->rolePermissions[$permCode] = false;
        }
    }

    public function selectAllGlobal(): void
    {
        $allModules = RbacService::getAllModules();
        $isPmoAdminRole = in_array($this->selectedRole, ['super_admin', 'pmo_admin'], true);
        $isLeadRole = in_array($this->selectedRole, ['lead', 'project_manager'], true);

        foreach ($allModules as $module) {
            foreach (array_keys($module['permissions']) as $permCode) {
                if ($permCode === 'project.create' && !$isPmoAdminRole) {
                    continue;
                }
                if ($permCode === 'project.delete' && !$isPmoAdminRole && !$isLeadRole) {
                    continue;
                }
                $this->rolePermissions[$permCode] = true;
            }
        }
    }

    public function clearAllGlobal(): void
    {
        // Set all to false rather than empty array so Livewire detects each key change
        $this->rolePermissions = array_map(fn() => false, $this->rolePermissions);
    }

    public function getHasUnsavedChangesProperty(): bool
    {
        $current = array_keys(array_filter($this->rolePermissions));
        $initial = array_keys(array_filter($this->initialRolePermissions));
        sort($current);
        sort($initial);

        return $current !== $initial;
    }

    public function savePermissions(): void
    {
        abort_if(!auth()->user()->isSuperAdmin() && !auth()->user()->isPmoAdmin(), 403, 'Unauthorized.');

        if (!$this->selectedRole) {
            return;
        }

        $allRoles = RbacService::getAllRoles();
        $roleCode = $this->selectedRole;
        $roleInfo = $allRoles[$roleCode] ?? ['name' => $roleCode];

        $role = Role::where('name', $roleCode)->first();
        if (!$role) {
            $role = Role::create(['name' => $roleCode, 'guard_name' => 'web']);
        }

        // Active permissions to save
        $newPerms = array_keys(array_filter($this->rolePermissions));

        $isPmoAdminRole = in_array($roleCode, ['super_admin', 'pmo_admin'], true);
        $isLeadRole = in_array($roleCode, ['lead', 'project_manager'], true);

        if (!$isPmoAdminRole) {
            $newPerms = array_values(array_diff($newPerms, ['project.create']));
            if (!$isLeadRole) {
                $newPerms = array_values(array_diff($newPerms, ['project.delete']));
            }
        }
        $oldPerms = $role->permissions->pluck('name')->toArray();

        // Sync with Spatie
        $role->syncPermissions($newPerms);

        // Also sync alias role if exists (e.g. lead <-> project_manager, member <-> team_member)
        $alias = match($roleCode) {
            'lead' => 'project_manager',
            'member' => 'team_member',
            default => null,
        };
        if ($alias) {
            $aliasRole = Role::where('name', $alias)->first();
            if ($aliasRole) {
                $aliasRole->syncPermissions($newPerms);
            }
        }

        // Clear RBAC Cache
        RbacService::clearCache();

        // Audit Logging
        $added = array_diff($newPerms, $oldPerms);
        $removed = array_diff($oldPerms, $newPerms);

        $description = "PMO Admin updated permissions for {$roleInfo['name']}.";
        if (!empty($added)) {
            $description .= " Granted: [" . implode(', ', $added) . "].";
        }
        if (!empty($removed)) {
            $description .= " Revoked: [" . implode(', ', $removed) . "].";
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_role_permissions',
            'module' => 'roles_permissions',
            'record_type' => Role::class,
            'record_id' => $role->id,
            'previous_values' => ['role' => $roleCode, 'permissions' => $oldPerms],
            'new_values' => ['role' => $roleCode, 'permissions' => $newPerms, 'summary' => $description],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->initialRolePermissions = $this->rolePermissions;
        $this->showManageDrawer = false;
        $this->dispatch('toast', message: "Permissions for {$roleInfo['name']} saved successfully!", type: 'success');
    }

    public function resetRoleToDefault(?string $roleCode = null): void
    {
        abort_if(!auth()->user()->isSuperAdmin() && !auth()->user()->isPmoAdmin(), 403, 'Unauthorized.');

        $targetRole = $roleCode ?: $this->selectedRole;
        if (!$targetRole) {
            return;
        }

        $defaultPerms = RbacService::getDefaultPermissionsForRole($targetRole);

        $role = Role::where('name', $targetRole)->first();
        if ($role) {
            $role->syncPermissions($defaultPerms);
        }

        // Also sync alias role if exists
        $alias = match($targetRole) {
            'lead' => 'project_manager',
            'member' => 'team_member',
            'project_manager' => 'lead',
            'team_member' => 'member',
            default => null,
        };
        if ($alias) {
            $aliasRole = Role::where('name', $alias)->first();
            if ($aliasRole) {
                $aliasRole->syncPermissions($defaultPerms);
            }
        }

        RbacService::clearCache();

        if ($this->selectedRole === $targetRole) {
            $this->rolePermissions = array_fill_keys($defaultPerms, true);
            $this->initialRolePermissions = $this->rolePermissions;
        }

        $allRoles = RbacService::getAllRoles();
        $roleName = $allRoles[$targetRole]['name'] ?? $targetRole;
        $this->dispatch('toast', message: "Permissions for {$roleName} reset to default configuration.", type: 'info');
    }

    // --- CREATE NEW ROLE ---

    public function openCreateRoleModal(): void
    {
        $this->newRoleName = '';
        $this->newRoleCode = '';
        $this->newRoleDescription = '';
        $this->newRoleIcon = '🏷️';
        $this->clonePermissionsFrom = null;
        $this->showCreateRoleModal = true;
    }

    public function updatedNewRoleName(string $value): void
    {
        if (empty($this->newRoleCode)) {
            $this->newRoleCode = Str::slug($value, '_');
        }
    }

    public function createRole(): void
    {
        $this->validate([
            'newRoleName' => 'required|string|max:100',
            'newRoleCode' => 'required|string|max:50|alpha_dash|unique:roles,name',
            'newRoleDescription' => 'nullable|string|max:255',
        ]);

        $role = Role::create([
            'name' => strtolower($this->newRoleCode),
            'guard_name' => 'web',
        ]);

        if ($this->clonePermissionsFrom) {
            $clonePerms = RbacService::getPermissionsForRole($this->clonePermissionsFrom);
            $role->syncPermissions($clonePerms);
        }

        RbacService::clearCache();
        $this->showCreateRoleModal = false;
        $this->dispatch('toast', message: "New role '{$this->newRoleName}' created successfully!", type: 'success');
        $this->openManageModal($role->name);
    }

    public function promptDeleteRole(string $roleCode): void
    {
        if (RbacService::isProtectedRole($roleCode)) {
            $this->dispatch('toast', message: 'Core governance roles (Project Manager, PMO Admin, Super Admin, Project Owner, Project Sponsor, Core Project Team) cannot be deleted as they are essential to project governance.', type: 'warning');
            return;
        }

        $role = Role::where('name', $roleCode)->first();
        if (!$role) {
            $this->dispatch('toast', message: 'Role not found in database.', type: 'error');
            return;
        }

        $allRoles = RbacService::getAllRoles();
        $this->roleToDelete = $roleCode;
        $this->roleNameToDelete = $allRoles[$roleCode]['name'] ?? ucwords(str_replace(['_', '-'], ' ', $role->name));
        $this->roleUsersCountToDelete = $role->users()->count();
        $this->showDeleteRoleModal = true;
    }

    public function cancelDeleteRole(): void
    {
        $this->showDeleteRoleModal = false;
        $this->roleToDelete = null;
        $this->roleNameToDelete = '';
        $this->roleUsersCountToDelete = 0;
    }

    public function confirmDeleteRole(): void
    {
        if (!$this->roleToDelete) {
            return;
        }

        $roleCode = $this->roleToDelete;
        $this->showDeleteRoleModal = false;
        $this->roleToDelete = null;
        $this->roleNameToDelete = '';
        $this->roleUsersCountToDelete = 0;

        $this->deleteCustomRole($roleCode);
    }

    public function deleteCustomRole(string $roleCode): void
    {
        abort_if(!auth()->check() || (!auth()->user()->isSuperAdmin() && !auth()->user()->isPmoAdmin()), 403, 'Unauthorized.');

        // Prevent deletion of core protected governance roles:
        // Project Manager (lead/project_manager), PMO Admin/Super Admin (pmo_admin, super_admin),
        // Project Owner (owner), Project Sponsor (sponsor), Core Project Team (member/team_member)
        if (RbacService::isProtectedRole($roleCode)) {
            $this->dispatch('toast', message: 'Core governance roles (Project Manager, PMO Admin, Super Admin, Project Owner, Project Sponsor, Core Project Team) cannot be deleted as they are essential to project governance.', type: 'warning');
            return;
        }

        $role = Role::where('name', $roleCode)->first();
        if (!$role) {
            $this->dispatch('toast', message: 'Role not found in database.', type: 'error');
            return;
        }

        $allRoles = RbacService::getAllRoles();
        $displayName = $allRoles[$roleCode]['name'] ?? ucwords(str_replace(['_', '-'], ' ', $role->name));

        // Automatically detach users from this role
        $role->users()->detach();

        // Automatically clean up any project member assignments with this role
        \App\Models\ProjectMember::where('role', $roleCode)->delete();

        // Detach permissions and delete
        $role->permissions()->detach();
        $role->delete();
        RbacService::clearCache();

        // Audit Logging
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_role',
            'module' => 'roles_permissions',
            'record_type' => Role::class,
            'record_id' => null,
            'previous_values' => ['role' => $roleCode, 'name' => $displayName],
            'new_values' => ['summary' => "PMO Admin permanently deleted role '{$displayName}' ({$roleCode})."],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        if ($this->selectedRole === $roleCode) {
            $this->closeManageModal();
        }

        $this->resetPage();
        $this->successToast = "Role '{$displayName}' has been permanently deleted.";
        $this->dispatch('toast', message: "Role '{$displayName}' has been permanently deleted.", type: 'success');
    }

    public function deleteRole(string $roleCode): void
    {
        $this->promptDeleteRole($roleCode);
    }

    public function cannotDeleteSystemRole(string $roleName): void
    {
        $this->dispatch('toast', message: "Built-in role '{$roleName}' is a core protected GS NexusPM governance role and cannot be deleted. You can customize all of its granular permissions anytime.", type: 'info');
    }

    public function deletePermission(string $permCode): void
    {
        // Prevent deletion of built-in system permissions
        $standardPerms = [];
        foreach (RbacService::MODULES as $mod) {
            $standardPerms = array_merge($standardPerms, array_keys($mod['permissions']));
        }

        if (in_array($permCode, $standardPerms)) {
            $this->dispatch('toast', message: "Standard system permission '{$permCode}' is protected and cannot be deleted.", type: 'warning');
            return;
        }

        $perm = Permission::where('name', $permCode)->first();
        if ($perm) {
            $perm->delete();
            RbacService::clearCache();
            $this->successToast = "Custom permission '{$permCode}' removed successfully.";
        }
    }

    // --- CREATE NEW PERMISSION ---

    public function openCreatePermissionModal(): void
    {
        $this->newPermissionName = '';
        $this->newPermissionCode = '';
        $this->newPermissionModule = 'project';
        $this->showCreatePermissionModal = true;
    }

    public function updatedNewPermissionName(string $value): void
    {
        if (empty($this->newPermissionCode)) {
            $slug = Str::slug($value, '_');
            $this->newPermissionCode = "{$this->newPermissionModule}.{$slug}";
        }
    }

    public function updatedNewPermissionModule(string $value): void
    {
        if (!empty($this->newPermissionName)) {
            $slug = Str::slug($this->newPermissionName, '_');
            $this->newPermissionCode = "{$value}.{$slug}";
        }
    }

    public function createPermission(): void
    {
        $this->validate([
            'newPermissionName' => 'required|string|max:100',
            'newPermissionCode' => 'required|string|max:100|unique:permissions,name',
        ]);

        Permission::create([
            'name' => strtolower($this->newPermissionCode),
            'guard_name' => 'web',
        ]);

        // Auto-assign to Super Admin & PMO Admin
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(strtolower($this->newPermissionCode));
        }
        $pmoAdmin = Role::where('name', 'pmo_admin')->first();
        if ($pmoAdmin) {
            $pmoAdmin->givePermissionTo(strtolower($this->newPermissionCode));
        }

        RbacService::clearCache();
        $this->showCreatePermissionModal = false;
        $this->dispatch('toast', message: "Granular permission '{$this->newPermissionCode}' registered successfully!", type: 'success');
    }

    public function render()
    {
        $allRoles = RbacService::getAllRoles();
        $roles = $allRoles;

        if (!empty($this->searchRole)) {
            $query = strtolower($this->searchRole);
            $roles = array_filter($roles, function($r) use ($query) {
                return str_contains(strtolower($r['name']), $query) 
                    || str_contains(strtolower($r['code']), $query) 
                    || str_contains(strtolower($r['description'] ?? ''), $query);
            });
        }

        $allModules = RbacService::getAllModules();
        $modules = $allModules;

        if (!empty($this->searchPermission)) {
            $pQuery = strtolower($this->searchPermission);
            $filteredModules = [];
            foreach ($modules as $mKey => $mDef) {
                $matchingPerms = array_filter($mDef['permissions'], function($label, $code) use ($pQuery) {
                    return str_contains(strtolower($label), $pQuery) || str_contains(strtolower($code), $pQuery);
                }, ARRAY_FILTER_USE_BOTH);

                if (!empty($matchingPerms) || str_contains(strtolower($mDef['name']), $pQuery)) {
                    $mDef['permissions'] = !empty($matchingPerms) ? $matchingPerms : $mDef['permissions'];
                    $filteredModules[$mKey] = $mDef;
                }
            }
            $modules = $filteredModules;
        }

        $totalPermsCount = Permission::count();
        $totalRolesCount = count($allRoles);

        $currentPage = $this->getPage();
        $paginatedRoles = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($roles)->forPage($currentPage, $this->perPage),
            count($roles),
            $this->perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.role-permission-manager', [
            'roles' => $paginatedRoles,
            'allRoles' => $allRoles,
            'modules' => $modules,
            'allModules' => $allModules,
            'totalPermsCount' => $totalPermsCount,
            'totalRolesCount' => $totalRolesCount,
        ])->layout('layouts.app', ['title' => 'Roles & Permissions Hub — GS NexusPM']);
    }
}
