<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionsController extends Controller
{

    public function index()
    {
        $roles=Role::all();
        return view('users.listPermission', compact('roles'));
    }
    // Affiche le formulaire de création de rôle
    public function create()
    {
        // Récupérer toutes les permissions
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    // Stocke un nouveau rôle avec des permissions
    public function store(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        // Créer le rôle
        $role = Role::create([
            'name' => $validatedData['name'],
            'guard_name' => 'web', // Mettez à jour si nécessaire
        ]);

        // Assigner les permissions sélectionnées au rôle
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permissionId) {
                // Récupérer la permission par son ID et l'attribuer au rôle
                $permission = Permission::findById($permissionId);
                $role->givePermissionTo($permission);
            }
        }

        return redirect()->route('roles.index')->with('success', 'Rôle créé avec succès!');
    }

    public function edit($roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('users.editRoles', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
        ]);

        // Update the role name
        $role->update(['name' => $request->name]);

        // Retrieve only valid permission IDs from the request
        $validPermissionIds = Permission::whereIn('id', $request->permissions)->pluck('id')->toArray();

        // Sync the permissions with the validated IDs
        $role->permissions()->sync($validPermissionIds);

        // Redirect back with a success message
        return redirect()->route('roles.index')->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy(Role $role)
    {
        // Détacher toutes les permissions associées
        $role->permissions()->detach();

        // Ensuite, supprimer le rôle
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rôle et permissions associées supprimés avec succès.');
    }


}
