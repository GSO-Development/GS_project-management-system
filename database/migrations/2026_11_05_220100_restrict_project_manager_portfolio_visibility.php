<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Project Managers are scoped to the projects they lead or are assigned to.
     * Portfolio-wide visibility remains available to PMO Administrators and any
     * explicitly configured non-PM governance role.
     */
    public function up(): void
    {
        foreach (['lead', 'project_manager'] as $roleCode) {
            $role = Role::where('name', $roleCode)->first();

            if ($role?->hasPermissionTo('project.view_all')) {
                $role->revokePermissionTo('project.view_all');
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Do not automatically broaden Project Manager visibility on rollback.
    }
};
