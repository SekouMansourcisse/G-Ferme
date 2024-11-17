<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\CategorieOeuf;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Compte;
use App\Models\Entreprise;
use App\Models\Operation;
use App\Models\OperationRetour;
use App\Models\Parametre;
use App\Models\Produit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
class CommandeController extends Controller
{
    // Afficher la liste des commandes
    public function index()
    {
        $commandes = Commande::all();
        return view('commande.list', compact('commandes'));
    }

    public function FactureRemb($id)
    {
        $comptes= Compte::all();
        $commande = Commande::findOrFail($id);
        $client=Client::findOrFail($commande->client);
        $settings = Parametre::first();
        $etatRemboursement = $this->MemeEtat($id);
        $operations = OperationRetour::where('commande_id', $id)
        ->where('TypeRetour', 'Remboursement')
        ->get();
        return view('commande.BonRemboursement', compact('commande','comptes','client','operations','etatRemboursement','settings'));
    }
    public function RembourserVente(Request $request)
    {
        // Valider les entrées
        $validated = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'payer_par' => 'required|exists:compte,id',
        ]);

        $id = $validated['commande_id'];
        $payerPar = $validated['payer_par'];

        // Récupérer les opérations à rembourser
        $operations = OperationRetour::where('commande_id', $id)
            ->where('TypeRetour', 'Remboursement')
            ->where('etat', '1') // Opérations en attente de remboursement
            ->get();

        if ($operations->isEmpty()) {
            return redirect()->back()->with('error', 'Aucune opération en attente de remboursement.');
        }

        // Récupérer le compte à débiter
        $compte = Compte::find($payerPar);
        if (!$compte) {
            return redirect()->back()->with('error', 'Compte non trouvé.');
        }

        // Parcourir les opérations et les rembourser
        foreach ($operations as $operation) {
            // Débiter le montant du compte
            $compte->solde_actuel -= $operation->Montant_R;
            $compte->save();

            // Mettre à jour l'état de l'opération à remboursé (etat = 2)
            $operation->etat = 2;
            $operation->save();
        }

        return redirect()->back()->with('success', 'Remboursement effectué avec succès.');
    }

    private function MemeEtat($id)
    {
        $reponse = true; // On suppose que toutes les opérations sont dans l'état attendu
        $operations = OperationRetour::where('commande_id', $id)
            ->where('TypeRetour', 'Remboursement')
            ->get();

        foreach ($operations as $operation) {
            if ($operation->etat != 1) {
                $reponse = false; // Si on trouve une opération avec etat différent de 1
                break; // On quitte immédiatement la boucle
            }
        }

        return $reponse; // On retourne la réponse finale
    }
    private function BonSigner($id)
    {
        $reponse = true; // On suppose que toutes les opérations sont dans l'état attendu
        $operations = OperationRetour::where('commande_id', $id)
            ->where('TypeRetour', 'Remplacement')
            ->get();

        foreach ($operations as $operation) {
            if (!empty($operation->BonRemp_signer)) {
                $reponse = false; // Si on trouve une opération avec etat différent de 1
                break; // On quitte immédiatement la boucle
            }
        }

        return $reponse; // On retourne la réponse finale
    }

    public function FactureRemp($id)
    {
        $comptes= Compte::all();
        $commande = Commande::findOrFail($id);
        $etatRemboursement = $this->BonSigner($id);
        $settings = Parametre::first();
        $operations = OperationRetour::where('commande_id', $id)
        ->where('TypeRetour', 'Remplacement')
        ->get();
        $client=Client::findOrFail($commande->client);
        return view('commande.BonRemplacement', compact('commande','comptes','client','operations','etatRemboursement','settings'));
    }
    public function showDetails($id)
    {
        $comptes= Compte::all();
        $commande = Commande::findOrFail($id);
        $entreprises=Entreprise::all();
        if($commande->client!=null)
        {
            $client=Client::findOrFail($commande->client);
        }else{
            $client=$commande->NomPrenomClient;
        }
        $settings = Parametre::first();
        return view('commande.facture', compact('commande','comptes','client','settings'));
    }
    public function showInvoiceP($id)
    {
        $comptes= Compte::all();
        $commande = Commande::findOrFail($id);
        $entreprises=Entreprise::all();
        $client=Client::findOrFail($commande->client);
        $settings = Parametre::first();
        return view('commande.InvoicePaye', compact('commande','comptes','client','settings'));
    }
    public function uploadBonRemplacement(Request $request)
    {
        // Validation du fichier
        $request->validate([
            'logo' => 'required|file|mimes:jpeg,png,jpg,pdf', // Adapter les types de fichiers autorisés et la taille
        ]);

        // Récupérer la commande via l'ID

        $operations = OperationRetour::where('commande_id', $request->input('commande_id'))
        ->where('TypeRetour', 'Remplacement')
        ->get();
        if ($request->hasFile('logo')) {
            // Sauvegarder le fichier
            $file = $request->file('logo');
            $path = $file->store('Remplacement', 'public'); // Stocker dans un dossier 'factures' dans storage/app/public

            if ($operations) {
                foreach ($operations as $operation) {
                    # code...

                    // Mettre à jour la colonne document avec le chemin du fichier et changer l'état à 3
                    $operation->BonRemp_signer = $path;
                    $operation->save();


                }

            } else {
                return redirect()->back()->with('error', 'Commande introuvable.');
            }
        } else {
            return redirect()->back()->with('error', 'Aucun fichier trouvé.');
        }
        return redirect()->back()->with('success', 'Operation de remplacement effectuée avec succès.');
    }
    public function uploadInvoice(Request $request)
    {
        // Validation du fichier
        $request->validate([
            'logo' => 'required|file|mimes:jpeg,png,jpg,pdf', // Adapter les types de fichiers autorisés et la taille
        ]);

        // Récupérer la commande via l'ID
        $commande = Commande::find($request->input('commande_id'));

        if ($commande) {
            // Vérifier si un fichier a été uploadé
            if ($request->hasFile('logo')) {
                // Sauvegarder le fichier
                $file = $request->file('logo');
                $path = $file->store('factures', 'public'); // Stocker dans un dossier 'factures' dans storage/app/public

                // Mettre à jour la colonne document avec le chemin du fichier et changer l'état à 3
                $commande->document = $path;
                $commande->etat = 3;
                $commande->save();

                // Redirection avec un message de succès
                session()->flash('active_tab', 'livre');
                return redirect()->route('commandes.index')->with('success', 'Facture payé et livré !');
            } else {
                return redirect()->back()->with('error', 'Aucun fichier trouvé.');
            }
        } else {
            return redirect()->back()->with('error', 'Commande introuvable.');
        }
    }

    public function exportList()
    {
        $operationsRetour = OperationRetour::whereNotNull('commande_id')
            ->get()
            ->groupBy(function($item) {
                return $item->commande_id . '-' . $item->TypeRetour;
            });
            $settings = Parametre::first();
        $pdf = Pdf::loadView('commande.retour_pdf', compact('operationsRetour','settings'));

        return $pdf->download('List_retourVente.pdf');
    }
    public function process($id)
    {
        $commande = Commande::findOrFail($id);
        // Logic pour traiter le paiement
        $commande->etat = 2; // 2 = Payé
        $commande->save();

        return redirect()->route('commande.details', $commande->id)->with('success', 'Paiement effectué avec succès');
    }
    public function fetchOperations(Request $request)
    {
        $type = $request->input('type');
        $operations = Operation::where('typevente', $type)->get();
        return response()->json(['operations' => $operations]);
    }

    public function fetchOperationDetails(Request $request)
    {
        $operationId = $request->input('id');
        $typeOperation = $request->input('type');

        $operation = Operation::find($operationId);

        $details = [];

        switch ($typeOperation) {
            case 'vente-oeuf':
                $infoOeuf = explode(';', $operation->infosOeuf);
                foreach ($infoOeuf as $info) {
                    list($categorieId, $qteVendu) = explode('*', $info);
                    $categorie = CategorieOeuf::find($categorieId);
                    $details[] = [
                        'libelle' => $categorie->Denomination,
                        'qte_vendu' => $qteVendu,
                        'prix_unitaire' => $categorie->PrixPlateaux,
                        'id' => $categorieId
                    ];
                }
                break;

            case 'vente-sujet':
                $sujetInfos = explode(';', $operation->sujetInfos);
                foreach ($sujetInfos as $info) {
                    list($bandeId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                    $bande = Bande::find($bandeId);
                    $details[] = [
                        'libelle' => $bande->nom_bande,
                        'qte_vendu' => $qteVendu,
                        'prix_unitaire' => $prixUnitaire,
                        'id' => $bandeId
                    ];
                }
                break;

            case 'vente-autre':
                $autresInfos = explode(';', $operation->AutresInfos);
                foreach ($autresInfos as $info) {
                    list($produitId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                    $produit = Produit::find($produitId);
                    $details[] = [
                        'libelle' => $produit->Denomination,
                        'qte_vendu' => $qteVendu,
                        'prix_unitaire' => $prixUnitaire,
                        'id' => $produitId
                    ];
                }
                break;
        }

        return response()->json(['details' => $details]);
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('commandes.create');
    }
    public function RetourVente()
    {
        $operationsRetour = OperationRetour::whereNotNull('commande_id')
            ->get()
            ->groupBy(function($item) {
                return $item->commande_id . '-' . $item->TypeRetour;
            });


        return view('commande.listRetour', compact('operationsRetour'));
    }
    public function RetourV()
    {
        $comptes=Compte::all();
        $operations = Operation::all();
        $operations=$operations->unique('commande_id');
        return view('commande.RetourVente',compact('comptes','operations'));
    }
    // Enregistrer une nouvelle commande
    public function store(Request $request)
    {
        // Récupération des données du formulaire
        $typeVente = $request->input('type_vente');
        $date = $request->input('date');
        $typeClient = $request->input('type_client');
        if($typeClient=="Client Comptoir")
        {
            $NomPrenomClient = $request->input('client_comptoir');
        }else {
            $clientInfos=Client::find($request->input('client_id'));
            $NomPrenomClient =$clientInfos->nom;
        }

        $client = $request->input('client_id');
        $totalVente = $request->input('total_ravitaillement');
        $totalRemise = $request->input('total_remise');
        $netAPayer = $request->input('net_payer');
        $etat = 1;

        // Formatage des produits
        $produits = [];
        if ($request->has('qte_vente')) {
            foreach ($request->input('qte_vente') as $produitId => $quantite) {
                $produitV=Produit::find($produitId);
                $prixVente = $produitV->prix_unitaire;
                $montantTotal = $quantite * $prixVente;
                $produits[] = $produitId . '*' . $quantite . '*' . $prixVente . '*' . $montantTotal;
            }
        }
        $produitsString = implode(',', $produits);

        // Formatage des oeufs
        $oeufs = [];
        if ($request->has('qte_plateaux')) {
            foreach ($request->input('qte_plateaux') as $categorieId => $quantite) {
                // Récupération du prixPlateaux depuis la base de données en fonction du categorieId
                $categorieOeuf = CategorieOeuf::find($categorieId);

                // Vérifiez si la catégorie existe
                if ($categorieOeuf) {
                    $prixPlateaux = $categorieOeuf->PrixPlateaux;
                    $montantTotal = $quantite * $prixPlateaux;
                    $oeufs[] = $categorieId . '*' . $quantite . '*' . $montantTotal;
                }
            }
        }
        $oeufsString = implode(',', $oeufs);

        // Formatage des poulets
        $poulets = [];
        if ($request->has('qte_vente1')) {
            foreach ($request->input('qte_vente1') as $bandeId => $quantite) {
                $prixUnitaire = $request->input('prixUnitaire1')[$bandeId];
                $prixTotal = $quantite * $prixUnitaire;
                $poulets[] = $bandeId . '*' . $quantite . '*' . $prixUnitaire . '*' . $prixTotal;
            }
        }
        $pouletsString = implode(',', $poulets);

        // Assurez-vous que $produitsString, $oeufsString, et $pouletsString sont des chaînes et non des tableaux
        $produitsString = isset($produitsString) ? (string)$produitsString : '';
        $oeufsString = isset($oeufsString) ? (string)$oeufsString : '';
        $pouletsString = isset($pouletsString) ? (string)$pouletsString : '';
        if($typeClient=="Client Comptoir")
        {
            // Insertion dans la table Commande
            Commande::create([
                'type_vente' => json_encode($typeVente),  // Encodage en JSON si c'est un tableau
                'date' => $date,
                'NomPrenomClient' => $NomPrenomClient,
                'produit' => is_array($produitsString) ? json_encode($produitsString) : $produitsString,  // Assurez-vous que c'est une chaîne
                'oeufs' => is_array($oeufsString) ? json_encode($oeufsString) : $oeufsString,  // Assurez-vous que c'est une chaîne
                'poulets' => is_array($pouletsString) ? json_encode($pouletsString) : $pouletsString,  // Assurez-vous que c'est une chaîne
                'TotalVente' => $totalVente,
                'TotalRemise' => $totalRemise,
                'Net_a_payer' => $netAPayer,
                'etat' => $etat,
            ]);

        }else {

            // Insertion dans la table Commande
            Commande::create([
                'type_vente' => json_encode($typeVente),  // Encodage en JSON si c'est un tableau
                'date' => $date,
                'NomPrenomClient' => $NomPrenomClient,
                'client' => $client,
                'produit' => is_array($produitsString) ? json_encode($produitsString) : $produitsString,  // Assurez-vous que c'est une chaîne
                'oeufs' => is_array($oeufsString) ? json_encode($oeufsString) : $oeufsString,  // Assurez-vous que c'est une chaîne
                'poulets' => is_array($pouletsString) ? json_encode($pouletsString) : $pouletsString,  // Assurez-vous que c'est une chaîne
                'TotalVente' => $totalVente,
                'TotalRemise' => $totalRemise,
                'Net_a_payer' => $netAPayer,
                'etat' => $etat,
            ]);

        }




        return redirect()->back()->with('success', 'Commande ajoutée avec succès !');
    }
    public function processPayment(Request $request)
    {
        // Récupérer les détails de la commande
        $commande = Commande::find($request->input('commande_id'));
        Log::info($commande);
        // Valider les entrées du formulaire
        $request->validate([
            'montant_paye' => 'required|numeric',
            'payer_par' => 'required'
        ]);

        // Récupérer les informations du paiement
        $montantPaye = $request->input('montant_paye');
        $netAPayer = $commande->Net_a_payer;
        $montantDette = max($netAPayer - $montantPaye, 0);  // Empêcher un montant négatif pour la dette
        $payerPar = $request->input('payer_par');
        Log::info($commande->id);
        // Boucle sur chaque type de vente pour créer les opérations correspondantes
        foreach (json_decode($commande->type_vente) as $typeVente) {
            if ($typeVente=="produits") {
                foreach (explode(',', $commande->produit) as $produit) {
                    [$produitId, $quantite, $prixUnitaire, $montantTotal] = explode('*', $produit);
                    Log::info($commande->id);
                    Log::info("je suis un produit");
                    Operation::create([
                        'commande_id' => $commande->id,
                        'typevente' => 'vente-autre',
                        'payer_par' => $payerPar,
                        'NomPrenomClient' => $commande->NomPrenomClient,
                        'client' => $commande->client,
                        'Montant_facture' => $montantTotal,
                        'Totalvente' => $montantTotal,
                        'montant_payé' => min($montantPaye, $montantTotal),
                        'montantDette' => null,
                        'totalRemise' => 0,
                        'typeOperation' => 'vente',
                        'AutresInfos' => $commande->produit,
                        'Payer_par'=>$payerPar,
                        'TotalRavitaillement' => $montantTotal,
                        'Date_op' => $commande->date,
                    ]);
                }

            }

            if ($typeVente=="oeufs"){
                foreach (explode(',', $commande->oeufs) as $oeuf) {
                    [$categorieId, $quantite, $montantTotal] = explode('*', $oeuf);
                    Log::info("je suis un oeuf");
                    Operation::create([
                        'commande_id' => $commande->id,
                        'typevente' => 'vente-oeuf',
                        'payer_par' => $payerPar,
                        'NomPrenomClient' => $commande->NomPrenomClient,
                        'client' => $commande->client,
                        'Montant_facture' => $montantTotal,
                        'Totalvente' => $montantTotal,
                        'montant_payé' => min($montantPaye, $montantTotal),
                        'montantDette' => null,
                        'totalRemise' => 0,
                        'typeOperation' => 'vente',
                        'infosOeuf' => $commande->oeufs,
                        'Payer_par'=>$payerPar,
                        'TotalRavitaillement' => $montantTotal,
                        'Date_op' => $commande->date,
                    ]);
                }
            }

            if ($typeVente=="bandes")  {
                foreach (explode(',', $commande->poulets) as $poulet) {
                    [$bandeId, $quantite, $prixUnitaire, $montantTotal] = explode('*', $poulet);
                    Log::info("je suis une bande");
                    Operation::create([
                        'commande_id' => $commande->id,
                        'typevente' => 'vente-sujet',
                        'payer_par' => $payerPar,
                        'NomPrenomClient' => $commande->NomPrenomClient,
                        'client' => $commande->client,
                        'Montant_facture' => $montantTotal,
                        'Totalvente' => $montantTotal,
                        'montant_payé' => min($montantPaye, $montantTotal),
                        'montantDette' => null,
                        'totalRemise' => 0,
                        'typeOperation' => 'vente',
                        'sujetInfos' => $commande->poulets,
                        'Payer_par'=>$payerPar,
                        'TotalRavitaillement' => $montantTotal,
                        'Date_op' => $commande->date,
                    ]);
                }

            }
        }

        // Mise à jour de la commande
        $commande->Montant_paye = $request->input('montant_paye');
        $commande->MontantDette = $montantDette;
        $commande->payer_par = $payerPar;
        $commande->etat=2;
        $commande->save();

        // Mise à jour du compte
        $compte = Compte::find($request->payer_par);
        $compte->update(['solde_actuel' => $compte->solde_actuel + $request->input('montant_paye')]);

        // Redirection avec un message de succès
        session()->flash('generate_pdf', 'true');
        return redirect()->route('detailscommande', ['id' => $commande->id])
        ->with('success', 'Paiement enregistré avec succès !');

    }

    // Afficher les détails d'une commande spécifique
    public function show(Commande $commande)
    {
        return view('commandes.show', compact('commande'));
    }

    // Afficher le formulaire d'édition d'une commande
    public function edit(Commande $commande)
    {
        return view('commandes.edit', compact('commande'));
    }

    // Mettre à jour une commande
    public function update(Request $request, Commande $commande)
    {
        $validatedData = $request->validate([
            'type_vente' => 'required|string|max:255',
            'date' => 'required|date',
            'NomPrenomClient' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'produit' => 'nullable|string',
            'oeufs' => 'nullable|integer',
            'poulets' => 'nullable|integer',
            'TotalVente' => 'required|numeric',
            'TotalRemise' => 'nullable|numeric',
            'Net_a_payer' => 'required|numeric',
            'etat' => 'required|string|max:50',
        ]);

        $commande->update($validatedData);

        return redirect()->route('commandes.index')->with('success', 'Commande mise à jour avec succès.');
    }

    // Supprimer une commande
    public function destroy(Commande $commande)
    {
        $commande->delete();
        return redirect()->route('commandes.index')->with('success', 'Commande supprimée avec succès.');
    }
}
