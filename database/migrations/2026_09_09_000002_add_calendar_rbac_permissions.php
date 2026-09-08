<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $calendarPerms = [
            'calendar.view',
            'calendar.view_all',
            'calendar.create',
            'calendar.edit',
            'calendar.delete',
            'calendar.sync_outlook',
            'calendar.export',
        ];

        foreach ($calendarPerms as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // 1. Super Admin & PMO Admin: Full Calendar & Microsoft Outlook Permissions
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($calendarPerms);
        }

        $pmoAdmin = Role::where('name', 'pmo_admin')->first();
        if ($pmoAdmin) {
            $pmoAdmin->givePermissionTo($calendarPerms);
        }

        // 2. Project Manager / Lead: Can create, edit, delete, sync & export
        $pmRoles = Role::whereIn('name', ['lead', 'project_manager'])->get();
        foreach ($pmRoles as $pmRole) {
            $pmRole->givePermissionTo([
                'calendar.view',
                'calendar.create',
                'calendar.edit',
                'calendar.delete',
                'calendar.sync_outlook',
                'calendar.export',
            ]);
        }

        // 3. Other Roles: View only + personal Outlook sync & export
        $viewRoles = Role::whereIn('name', ['sponsor', 'owner', 'steering_committee', 'member', 'team_member', 'collaborator'])->get();
        foreach ($viewRoles as $vRole) {
            $vRole->givePermissionTo([
                'calendar.view',
                'calendar.sync_outlook',
                'calendar.export',
            ]);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $calendarPerms = [
            'calendar.view',
            'calendar.view_all',
            'calendar.create',
            'calendar.edit',
            'calendar.delete',
            'calendar.sync_outlook',
            'calendar.export',
        ];

        Permission::whereIn('name', $calendarPerms)->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
