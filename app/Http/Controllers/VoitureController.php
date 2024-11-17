<?php

namespace App\Http\Controllers;

use App\Models\Assurance;
use App\Models\Vignette;
use App\Models\Voiture;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class VoitureController extends Controller
{
    // Afficher toutes les voitures
    public function index()
    {
        $voitures = Voiture::all();
        return view('voitures.index', compact('voitures'));
    }
    public function exportList()
    {
        $voitures = Voiture::all();
        $pdf = Pdf::loadView('voitures.pdf', compact('voitures'));

        return $pdf->download('List_voitures.pdf');
    }

    public function dashbord()
    {


        // Pour les assurances qui expirent bientôt (dans 30 jours)
        $assurancesExpireBientot = Assurance::where('date_fin', '>=', Carbon::now())
            ->where('date_fin', '<=', Carbon::now()->addMonth())
            ->get();

        // Pour les assurances qui sont déjà expirées
        $assurancesExpirees = Assurance::where('date_fin', '<', Carbon::now())->get();

        // Pour les vignettes qui expirent bientôt (dans 30 jours)
        $vignettesExpireBientot = Vignette::where('date_expiration', '>=', Carbon::now())
            ->where('date_expiration', '<=', Carbon::now()->addMonth())
            ->get();

        // Pour les vignettes qui sont déjà expirées
        $vignettesExpirees = Vignette::where('date_expiration', '<', Carbon::now())->get();

        return view('voitures.dashbord', compact('assurancesExpireBientot', 'assurancesExpirees', 'vignettesExpireBientot', 'vignettesExpirees'));
    }

    // Formulaire pour ajouter une nouvelle voiture
    public function create()
    {
        return view('voitures.add');
    }

    // Enregistrer une nouvelle voiture
    public function store(Request $request)
    {
        $request->validate([
            'marque' => 'required',
            'modele' => 'required',
            'plaque_immatriculation' => 'required|unique:voitures',
            'annee_fabrication' => 'required',
            'kilometrage' => 'required',
            'etat' => 'required',
            'commentaire',
        ]);

        Voiture::create($request->all());
        return redirect()->route('voitures.index')->with('success', 'Voiture ajoutée avec succès.');
    }

    // Afficher une voiture
    public function show(Voiture $voiture)
    {
        return view('voitures.show', compact('voiture'));
    }

    // Formulaire pour éditer une voiture
    public function edit($id)
    {
        $voiture = Voiture::findOrFail($id);
        return response()->json($voiture);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'marque' => 'required',
            'modele' => 'required',
            'plaque_immatriculation' => 'required|unique:voitures,plaque_immatriculation,' . $id,
            'annee_fabrication' => 'required',
            'kilometrage' => 'required',
            'etat' => 'required',
            'commentaire' => 'nullable',
        ]);

        $voiture = Voiture::findOrFail($id);
        $voiture->update($request->all());

        return response()->json(['success' => true, 'message' => 'Voiture mise à jour avec succès.']);
    }

    // Supprimer une voiture
    public function destroy($id)
    {
        try {
            $voiture = Voiture::findOrFail($id);
            $voiture->delete();

            return response()->json(['success' => true, 'message' => 'Voiture et ses données associées ont été supprimées avec succès.']);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression de la voiture: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression.'], 500);
        }
    }



}
