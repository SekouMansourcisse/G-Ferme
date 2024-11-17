<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Database\Schema\IndexDefinition;
use Illuminate\Http\Request;
use App\Models\AlimentationBetail;
use App\Models\Vache;

class AlimentationBetailController extends Controller
{
    public function index()
    {
        $alimentations=AlimentationBetail::all();
        $produits=Produit::where('typeProduit','Autres produits')->get();
        return view('vaches.alimentation',compact('alimentations','produits'));
    }
    public function create()
    {
        $produits=Produit::where('typeProduit','Autres produits')->get();
        return view('vaches.addAliment',compact('produits'));

    }


    // Fonction pour enregistrer l'alimentation d'une vache
    public function store(Request $request)
    {
        // Validation des données entrantes
        $validated = $request->validate([
            'date_alimentation' => 'required|date',
            'produit_id' => 'required|integer|exists:produit,id',
            'quantite' => 'required|string|max:100',
            'periode' => 'required|string|max:100',
            'commentaire' => 'nullable|string|max:100',
        ]);

        // Enregistrement de l'alimentation
        AlimentationBetail::create($validated);
        $produit=Produit::findOrFail($request->input('type_nourriture'));
        $produit->qte_stock-=$request->input('quantite');
        $produit->save();
        return redirect()->route('betail.index')->with('success', 'Alimentation enregistrée avec succès.');
    }
    public function update(Request $request, $id)
    {
        // Validation des données entrantes
        $validated = $request->validate([
            'date_alimentation' => 'required|date',
            'produit_id' => 'required|integer|exists:produit,id',
            'quantite' => 'required|string|max:100',
            'periode' => 'required|string|max:100',
            'commentaire' => 'nullable|string|max:100',
        ]);

        $alimentation = AlimentationBetail::findOrFail($id);
        $alimentation->update([
            'date_alimentation' => $request->date_alimentation,
            'produit_id' => $request->produit_id,
            'periode' => $request->periode,
            'quantite' => $request->quantite,
            'commentaire' => $request->commentaire,
        ]);
        return response()->json(['success' => true, 'message' => 'alimentation mise à jour avec succès']);
    }

    public function destroy($id)
    {
        $aliment = AlimentationBetail::findOrFail($id);
        $aliment->delete();

        return response()->json(['success' => true, 'message' => 'alimentation supprimée avec succès.']);
    }
}
