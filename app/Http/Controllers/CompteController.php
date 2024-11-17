<?php
namespace App\Http\Controllers;

use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Transfert;
class CompteController extends Controller
{
    public function index()
    {
        // Utilisez paginate(10) pour paginer les résultats (10 lignes par page)
        $comptes = Compte::paginate(10);

        // Retourner la vue avec les comptes paginés
        return view('comptes.list', compact('comptes'));
    }

    public function create()
    {
        return view('comptes.add');
    }
    public function historique($id)
    {
        $compte=Compte::find($id);
        $historique = $this->getHistoriqueCompte($id);

        return view('comptes.historique', compact('historique','compte'));
    }

    public function getHistoriqueCompte($compteId, $perPage = 5)
    {
        // Récupérer le compte
        $compte = Compte::with(['commandes', 'depenses', 'operations', 'operationsRetour', 'remboursements'])
            ->findOrFail($compteId);

        // Fusionner tous les historiques
        $historique = collect();

        // Ajouter les commandes
        foreach ($compte->commandes as $commande) {
            $historique->push([
                'type' => 'Commande',
                'date' => $commande->date,
                'montant' => $commande->Montant_paye,
                'details' => 'Commande N°:'.$commande->id
            ]);
        }

        // Ajouter les dépenses
        foreach ($compte->depenses as $depense) {
            $historique->push([
                'type' => $depense->typeDepense->Denomination,
                'date' => $depense->Date_depense,
                'montant' => $depense->Montant_paye,
                'details' => 'Depense N°:'.$depense->id
            ]);
        }

        // Ajouter les opérations
        foreach ($compte->operations as $operation) {

                $historique->push([
                    'type' => $operation->typeOperation,
                    'date' => $operation->Date_op,
                    'montant' => $operation->montant_payé,
                    'details' => 'Operation N°:'.$operation->id
                ]);


        }

        // Ajouter les retours d'opération
        foreach ($compte->operationsRetour as $operationRetour) {
            $historique->push([
                'type' => 'Retour d’opération',
                'date' => $operationRetour->date_op,
                'montant' => $operationRetour->Montant_R,
                'details' => 'Commande N°'.$operationRetour->commande_id
            ]);
        }

        // Ajouter les remboursements
        foreach ($compte->remboursements as $remboursement) {
            if($remboursement->client==null)
            {
                $type="Remboursement a un Fournisseur";
            }else{
                $type=" Dette payé par un client";
            }
            $historique->push([
                'type' => $type,
                'date' => $remboursement->date_r,
                'montant' => $remboursement->montant_paye,
                'details' => 'Remboursement N°'.$remboursement->id,
            ]);
        }

        // Trier par date
        $historique = $historique->sortByDesc('date');

        // Paginer manuellement
        $page = request()->get('page', 1); // Page actuelle, par défaut 1
        $total = $historique->count(); // Nombre total d'éléments

        $historique = $historique->slice(($page - 1) * $perPage, $perPage)->values(); // Découpe des éléments pour la page

        // Créer l'instance de LengthAwarePaginator
        return new LengthAwarePaginator($historique, $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Denomination' => 'required|string|max:255',
            'solde_actuel' => 'required|string|max:255',
            'infos_supp'=>'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $compte = new Compte();
            $compte->Denomination = $request->input('Denomination');
            $compte->solde_actuel = $request->input('solde_actuel');
            $compte->infos_supp = $request->input('infos_supp');
            // Ajoutez d'autres champs au besoin

            $compte->save();

            return response()->json(['success' => true, 'compte' => $compte], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du compte: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'edit-denomination' => 'required|string|max:255',
                'edit-solde-actuel' => 'required|string|max:255',
                'edit-infos-supplementaires'=>'nullable|string'
                // Ajoutez d'autres règles de validation au besoin
            ]);

            $compte = Compte::findOrFail($id);
            $data = [
                'Denomination' => $request->input('edit-denomination'),
                'solde_actuel' => $request->input('edit-solde-actuel'),
                'infos_supp' => $request->input('edit-infos-supplementaires'),
                // Ajoutez d'autres champs au besoin
            ];

            $compte->update($data);

            return response()->json(['success' => true, 'message' => 'Compte mis à jour avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour du compte', 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $compte = Compte::findOrFail($id);
            $compte->delete();
            return response()->json(['success' => true, 'message' => 'Compte supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du compte', 'error' => $e->getMessage()]);
        }
    }
}

