<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use App\Models\Ferme;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class EquipementController extends Controller
{
    public function index()
    {
        $equipements = Equipement::all();
        $fermes= Ferme::all();
        return view('Equipement.list', compact('equipements','fermes'));
    }

    public function create()
    {
        $fermes=Ferme::all();
        return view('Equipement.add',compact('fermes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Denomination' => 'required|string|max:100',
            'ferme_id' => 'required|string|max:100',
            'Emplacement' => 'required|string|max:100',
            'PrixAchat' => 'required|string|max:100',
            'responsable' => 'required|string|max:100',
            'commentaire'=>'string|max:100',
        ]);

        Equipement::create($validated);

        return redirect()->route('equipements.index')->with('success', 'Equipement ajoutée avec Succès.');
    }

    public function show(Equipement $equipement)
    {
        return view('equipements.show', compact('equipement'));
    }

    public function edit($id)
    {
        $equipement = Equipement::findOrFail($id);
        return response()->json($equipement);
    }

    public function update(Request $request, $id)
    {


        $equipement = Equipement::findOrFail($id);
        $equipement->update($request->all());

        return response()->json(['success' => true, 'message' => 'Équipement mis à jour avec succès.']);
    }

    public function destroy($id)
    {
        $equipement = Equipement::findOrFail($id);
        $equipement->delete();

        return response()->json(['success' => true, 'message' => 'Équipement supprimé avec succès.']);
    }
}

