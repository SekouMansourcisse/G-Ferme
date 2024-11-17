<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        // Définition des modules pour lesquels créer les permissions
        $modules = [
            'commandes',
            'retour_ventes_commande',
            'voitures',
            'vignettes',
            'assurances',
            'maintenances',
            'ventes_bovins',
            'vaches',
            'vaches_laitiers',
            'bovins',
            'alimentation_betail',
            'production_lait',
            'abbatoire',
            'abbatu',
        ];

        // Actions à appliquer à chaque module
        $actions = ['read', 'create', 'delete', 'edit'];

        // Création des permissions pour chaque combinaison de module et d'action
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action} {$module}"]);
            }
        }
    }
}

