<?php

namespace App\Http\Controllers;

use App\Models\Assurance;
use App\Models\Parametre;
use App\Models\Voiture;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AssuranceController extends Controller
{
    // Afficher toutes les assurances d'une voiture
    public function index()
    {
        $assurances = Assurance::all();
        //$voiture = Voiture::find($voiture_id);
        return view('assurances.index', compact('assurances'));
    }
    public function exportList()
    {
        $assurances = Assurance::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('assurances.pdf', compact('assurances','settings'));

        return $pdf->download('List_assurances.pdf');
    }

    // Formulaire pour ajouter une nouvelle assurance
    public function create()
    {
        // Sélectionner uniquement les voitures dont l'assurance est expirée
        $voitures = Voiture::whereHas('assurances', function ($query) {
            $query->where('date_fin', '<', now()); // Assurances expirées
        })->orDoesntHave('assurances') // Voitures sans vignette
        ->get();

        return view('assurances.add', compact('voitures'));
    }


    // Enregistrer une nouvelle assurance
    public function store(Request $request)
    {
        $request->validate([
            'assureur' => 'required',
            'voiture_id'=>'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'montant' => 'required',
        ]);

        Assurance::create([
            'voiture_id' => $request->voiture_id,
            'assureur' => $request->assureur,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'montant' => $request->montant,
        ]);

        return redirect()->route('assurances.index')->with('success', 'Assurance ajoutée avec succès.');
    }
    // Fonction pour afficher les données d'assurance à éditer
    public function edit($id)
    {
        $assurance = Assurance::findOrFail($id);
        return response()->json($assurance);
    }

    // Fonction pour mettre à jour l'assurance
    public function update(Request $request, $id)
    {
        $request->validate([
            'assureur' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'montant' => 'required|numeric',
        ]);

        $assurance = Assurance::findOrFail($id);
        $assurance->update($request->all());

        return response()->json(['success' => true, 'message' => 'Assurance mise à jour avec succès']);
    }

    // Fonction pour supprimer une assurance
    public function destroy($id)
    {
        $assurance = Assurance::findOrFail($id);
        $assurance->delete();

        return response()->json(['success' => true, 'message' => 'Assurance supprimée avec succès']);
    }

}
