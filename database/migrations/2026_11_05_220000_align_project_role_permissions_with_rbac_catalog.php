<?php

use App\Services\RbacService;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Bring existing standard roles forward when the Roles & Permissions catalog gains
     * a permission. Existing selections are retained; this migration only grants the
     * documented default access that old installations were missing.
     */
    public function up(): void
    {
        RbacService::ensureAllPermissionsExist();

        foreach ([
            'super_admin', 'pmo_admin', 'admin_viewer',
            'lead', 'project_manager',
            'member', 'team_member', 'collaborator',
            'sponsor', 'owner', 'steering_committee',
        ] as $roleCode) {
            $role = Role::where('name', $roleCode)->first();
            if (!$role) {
                continue;
            }

            $currentPermissions = $role->permissions->pluck('name')->all();
            $defaultPermissions = $roleCode === 'admin_viewer'
                ? RbacService::getEffectivePermissionsForRole($roleCode)
                : RbacService::getDefaultPermissionsForRole($roleCode);

            $role->syncPermissions(array_values(array_unique(array_merge(
                $currentPermissions,
                $defaultPermissions,
            ))));
        }

        RbacService::clearCache();
    }

    public function down(): void
    {
        // Permission assignments are operational data and must not be revoked on rollback.
    }
};
