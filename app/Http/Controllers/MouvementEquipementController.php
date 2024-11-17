<?php

namespace App\Http\Controllers;

use App\Models\MouvementEquipement;
use App\Models\Equipement;
use Illuminate\Http\Request;

class MouvementEquipementController extends Controller
{
    public function index()
    {
        $mouvement_equipements = MouvementEquipement::all();
        $equipements= Equipement::all();
        return view('Equipement.listM', compact('mouvement_equipements','equipements'));
    }

    public function create()
    {
        $equipements = Equipement::all();
        return view('Equipement.mouvement', compact('equipements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Equipement_id' => 'required|integer|exists:equipements,id',
            'Origine' => 'required|string|max:100',
            'Destination' => 'required|string|max:100',
            'Statut' => 'required|string|max:100',
            'Date_mouvement' => 'required|string|max:100',
            'commentaire' => 'string|max:100',
        ]);

        MouvementEquipement::create($validated);

        return redirect()->route('mouvements.index')->with('success', 'Mouvement effectué avec Succès.');
    }

    public function show(MouvementEquipement $mouvement)
    {
        return view('mouvements.show', compact('mouvement'));
    }
    public function edit($id)
    {
        $mouvement = MouvementEquipement::with('equipement')->findOrFail($id);

        return response()->json($mouvement);
    }

    // Met à jour les informations d'un mouvement
    public function update(Request $request, $id)
    {
        // Validation des champs
        $request->validate([
            'Equipement_id' => 'required|exists:equipements,id',
            'Origine' => 'required|string|max:255',
            'Destination' => 'required|string|max:255',
            'Statut' => 'required|string',
            'Date_mouvement' => 'required|date',
        ]);

        // Récupère le mouvement et le met à jour
        $mouvement = MouvementEquipement::findOrFail($id);
        $mouvement->update([
            'Equipement_id' => $request->input('Equipement_id'),
            'Origine' => $request->input('Origine'),
            'Destination' => $request->input('Destination'),
            'Statut' => $request->input('Statut'),
            'Date_mouvement' => $request->input('Date_mouvement'),
        ]);

        return response()->json(['success' => true, 'message' => 'Mouvement mis à jour avec succès.']);
    }

    // Supprime un mouvement
    public function destroy($id)
    {
        $mouvement = MouvementEquipement::findOrFail($id);
        $mouvement->delete();

        return response()->json(['success' => true, 'message' => 'Mouvement supprimé avec succès.']);
    }
}

