<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    // Affiche la liste des races
    public function index()
    {
        $races = Race::all();
        return view('races.index', compact('races'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        return view('races.create');
    }

    // Stocke une nouvelle race dans la base de données
    public function store(Request $request)
    {
        $request->validate([
            'denomination' => 'required|string|max:255',
            'elevage' => 'required|string|max:255',
        ]);

        Race::create($request->all());
        return redirect()->route('races.index')->with('success', 'Race ajoutée avec succès');
    }

    // Affiche une race spécifique
    public function show(Race $race)
    {
        return view('races.show', compact('race'));
    }

    // Affiche le formulaire de modification d'une race
    public function edit(Race $race)
    {
        return view('races.edit', compact('race'));
    }

    // Met à jour une race existante dans la base de données
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'denomination' => 'required|string|max:255',
            'elevage' => 'required|string'
        ]);

        $race = Race::findOrFail($id);
        $race->update($validatedData);

        return response()->json(['success' => true, 'message' => 'Race mise à jour avec succès.']);
    }


    // Supprime une race de la base de données
    public function destroy($id)
    {
        $race = Race::findOrFail($id);
        $race->delete();

        return response()->json(['success' => true, 'message' => 'race supprimée avec succès.']);
    }
}
