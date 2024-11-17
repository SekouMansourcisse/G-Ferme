<?php

namespace App\Http\Controllers;

use App\Models\Vignette;
use App\Models\Voiture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class VignetteController extends Controller
{
    // Afficher toutes les vignettes d'une voiture
    public function index()
    {
        $vignettes = Vignette::all();
        //$voiture = Voiture::find($voiture_id);
        return view('vignettes.index', compact('vignettes'));
    }
    public function exportList()
    {
        $vignettes = Vignette::all();
        $pdf = Pdf::loadView('vignettes.pdf', compact('vignettes'));

        return $pdf->download('List_vignettes.pdf');
    }

    // Formulaire pour ajouter une nouvelle vignette
    public function create()
    {
        $voitures = Voiture::whereHas('vignettes', function ($query) {
            $query->where('date_expiration', '<', now()); // Vignettes expirées
        })
        ->orDoesntHave('vignettes') // Voitures sans vignette
        ->get();


        return view('vignettes.add', compact('voitures'));
    }


    // Enregistrer une nouvelle vignette
    public function store(Request $request)
    {
        $request->validate([
            'date_obtention' => 'required|date',
            'date_expiration' => 'required|date',
            'montant' => 'required',
            'voiture_id' => 'required',
        ]);

        Vignette::create([
            'voiture_id' =>$request->voiture_id,
            'date_obtention' => $request->date_obtention,
            'date_expiration' => $request->date_expiration,
            'montant' => $request->montant,
        ]);

        return redirect()->route('vignettes.index')->with('success', 'Vignette ajoutée avec succès.');
    }

    // Formulaire pour éditer une vignette
    public function edit($id)
    {
        $vignette = Vignette::findOrFail($id);
        return response()->json($vignette);
    }

    // Fonction pour mettre à jour la vignette
    public function update(Request $request, $id)
    {
        $request->validate([
            'date_obtention' => 'required|date',
            'date_expiration' => 'required|date',
            'montant' => 'required|numeric',
        ]);

        $vignette = Vignette::findOrFail($id);
        $vignette->update($request->all());

        return response()->json(['success' => true, 'message' => 'Vignette mise à jour avec succès']);
    }

    // Fonction pour supprimer une vignette
    public function destroy($id)
    {
        $vignette = Vignette::findOrFail($id);
        $vignette->delete();

        return response()->json(['success' => true, 'message' => 'Vignette supprimée avec succès']);
    }
}
