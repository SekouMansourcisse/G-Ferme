<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Compte;
use App\Models\Transfert;

class TransfertController extends Controller
{
    /**
     * Effectuer un transfert de fonds entre deux comptes.
     */

     public function index()
     {
        $comptes = Compte::all();
         $transferts = Transfert::all();
         return view('comptes.transfert', ['transferts' => $transferts,'comptes'=>$comptes]);
     }
     public function appro_caisse(Request $request)
     {
        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'compte_id' => 'required|exists:compte,id',
            'denomination' => 'required',
            'montant_appro' => 'required',
            'type' => 'required',
            'logo' => 'required|file|mimes:jpeg,png,jpg,pdf',
        ]);

        $compte= Compte::find($request->input('compte_id'));
        if($compte){

            if ($request->hasFile('logo'))
            {
                // Sauvegarder le fichier
                $file = $request->file('logo');
                $path = $file->store('Transfert_fond', 'public'); // Stocker dans un dossier 'transfert_fond' dans storage/app/public
                // Enregistrement du transfert dans la table des transferts
                $transfert = new Transfert();
                $transfert->compte_source_id = $request->input('compte_id');
                $transfert->compte_destination_id = $request->input('compte_id');
                $transfert->montant = $request->input('montant_appro');
                $transfert->typetransfert=$request->input('type');
                $transfert->justificatif=$path;
                $transfert->save();
                $compte->solde_actuel+=$request->input('montant_appro');
                $compte->save();

                return redirect()->back()->with('success','Approvisionnement effectuée avec succès');
            }
        }

     }
    public function store(Request $request)
    {
        log::info($request->all()); // Debug: afficher les données envoyées
        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'compte_source' => 'required|exists:compte,id',
            'compte_destination' => 'required|exists:compte,id',
            'montant' => 'required',
            'logo' => 'required|file|mimes:jpeg,png,jpg,pdf',
        ]);


        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Récupération des comptes source et destination
        $compteSource = Compte::findOrFail($request->input('compte_source'));
        $compteDestination = Compte::findOrFail($request->input('compte_destination'));

        // Récupération du montant à transférer
        $montant = $request->input('montant');

        // Vérification si le solde du compte source est suffisant pour le transfert
        if ($compteSource->solde_actuel < $montant) {
            return response()->json(['success' => false, 'message' => 'Solde insuffisant pour effectuer ce transfert'], 422);
        }

        try {
            // Début de la transaction
            DB::beginTransaction();

            if ($request->hasFile('logo'))
            {
                // Déduction du montant du solde du compte source
                $compteSource->solde_actuel -= $montant;
                $compteSource->save();

                // Ajout du montant au solde du compte destination
                $compteDestination->solde_actuel += $montant;
                $compteDestination->save();

                // Sauvegarder le fichier
                $file = $request->file('logo');
                $path = $file->store('Transfert_fond', 'public'); // Stocker dans un dossier 'transfert_fond' dans storage/app/public
                // Enregistrement du transfert dans la table des transferts
                $transfert = new Transfert();
                $transfert->compte_source_id = $compteSource->id;
                $transfert->compte_destination_id = $compteDestination->id;
                $transfert->montant = $montant;
                $transfert->justificatif=$path;
                $transfert->typetransfert="Transfert de fond";
                $transfert->save();

                // Validation de la transaction
                DB::commit();

                return response()->json(['success' => true, 'message' => 'Transfert effectué avec succès'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'aucun fichier trouvée'], 500);
            }
        } catch (\Exception $e) {
            // En cas d'erreur, annulation de la transaction et enregistrement de l'erreur dans les logs
            DB::rollBack();
            Log::error('Erreur lors du transfert de fonds : ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Une erreur est survenue lors du transfert de fonds'], 500);
        }

    }
    public function update(Request $request, $id)
    {
        try {
            // Validation des données du formulaire
            $request->validate([
                'edit-denomination' => 'required|string|max:255',
                'edit-solde-actuel' => 'required|numeric|min:0',
                'edit-infos-supplementaires' => 'nullable|string',
                // Ajoutez d'autres règles de validation au besoin
            ]);

            // Récupération du compte à mettre à jour
            $compte = Compte::findOrFail($id);
            $data = [
                'Denomination' => $request->input('edit-denomination'),
                'solde_actuel' => $request->input('edit-solde-actuel'),
                'infos_supp' => $request->input('edit-infos-supplementaires'),
                // Ajoutez d'autres champs au besoin
            ];

            // Mise à jour des données du compte
            $compte->update($data);

            return response()->json(['success' => true, 'message' => 'Compte mis à jour avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour du compte', 'error' => $e->getMessage()]);
        }
    }
    public function getTransfertDetails($id)
    {
        try {
            $transfert = Transfert::with('compteSource', 'compteDestination')->findOrFail($id);
            return response()->json(['success' => true, 'transfert' => $transfert], 200);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une réponse JSON avec le message d'erreur
            return response()->json(['success' => false, 'message' => 'Erreur lors de la récupération des détails du transfert'], 500);
        }
    }
    public function destroy($id)
    {
        try {
            // Recherche du compte à supprimer
            $compte = Transfert::findOrFail($id);

            // Suppression du compte
            $compte->delete();

            return response()->json(['success' => true, 'message' => 'Compte supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du compte', 'error' => $e->getMessage()]);
        }
    }

}
