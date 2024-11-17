<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Liste des modules de votre sidebar
        $modules = [
            'Tableau de bord',
            'Produit',
            'Ravitaillements',
            'Provenderie',
            'Perte Produit',
            'Perte Oeufs',
            'Ventes Oeufs',
            'Ventes Sujet',
            'Autres Ventes',
            'Retour Ventes',
            'Achat',
            'Dépenses',
            'Fournisseurs',
            'Clients',
            'Comptes',
            'Situation Financier',
            'Poulailler',
            'Cycle Production',
            'Tri des Oeufs',
            'Ressources Humaines',
            'Equipements',
            'Rapport Général',
            'Paramétrages'
        ];

        // For each module, create permissions: "read", "create", "edit", "delete"
        foreach ($modules as $module) {
            Permission::create(['name' => 'read ' . $module]);
            Permission::create(['name' => 'create ' . $module]);
            Permission::create(['name' => 'edit ' . $module]);
            Permission::create(['name' => 'delete ' . $module]);
        }

    }
}
