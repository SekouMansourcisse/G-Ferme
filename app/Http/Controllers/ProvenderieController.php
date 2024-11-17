<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use App\Models\Produit;
use App\Models\Provenderie;
use Illuminate\Http\Request;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
class ProvenderieController extends Controller
{
    public function index()
    {
        $provenderies = Provenderie::all();
        return view('provenderie.list', compact('provenderies'));
    }
    public function exportPdf()
    {
        $provenderies = Provenderie::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('provenderie.pdf', compact('provenderies','settings'));

        return $pdf->download('liste_provenderie.pdf');
    }
    public function create()
    {
        $produits = Produit::all();
        return view('provenderie.add',compact('produits'));
    }

    public function store(Request $request)
    {
        // Validation des données (ajoutez ici les règles de validation si nécessaire)

        try {
            // Créer l'enregistrement de la provenderie
            $provenderie = new Provenderie();
            $provenderie->date_f = $request->input('Date');

            // Traitement des données pour Provend_produit
            $produitId = $request->input('produit_id');
            $qteObtenu = $request->input('qte');
            $provenderie->Provend_produit = "$produitId*$qteObtenu";
            $provenderie->commentaire=$request->input('Resume');
            $provenderie->qte=$request->input('qte');
            // Traitement des données pour Ingredient
            $qteConsomme = $request->input('qte_consomme');
            $ingredientStrings = [];
            foreach ($qteConsomme as $id => $qte) {
                $ingredientStrings[] = "$id*$qte";
            }
            $provenderie->Ingredient = implode(',', $ingredientStrings);

            $provenderie->save();

            // Mise à jour des quantités des produits
            // Mise à jour de la quantité obtenue
            $produit = Produit::find($produitId);
            $produit->qte_stock -= $qteObtenu; // Ajouter la quantité obtenue
            $produit->save();

            // Mise à jour des quantités consommées
            foreach ($qteConsomme as $produit_id => $qteConsomme1) {
                $produit = Produit::find($produit_id);
                $produit->qte_stock -= $qteConsomme1; // Réduire la quantité consommée
                $produit->save();
            }

            return redirect()->route('provenderies.index')->with('success', 'Fabrication effectuée avec succès.');
        } catch (Exception $e) {
            \Log::error('Erreur lors de l\'ajout de la provenderie: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }




    public function show(Provenderie $provenderie)
    {
        return view('provenderies.show', compact('provenderie'));
    }

    public function edit(Provenderie $provenderie)
    {
        return view('provenderies.edit', compact('provenderie'));
    }

    public function update(Request $request, Provenderie $provenderie)
    {
        $request->validate([
            'date_f' => 'required|string|max:100',
            'Provend_produit' => 'required|string|max:100',
            'qte' => 'required|string|max:100',
            'Ingredient' => 'required|string|max:100',
        ]);

        $provenderie->update($request->all());

        return redirect()->route('provenderies.index')->with('success', 'Provenderie mise à jour avec succès.');
    }

    public function destroy(Provenderie $provenderie)
    {
        $provenderie->delete();

        return redirect()->route('provenderies.index')->with('success', 'Provenderie supprimée avec succès.');
    }
}

