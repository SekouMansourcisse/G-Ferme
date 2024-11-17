<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\CategorieOeuf;
use App\Models\Client;
use App\Models\Compte;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
class VenteSujetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer toutes les opérations de type "vente"
        $ventes = Operation::where('typevente', 'vente-sujet')->get();

        // Retourner la vue avec les ventes
        return view('venteSujet.list', compact('ventes'));
    }
    public function exportPdf()
    {
        $ventes = Operation::where('typevente', 'vente-sujet')->get();
        $pdf = Pdf::loadView('venteSujet.pdf', compact('ventes'));

        return $pdf->download('liste_Vente-poulets.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $bandes = Bande::where('etat','1')->get();
        $clients= Client::all();
        $comptes=Compte::all();
        return view('venteSujet.add',compact('bandes','clients','comptes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        DB::beginTransaction();
        try {
            // Créer l'opération
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
                'typevente' => $request->type,
            ]);

            // Mettre à jour le compte
            $compte = Compte::where('id', $request->payer_par)->first();
            $compte->update(['solde_actuel' => $compte->solde_actuel + $request->montant_paye]);

            // Ajouter les informations sur les sujets
            $sujetInfos = [];
            foreach ($request->qte_vente as $bandeId => $qteVendu) {
                $prixUnitaire = $request->prixUnitaire[$bandeId];
                $montantTotal = $request->Montant_total[$bandeId];

                $sujetInfos[] = "{$bandeId}*{$qteVendu}*{$prixUnitaire}*{$montantTotal}";

                $bande = Bande::findOrFail($bandeId);

                // Mettre à jour le cheptel des poulaillers dans la bande
                $poulaillers = explode(',', $bande->poulailler);
                foreach ($poulaillers as &$poulaillerEntry) {
                    list($poulaillerId, $cheptel) = explode('*', $poulaillerEntry);

                    if ($cheptel >= $qteVendu) {
                        $cheptel -= $qteVendu;
                        $poulaillerEntry = "{$poulaillerId}*{$cheptel}";
                        $qteVendu = 0;
                        break;
                    } else {
                        $qteVendu -= $cheptel;
                        $cheptel = 0;
                        $poulaillerEntry = "{$poulaillerId}*{$cheptel}";
                    }
                }

                if ($qteVendu > 0) {
                    throw new \Exception("Quantité vendue dépasse le cheptel disponible.");
                }

                $bande->poulailler = implode(',', $poulaillers);
                $bande->cheptel_actuel -= $request->qte_vente[$bandeId];
                $bande->qte_vendu += $request->qte_vente[$bandeId];
                $bande->save();
            }

            $operation->sujetInfos = implode(',', $sujetInfos);
            $operation->save();

            DB::commit();
            return redirect()->route('vente-sujets.index')->with('success', 'Vente de sujet ajoutée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout de la vente de sujet: ' . $e->getMessage());
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
    public function edit($id)
    {
        // Récupérer l'opération par ID
        $operation = Operation::findOrFail($id);

        // Récupérer les clients et comptes pour les afficher dans le formulaire
        $clients = Client::all();
        $comptes = Compte::all();
        $bandes = Bande::where('etat','1')->get();
        // Préparer les données pour le formulaire
        $sujetInfos = explode(',', $operation->sujetInfos);
        $categories = [];
        foreach ($sujetInfos as $info) {
            list($bandeId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
             $bande= Bande::find($bandeId)->nom_bande;
            $categories[] = [
                'bandeId' => $bandeId,
                'nom'=>$bande,
                'qteVendu' => $qteVendu,
                'prixUnitaire' => $prixUnitaire,
                'montantTotal' => $montantTotal,
            ];
        }

        return view('venteSujet.edit', compact('operation', 'clients', 'comptes', 'categories','bandes'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
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
                'prixUnitaire.*' => 'required|numeric|min:0',
                'Montant_total.*' => 'required|numeric|min:0',
            ]);

            // Récupérer l'opération à mettre à jour
            $operation = Operation::findOrFail($id);

            // Mettre à jour les informations de l'opération
            $operation->update([
                'NomPrenomClient' => $request->type_client === 'Client Comptoir' ? $request->client_comptoir : $request->client_id,
                'client' => $request->type_client === 'Client Fidèle' ? $request->client_id : null,
                'Montant_facture' => $request->net_payer,
                'Totalvente' => $request->total_ravitaillement,
                'TotalRavitaillement' => $request->total_ravitaillement,
                'montant_payé' => $request->montant_paye,
                'montantDette' => $request->dette_a_paye,
                'totalRemise' => $request->total_remise,
                'Payer_par' => $request->payer_par,
                'Date_op' => $request->date,
                'typevente' => $request->type,
            ]);

            // Mettre à jour le compte
            $compte = Compte::where('id', $request->payer_par)->first();
            $compte->update(['solde_actuel' => $compte->solde_actuel + $request->montant_paye]);

            // Récupérer les anciennes informations sur les sujets pour annuler les mises à jour
            $ancienSujetInfos = explode(',', $operation->sujetInfos);

            // Mettre à jour les quantités dans les bandes
            foreach ($ancienSujetInfos as $info) {
                list($bandeId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                $bande = Bande::findOrFail($bandeId);
                $bande->cheptel_actuel += $qteVendu; // Remonter le cheptel
                $bande->qte_vendu -= $qteVendu; // Décrémenter la quantité vendue
                $bande->save();
            }

            // Ajouter les nouvelles informations sur les sujets
            $sujetInfos = [];
            foreach ($request->qte_vente as $bandeId => $qteVendu) {
                $prixUnitaire = $request->prixUnitaire[$bandeId];
                $montantTotal = $request->Montant_total[$bandeId];

                $sujetInfos[] = "{$bandeId}*{$qteVendu}*{$prixUnitaire}*{$montantTotal}";

                $bande = Bande::findOrFail($bandeId);

                // Mettre à jour le cheptel des poulaillers dans la bande
                $poulaillers = explode(',', $bande->poulailler);
                foreach ($poulaillers as &$poulaillerEntry) {
                    list($poulaillerId, $cheptel) = explode('*', $poulaillerEntry);

                    if ($cheptel >= $qteVendu) {
                        $cheptel -= $qteVendu;
                        $poulaillerEntry = "{$poulaillerId}*{$cheptel}";
                        $qteVendu = 0;
                        break;
                    } else {
                        $qteVendu -= $cheptel;
                        $cheptel = 0;
                        $poulaillerEntry = "{$poulaillerId}*{$cheptel}";
                    }
                }

                if ($qteVendu > 0) {
                    throw new \Exception("Quantité vendue dépasse le cheptel disponible.");
                }

                $bande->poulailler = implode(',', $poulaillers);
                $bande->cheptel_actuel -= $request->qte_vente[$bandeId];
                $bande->qte_vendu += $request->qte_vente[$bandeId];
                $bande->save();
            }

            $operation->sujetInfos = implode(',', $sujetInfos);
            $operation->save();

            DB::commit();
            return redirect()->route('vente-sujets.index')->with('success', 'Vente de sujet mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la vente de sujet: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ventebande = Operation::findOrFail($id);
        $ventebande->delete();

        return response()->json(['success' => true, 'message' => 'Vente supprimée avec succès.']);
    }
}
