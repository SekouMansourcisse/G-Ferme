<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToSuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Récupère toutes les permissions
        $permissions = Permission::all();

        // Récupère le rôle Super-admin par son ID
        $superAdminRole = Role::find(5);

        // Assigne toutes les permissions au rôle Super-admin
        if ($superAdminRole) {
            $superAdminRole->syncPermissions($permissions);
        }
    }
}

