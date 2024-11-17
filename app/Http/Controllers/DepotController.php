<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Depot;
use Illuminate\Support\Facades\Log;
use App\Models\Compte;
use App\Models\Client;
class DepotController extends Controller
{
    public function index()
    {
        $depots = Depot::all();
        return view('depots.list', ['depots' => $depots]);
    }

    public function create()
    {
        $comptes = Compte::all();
        $clients = Client::all();
        return view('depots.add', ['comptes' => $comptes,'clients'=>$clients]);
    }
    public function getDepotSolde($clientId)
    {
        // Récupérer le dernier dépôt du client
        $depot = Depot::where('client_id', $clientId)->latest()->first();

        // Si un dépôt est trouvé, récupérer le solde actuel, sinon définir à 0
        $soldeDepotActuel = $depot ? $depot->solde_depot_actuel : 0;

        // Retourner le solde actuel en JSON
        return response()->json(['solde_depot_actuel' => $soldeDepotActuel]);
    }
    public function store(Request $request)
    {
        // Validation des données
       /* $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'solde_depot_actuel' => 'required|numeric',
            'montant_depot' => 'required|numeric',
            'versement_fait' => 'required|numeric',
            'compte_id' => 'required|exists:comptes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }*/

        try {
            // Calculer le nouveau solde de dépôt actuel
            $soldeDepotActuel = $request->input('solde_depot_actuel') + $request->input('montant_depot');

            // Préparer les données pour la création du dépôt
            $data = [
                'client_id' => $request->input('client_id'),
                'solde_depot_actuel' => $soldeDepotActuel,
                'montant_depot' => $request->input('montant_depot'),
                'versement_fait' => $request->input('versement_fait'),
                'compte_id' => $request->input('compte_id'),
            ];

            // Utiliser la méthode ajouterDepot pour créer un dépôt
            $depot = Depot::ajouterDepot($data);

            return response()->json(['success' => true, 'depot' => $depot], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du dépôt: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        // Code de mise à jour du dépôt
    }
}
