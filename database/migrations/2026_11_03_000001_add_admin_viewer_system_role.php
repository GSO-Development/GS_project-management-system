<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Role::findOrCreate('admin_viewer', 'web');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $role = Role::findByName('admin_viewer', 'web');
        if ($role->users()->doesntExist()) {
            $role->delete();
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
