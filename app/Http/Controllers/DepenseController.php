<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Depense;
use App\Models\Fournisseur;
use App\Models\Parametre;
use App\Models\TypeDepense;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Log;
class DepenseController extends Controller
{
    public function index()
    {
        $depenses = Depense::all();
        $comptes = Compte::all();
        $type_depenses = TypeDepense::all();
        $fournisseurs=Fournisseur::all();
        return view('depenses.list', compact('depenses','type_depenses','comptes','fournisseurs'));
    }
    public function exportPdf()
    {
        $depenses = Depense::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('depenses.pdf', compact('depenses','settings'));

        return $pdf->download('liste_depenses.pdf');
    }
    public function create()
    {
        $type_depenses = TypeDepense::all();
        $comptes = Compte::all();
        $fournisseurs=Fournisseur::all();
        return view('depenses.add', compact('type_depenses','comptes','fournisseurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Date_depense' => 'required|string|max:100',
            'Beneficiaire' => 'required|string|max:100',
            'Categorie_depense' => 'required|string|max:100',
            'TypeDepense_id' => 'required|integer|exists:typedepense,id',
            'Objet' => 'required|string|max:100',
            'Montant_d' => 'required|string|max:100',
            'Montant_paye' => 'required|string|max:100',
            'payer_par' => 'required|string|max:100',
            'Fournisseur_id' => 'required|string|max:100',
            'Description' => 'nullable|string|max:100',
            'dette' => 'required|string|max:100',
        ]);

        Depense::create($validated);

        return redirect()->route('depenses.index')->with('success', 'Depense ajoutée avec Succès.');
    }

    public function show(Depense $depense)
    {
        return view('depenses.show', compact('depense'));
    }

    public function edit(Depense $depense)
    {
        $typesDepense = TypeDepense::all();
        return view('depenses.edit', compact('depense', 'typesDepense'));
    }

    public function update(Request $request,$id)
    {
        $validated = $request->validate([
            'Date_depense' => 'required|string|max:100',
            'Beneficiaire' => 'required|string|max:100',
            'Categorie_depense' => 'required|string|max:100',
            'TypeDepense_id' => 'required|integer|exists:typedepense,id',
            'Objet' => 'required|string|max:100',
            'Montant_d' => 'required|numeric',
            'Montant_paye' => 'required|numeric',
            'payer_par' => 'required|string|max:100',
            'Fournisseur_id' => 'required|string|max:100',
        ]);

        $depense=Depense::find($id);
        try {
            $depense->update($validated);
            return response()->json(['success' => true, 'message' => 'Depense mise à jour avec succès']);
        } catch (\Exception $e) {
            \Log::error("Erreur de mise à jour de dépense: ".$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur de mise à jour.'], 500);
        }

    }

    public function destroy($id)
    {
        $depense = Depense::findOrFail($id);
        $depense->delete();

        return response()->json(['success' => true, 'message' => 'Depense supprimée avec succès.']);
    }
}
