<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Compte;
use App\Models\Vache;
use App\Models\VenteBovin;
use Illuminate\Http\Request;

class VenteBovinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventes = VenteBovin::all(); // Obtenir toutes les ventes avec les informations des bovins
        return view('VenteBovins.index', compact('ventes'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients= Client::all();
        $comptes=Compte::all();
        $vaches=Vache::where('etat',1)->get();
        return view('VenteBovins.add',compact('vaches','clients','comptes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'date' => 'required|date',
            'type_client' => 'required|string',
            'client_id' => $request->type_client === 'Client Fidèle' ? 'required|integer' : 'nullable',
            'prix_vente' => 'required|array',
            'prix_vente.*' => 'required|numeric',
            'operation_id' => 'required|array',
            'operation_id.*' => 'required|integer',
            'total_ravitaillement' => 'required|numeric',
            'total_remise' => 'required|numeric',
            'net_payer' => 'required|numeric',
            'montant_paye' => 'required|numeric',
            'payer_par' => 'required|integer',
        ]);

        // Préparer la chaîne formatée pour le champ vache_id
        $vache_ids = [];
        foreach ($request->operation_id as $index => $vacheId) {
            $prixVente = $request->prix_vente[$index];
            $vache_ids[] = "{$vacheId}*{$prixVente}";

            $vache = Vache::find($vacheId);
            if ($vache) {
                $vache->etat = 2;
                $vache->save(); // Enregistrer le changement d'état dans la base de données
            }
        }

        $vache_ids_string = implode(',', $vache_ids);

        // Enregistrer la vente dans la table ventesbovins
        VenteBovin::create([
            'vaches' => $vache_ids_string,
            'date_vente' => $request->date,
            'prix_vente' => $request->total_ravitaillement,
            'client' => $request->type_client === 'Client Comptoir' ? 'client comptoir' : $request->client_id,
            'total_remise' => $request->total_remise,
            'montantDette' => $request->total_ravitaillement-$request->montant_paye,
            'montant_paye' => $request->montant_paye,
            'payer_par' => $request->payer_par,
        ]);

        return redirect()->route('ventes_bovins.index')->with('success', 'Vente enregistrée avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vente = VenteBovin::findOrFail($id);
        $clients = Client::all(); // Obtenir tous les clients pour le formulaire d'édition
        $vaches = Vache::all(); // Obtenir toutes les vaches pour le formulaire d'édition
        $comptes = Compte::all(); // Obtenir tous les comptes de paiement

        // Décomposer les vaches dans le format attendu
        $vente->vaches = collect(explode(';', $vente->vaches))->map(function ($vache) {
            list($vache_id, $prixvente) = explode('*', $vache);
            return (object) ['operation_id' => $vache_id, 'prix_vente' => $prixvente];
        });

        return view('VenteBovins.edit', compact('vente', 'clients', 'vaches', 'comptes'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'type_client' => 'required|string',
            'prix_vente' => 'required|array',
            'prix_vente.*' => 'required|numeric',
            'operation_id' => 'required|array',
            'operation_id.*' => 'required|integer',
            'total_ravitaillement' => 'required|numeric',
            'total_remise' => 'required|numeric',
            'net_payer' => 'required|numeric',
            'montant_paye' => 'required|numeric',
            'payer_par' => 'required|integer',
        ]);

        $vente = VenteBovin::findOrFail($id);
        // Remettre à jour l'état des anciennes vaches
        foreach (explode(',', $vente->vaches) as $item) {
            list($vache_Id, $prixVente) = explode('*', $item);
            $vacheAn = Vache::find($vache_Id);
            if ($vacheAn) {
                $vacheAn->etat = 1;
                $vacheAn->save(); // Enregistrer le changement d'état dans la base de données
            }
        }

        // Mettre à jour les nouvelles vaches avec l'état vendu
        $vache_ids = [];
        foreach ($request->operation_id as $index => $vacheId) {
            $prixVente = $request->prix_vente[$index];
            $vache_ids[] = "{$vacheId}*{$prixVente}";

            $vachen = Vache::find($vacheId);
            if ($vachen) {
                $vachen->etat = 2;
                $vachen->save(); // Enregistrer le changement d'état dans la base de données
            }
        }
        $vache_ids_string = implode(',', $vache_ids);

        // Mise à jour de la vente dans la table ventesbovins
        $vente->update([
            'vaches' => $vache_ids_string,
            'date_vente' => $request->date,
            'prix_vente' => $request->total_ravitaillement,
            'client' =>$request->type_client === 'Client Fidèle' ? $request->client_id : null,
            'total_remise' => $request->total_remise,
            'montantDette' => $request->total_ravitaillement - $request->montant_paye,
            'montant_paye' => $request->montant_paye,
            'payer_par' => $request->payer_par,
        ]);

        return redirect()->route('ventes_bovins.index')->with('success', 'Vente mise à jour avec succès.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vente = VenteBovin::findOrFail($id);
        $vente->delete();

        return response()->json(['success' => true, 'message' => 'Vente supprimée avec succès.']);
    }

}
