<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdditionalPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Modules pour lesquels nous voulons créer les permissions
        $modules = [
            'remboursement',
            'operation sur bande',
            'operation sur les vaches',
            'ventes vaches',
        ];

        // Actions pour chaque module
        $actions = ['read', 'create', 'delete', 'edit'];

        // Création des permissions pour chaque combinaison de module et d'action
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action} {$module}"]);
            }
        }
    }
}
