<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Parametre;
use Illuminate\Http\Request;
use App\Models\Remboursement;
use App\Models\Fournisseur;
use App\Models\Operation;
use App\Models\Compte;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Log;
class RemboursementController extends Controller
{
    public function index() {
        $remboursements = Remboursement::all();
        $fournisseurs = Fournisseur::all();
        $comptes = Compte::all();
        return view('fournisseur.Redevance', compact('remboursements','comptes','fournisseurs'));
    }
    public function exportPdf()
    {
        $remboursements = Remboursement::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('fournisseur.pdf_r', compact('remboursements','settings'));

        return $pdf->download('liste_Remboursement_fournisseur.pdf');
    }
    public function listdette(){
        $remboursements = Remboursement::all();
        $clients = Client::all();
        $comptes = Compte::all();
        return view('client.remboursement', compact('remboursements','comptes','clients'));
    }
    public function exportPdf2()
    {
        $remboursements = Remboursement::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('client.pdf_r', compact('remboursements','settings'));

        return $pdf->download('liste_Remboursement_client.pdf');
    }
    public function create()
    {
        $fournisseurs = Fournisseur::all();
        $comptes = Compte::all();
        return view('fournisseur.addRedevance', compact('fournisseurs','comptes'));
    }
    public function create2()
    {
        $clients = Client::all();
        $comptes = Compte::all();
        return view('client.addRemboursement', compact('clients','comptes'));
    }
    public function getOperations($id)
    {
        $operations = Operation::where('Fournisseur', $id)->where('montantDette', '>', 0)->get();
        $totalDette = $operations->sum('montantDette');
        return response()->json(['operations' => $operations, 'totalDette' => $totalDette]);
    }
    public function getOperations2($id)
    {
        $operations = Operation::where('client', $id)->where('montantDette', '>', 0)->get();
        $totalDette = $operations->sum('montantDette');
        return response()->json(['operations' => $operations, 'totalDette' => $totalDette]);
    }

    public function store(Request $request)
    {
        // Valider les données du formulaire
        /*$request->validate([
            'date_reglement' => 'required|date',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'montant_paye' => 'required|array',
            'montant_paye.*' => 'numeric|min:0',
            'payer_par' => 'required|exists:comptes,id',
        ]);*/

        // Initialiser une transaction pour assurer la cohérence des données
        DB::beginTransaction();

        try {
            if($request->type=="client"){
                $idP=$request->client_id ;
            }else{
                $idP=$request->fournisseur_id;
            }
            // Mettre à jour les opérations dont montant_paye est différent de zéro
            foreach ($request->montant_paye as $operationId => $montantPaye) {
                if ($montantPaye > 0) {
                    $operation = Operation::find($operationId);
                    if ($operation) {
                        // Remettre la dette à zéro
                        if($operation->montantDette != 0)
                        {
                            $mt=$operation->montantDette;
                            $operation->montantDette = 0;
                            $operation->save();
                            if($request->type=="fournisseur")
                            {
                                $fournisseur_nom = Fournisseur::find($request->fournisseur_id)->nom;
                                // Enregistrer le remboursement
                                Remboursement::create([
                                    'NomPrenomFournisseur' => $fournisseur_nom,
                                    'fournisseur' => $request->fournisseur_id,
                                    'virement_par' => $request->virement,
                                    'montant_paye' => $mt,
                                    'Dette' => $mt,
                                    'payer_par' => $request->payer_par,
                                    'operation' => $operationId,
                                    'date_r' => $request->date_reglement
                                ]);
                            }else{
                                $client_nom = Client::find($request->client_id)->nom;
                                // Enregistrer le remboursement
                                Remboursement::create([
                                    'NomPrenomClient' => $client_nom,
                                    'client' => $request->client_id,
                                    'virement_par' => $request->virement,
                                    'montant_paye' => $mt,
                                    'Dette' => $mt,
                                    'payer_par' => $request->payer_par,
                                    'operation' => $operationId,
                                    'date_r' => $request->date_reglement
                                ]);
                            }

                        }

                    }
                }
            }

            // Soustraire le montant payé du solde actuel du compte associé
            $compte = Compte::find($request->payer_par);

            if ($compte) {
                if($request->type=="fournisseur") {
                    $compte->solde_actuel -= $request->montant_p;
                }else{
                    $compte->solde_actuel += $request->montant_p;
                }

                $compte->save();
            }



            // Confirmer la transaction
            DB::commit();

            // Répondre avec un message de succès
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show($id) {
        try {
            $remboursement = Remboursement::with('compte', 'OperationR')->findOrFail($id);
            return response()->json([
                'success' => true,
                'remboursement' => $remboursement,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching remboursement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Remboursement non trouvé'
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
        // Valider les données du formulaire
       /* $request->validate([
            'date_reglement' => 'required|date',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'montant' => 'required|numeric|min:0',
            'payer_par' => 'required|exists:comptes,id',
        ]);*/

        // Initialiser une transaction pour assurer la cohérence des données
        DB::beginTransaction();

        try {
            // Récupérer le remboursement existant
            $remboursement = Remboursement::findOrFail($id);
            Log::info($request->all());
            // Mettre à jour les détails du remboursement
            $remboursement->date_r = $request->date_r;
            $remboursement->montant_paye = $request->montant_paye;
            $remboursement->payer_par = $request->payer_par;
            if($request->type=="fournisseur"){
                $remboursement->fournisseur = $request->fournisseur;
                $fournis=Fournisseur::find($request->remboursement_id);
                $remboursement->NomPrenomFournisseur=$fournis->prenom .' '. $fournis->nom;
            }else{
                $remboursement->client = $request->client;
                $clients=Client::find($request->client);
                $remboursement->NomPrenomClient=$clients->prenom .' '. $clients->nom;
            }
            $remboursement->save();

            // Mettre à jour le solde du compte
            $compte = Compte::find($request->payer_par);
            if ($compte) {
                if($request->type=="fournisseur") {
                    $compte->solde_actuel -= $request->montant_paye;
                }else{
                    $compte->solde_actuel += $request->montant_paye;
                }
                $compte->save();
            }

            // Confirmer la transaction
            DB::commit();

            // Répondre avec un message de succès
            return response()->json(['success' => true, 'message' => 'redevance mise à jour avec succès']);

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Ajoutez cette fonction pour récupérer les données du remboursement à éditer
    public function edit($id)
    {
        $remboursement = Remboursement::findOrFail($id);
        return response()->json(['remboursement' => $remboursement]);
    }

    public function destroy($id) {
        $remboursement = Remboursement::find($id);

        if ($remboursement) {
            $remboursement->delete();
            return response()->json(['success' => true, 'message' => 'Remboursement supprimé avec succès']);
        }

        return response()->json(['success' => false, 'message' => 'Remboursement non trouvé'], 404);
    }
}
