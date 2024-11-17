<?php

namespace App\Http\Controllers;

use App\Models\produitOeuf;
use App\Models\Client;
use App\Models\Compte;
use App\Models\Operation;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class VenteAutreController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
     {
         // Récupérer toutes les opérations de type "vente"
         $ventes = Operation::where('typevente', 'vente-autre')->get();

         // Retourner la vue avec les ventes
         return view('AutreVente.list', compact('ventes'));
     }

     public function exportPdf()
     {
        $ventes = Operation::where('typevente', 'vente-autre')->get();
         $pdf = Pdf::loadView('AutreVente.pdf', compact('ventes'));

         return $pdf->download('liste_Vente-Autres.pdf');
     }

     /**
      * Show the form for creating a new resource.
      */
     public function create()
     {

         $produits = Produit::where('typeProduit','Les produits commercialisable')->get();
         $clients= Client::all();
         $comptes=Compte::all();
         return view('AutreVente.add',compact('produits','clients','comptes'));
     }

     /**
      * Store a newly created resource in storage.
      */
     public function store(Request $request)
     {
         // Valider les données du formulaire
         $request->validate([
             'date' => 'required|date',
             'type_client' => 'required|string',
             'client_comptoir' => 'nullable|string',
             'client_id' => 'nullable|exists:client,id',
             'phone' => 'required|string',
             'total_ravitaillement' => 'required|numeric',
             'total_remise' => 'required|numeric',
             'net_payer' => 'required|numeric',
             'montant_paye' => 'required|numeric',
             'dette_a_paye' => 'required|numeric',
             'payer_par' => 'required|exists:compte,id',
             'qte_vente.*' => 'required|integer|min:0',
             'alv.*' => 'nullable',
         ]);



         // Début de la transaction
         DB::beginTransaction();

         try {
             // Créer une nouvelle opération
             $operation = Operation::create([
                 'NomPrenomClient' => $request->type_client === 'Client Comptoir' ? $request->client_comptoir : $request->client_id,
                 'client' => $request->type_client === 'Client Fidèle' ? $request->client_id : null,

                 'Montant_facture' => $request->net_payer,
                 'Totalvente' => $request->total_ravitaillement,
                 'montant_payé' => $request->montant_paye,
                 'montantDette' => $request->dette_a_paye,
                 'totalRemise' => $request->total_remise,
                 'typeOperation' => 'vente',
                 'Fournisseur' => null,
                 'NomPrenomFournisseur' => null,
                 'Produit' => null,
                 'TotalRavitaillement' => $request->total_ravitaillement,
                 'Payer_par' => $request->payer_par,
                 'Date_op' => $request->date,
                 'typevente'=>$request->type,
             ]);

             // Mettre à jour les quantités dans la table produitoeuf
             $sujetInfos = [];
             foreach ($request->qte_vente as $produitId => $qteVendu) {
                 $prixUnitaire = $request->prixUnitaire[$produitId];
                 $montantTotal = $request->Montant_total[$produitId];

                 $sujetInfos[] = "{$produitId}*{$qteVendu}*{$prixUnitaire}*{$montantTotal}";

                 $produit = Produit::findOrFail($produitId);

                 $produit->qte_stock -= $request->qte_vente[$produitId];

                 $produit->save();
             }
             $operation->AutresInfos = implode(',', $sujetInfos);
             $operation->save();
             // Mettre à jour le compte
             $compte = Compte::where('id', $request->payer_par)->first();
             $compte->update(['solde_actuel' => $compte->solde_actuel + $request->montant_paye]);
             // Confirmer la transaction
             DB::commit();

             return redirect()->route('vente-autres.index')->with('success', 'Vente enregistrée avec succès.');

         } catch (\Exception $e) {
             // Annuler la transaction en cas d'erreur
             DB::rollback();

             return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la vente.');
         }
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
// Dans le contrôleur VenteAutreController.php
public function edit($id)
{
    // Récupérer la vente de produits par son ID
    $operation = Operation::where('typevente', 'vente-autre')
        ->where('id', $id)
        ->firstOrFail();

    // Récupérer les clients, comptes et produits
    $clients = Client::all();
    $comptes = Compte::all();
    $produits = Produit::all();

    // Analyser la chaîne AutresInfos pour créer un tableau de produits
    $infosProduitArray = [];
    if (!empty($operation->AutresInfos)) {
        $infos = explode(',', $operation->AutresInfos);
        foreach ($infos as $info) {
            list($produitId, $quantite, $prixUnitaire, $montantTotal) = explode('*', $info);
            $produit = Produit::find($produitId);

            // Ajouter le produit et ses informations au tableau
            $produitsInfos[] = [
                'produit' => $produit,
                'quantite' => $quantite,
                'prixUnitaire' => $prixUnitaire,
                'montantTotal' => $montantTotal,
            ];
        }
    }

    return view('AutreVente.edit', compact('operation', 'clients', 'comptes', 'produits', 'produitsInfos'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'type_client' => 'required|string',
            'client_comptoir' => 'nullable|string',
            'client_id' => 'nullable|exists:client,id',
            'phone' => 'required|string',
            'total_ravitaillement' => 'required|numeric',
            'total_remise' => 'required|numeric',
            'net_payer' => 'required|numeric',
            'montant_paye' => 'required|numeric',
            'dette_a_paye' => 'required|numeric',
            'payer_par' => 'required|exists:compte,id',
            'qte_vente.*' => 'required|integer|min:0',
            'alv.*' => 'nullable',
        ]);

        if($request->client_id)
        {
            $client=Client::find($request->client_id);
            $nomClient=$client->nom .' '.$client->prenom;
        }
        DB::beginTransaction();

        try {
            $operation = Operation::findOrFail($id);
            $operation->update([
                'NomPrenomClient' => $request->type_client === 'Client Comptoir' ? 'client au comptoir' : $nomClient,
                'client' => $request->type_client === 'Client Fidèle' ? $request->client_id : null,
                'Montant_facture' => $request->net_payer,
                'Totalvente' => $request->total_ravitaillement,

                'montant_payé' => $request->montant_paye,
                'montantDette' => $request->dette_a_paye,
                'totalRemise' => $request->total_remise,
                'typeOperation' => 'vente',
                'Fournisseur' => null,
                'NomPrenomFournisseur' => null,
                'Produit' => null,
                'TotalRavitaillement' => $request->total_ravitaillement,
                'Payer_par' => $request->payer_par,
                'Date_op' => $request->date,
                'typevente'=>$request->type,
            ]);

            $sujetInfos = [];
            foreach ($request->qte_vente as $produitId => $qteVendu) {
                $prixUnitaire = $request->prixUnitaire[$produitId];
                $montantTotal = $request->Montant_total[$produitId];

                $sujetInfos[] = "{$produitId}*{$qteVendu}*{$prixUnitaire}*{$montantTotal}";

                $produit = Produit::findOrFail($produitId);
                $produit->qte_stock -= $qteVendu;
                $produit->save();
            }
            $operation->AutresInfos = implode(',', $sujetInfos);
            $operation->save();

            $compte = Compte::where('id', $request->payer_par)->first();
            $compte->update(['solde_actuel' => $compte->solde_actuel + $request->montant_paye]);

            DB::commit();

            return redirect()->route('vente-autres.index')->with('success', 'Vente mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la vente.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vente = Operation::findOrFail($id);
        $vente->delete();

        return response()->json(['success' => true, 'message' => 'Vente supprimée avec succès.']);
    }
}
