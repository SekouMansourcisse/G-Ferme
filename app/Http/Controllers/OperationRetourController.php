<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\CategorieOeuf;
use App\Models\Commande;
use App\Models\Compte;
use App\Models\Operation;
use App\Models\OperationRetour;
use App\Models\Parametre;
use App\Models\Produit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class OperationRetourController extends Controller
{
    public function index()
    {
        $operationsRetour = OperationRetour::all();
        return view('RetourVente.list', compact('operationsRetour'));
    }

    public function exportPdf()
    {
        $operationsRetour = OperationRetour::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('RetourVente.pdf', compact('operationsRetour','settings'));

        return $pdf->download('liste_retour_ventes.pdf');
    }

    public function create()
    {
        $comptes=Compte::all();
        $operations = Operation::all();
        return view('RetourVente.add',compact('comptes','operations'));
    }

    public function store(Request $request)
    {
        // Validation des données du formulaire
        /*$validated = $request->validate([
            'operation_id' => 'required|exists:operation,id',
            'type_operation' => 'required|array',
            'type_operation.*' => 'string',
            'qte_retour' => 'required|array',
            'qte_retour.*' => 'numeric|min:0', // Assurez-vous que les quantités sont des nombres non négatifs
            'montant_total' => 'required|array',
            'montant_total.*' => 'numeric|min:0',
            'payer_par' => 'required|exists:compte,id',
            'date' => 'required|date',
            'typeVente' => 'required|array',
            'typeVente.*' => 'string',
        ]);*/

        // Récupération des données du formulaire
        $operationId = $request->input('operationId');
        $commandeId = $request->input('operation_id');
        $typeOperation = $request->input('type_operation');
        $qteRetour = $request->input('qte_retour');
        $montantRetour = $request->input('montant_total');
        $payerPar = $request->input('payer_par');
        $date = $request->input('date');
        $typeVente = $request->input('typeVente');

        // Création de l'enregistrement dans la table OperationRetour
        foreach ($qteRetour as $elementId => $qte) {
            if ($qte > 0) { // Ignorer les quantités nulles ou négatives
                $qteRetourFormatted = "{$elementId}*{$qte}";
                $typeV = $typeVente[$elementId];

                OperationRetour::create([
                    'date_op' => $date,
                    'TypeVenteR' => $typeV,
                    'numero_vente' => $operationId[$elementId],
                    'qteR' => $qteRetourFormatted,
                    'Montant_R' => $montantRetour[$elementId],
                    'TotalR' => $montantRetour[$elementId],
                    'payer_par' => $payerPar,
                    'TypeRetour' => $typeOperation[$elementId],
                    'commande_id' => $commandeId,
                    'etat'=>1
                ]);
                if ($typeOperation[$elementId] =="Remboursement") {
                    // Mise à jour de l'enregistrement dans la table Operation
                    $operation = Operation::find($operationId[$elementId]);
                    if ($operation) {
                        $infos = [];
                        switch ($typeV) {
                            case 'vente-oeuf':
                                $infoOeuf = explode(';', $operation->infosOeuf);
                                foreach ($infoOeuf as $info) {
                                    list($categorieId, $qteVendu) = explode('*', $info);
                                    $qteRetournee = $qteRetour[$categorieId] ?? 0; // Éviter les erreurs si l'ID n'existe pas
                                    $newQteVendu = $qteVendu - $qteRetournee;
                                    $categorie = CategorieOeuf::find($categorieId);
                                    if ($categorie) {
                                        //$categorie->qteEnplateaux += $qteRetournee;
                                        //$categorie->ValeurFinancier = $categorie->qteEnplateaux * $categorie->PrixPlateaux;
                                        if ($newQteVendu > 0) {
                                            $infos[] = $categorieId . '*' . $newQteVendu;
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'Catégorie d\'œuf non trouvée.');
                                    }
                                }
                                $operation->infosOeuf = implode(';', $infos);
                                break;

                            case 'vente-sujet':
                                $sujetInfos = explode(';', $operation->sujetInfos);
                                foreach ($sujetInfos as $info) {
                                    list($bandeId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                                    $qteRetournee = $qteRetour[$bandeId] ?? 0;
                                    $newQteVendu = $qteVendu - $qteRetournee;
                                    $bande = Bande::find($bandeId);
                                    if ($bande) {
                                        //$bande->cheptel_actuel += $qteRetournee;
                                        if ($newQteVendu > 0) {
                                            $infos[] = $bandeId . '*' . $newQteVendu . '*' . $prixUnitaire . '*' . ($newQteVendu * $prixUnitaire);
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'Bande non trouvée.');
                                    }
                                }
                                $operation->sujetInfos = implode(';', $infos);
                                break;

                            case 'vente-autre':
                                $autresInfos = explode(';', $operation->AutresInfos);
                                foreach ($autresInfos as $info) {
                                    list($produitId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                                    $qteRetournee = $qteRetour[$produitId] ?? 0;
                                    $produit = Produit::find($produitId);
                                    if ($produit) {
                                        //$produit->qte_stock += $qteRetournee;
                                        $newQteVendu = $qteVendu - $qteRetournee;
                                        if ($newQteVendu > 0) {
                                            $infos[] = $produitId . '*' . $newQteVendu . '*' . $prixUnitaire . '*' . ($newQteVendu * $prixUnitaire);
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'Produit non trouvé.');
                                    }
                                }
                                $operation->AutresInfos = implode(';', $infos);
                                break;
                        }

                        // Mise à jour des champs pertinents
                        $operation->Totalvente -= $montantRetour[$elementId];
                        $operation->save();
                    } else {
                        return redirect()->back()->with('error', 'Opération non trouvée.');
                    }
                }

            }
        }

        // Mise à jour du solde du compte (à décommenter si nécessaire)
        /*
        $compte = Compte::find($payerPar);
        if ($compte) {
            $compte->update(['solde_actuel' => $compte->solde_actuel - array_sum($montantRetour)]);
        } else {
            return redirect()->back()->with('error', 'Compte non trouvé.');
        }
        */

        return redirect()->route('CommandeRetourVente')->with('success', 'Opération enregistrée avec succès.');
    }

    public function store2(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'operation_id' => 'required|exists:operation,id',
            'type_operation' => 'required|string',
            'qte_retour' => 'required|array',
            'qte_retour.*' => 'numeric|min:0', // Assurez-vous que les quantités sont des nombres non négatifs
            'montant_retour' => 'required|numeric|min:0',
            'payer_par' => 'required|exists:compte,id',
            'date' => 'required|date',
        ]);

        // Récupération des données du formulaire
        $operationId = $request->input('operation_id');
        $typeOperation = $request->input('type_operation');
        $qteRetour = $request->input('qte_retour');
        $montantRetour = $request->input('montant_retour');
        $payerPar = $request->input('payer_par');
        $date = $request->input('date');

        // Création de l'enregistrement dans la table OperationRetour
        $qteRetourFormatted = [];
        foreach ($qteRetour as $elementId => $qte) {
            if ($qte > 0) { // Ignorer les quantités nulles ou négatives
                $qteRetourFormatted[] = "{$elementId}*{$qte}";
            }
        }

        OperationRetour::create([
            'date_op' => $date,
            'TypeVenteR' => $typeOperation,
            'numero_vente' => $operationId,
            'qteR' => implode(';', $qteRetourFormatted),
            'Montant_R' => $montantRetour,
            'TotalR' => $montantRetour,
            'payer_par' => $payerPar,
        ]);

        // Mise à jour du solde du compte
        $compte = Compte::find($payerPar);
        if ($compte) {
            $compte->update(['solde_actuel' => $compte->solde_actuel - $montantRetour]);
        } else {
            return redirect()->back()->with('error', 'Compte non trouvé.');
        }

        // Mise à jour de l'enregistrement dans la table Operation
        $operation = Operation::find($operationId);
        if ($operation) {
            $infos = [];
            switch ($typeOperation) {
                case 'vente-oeuf':
                    $infoOeuf = explode(';', $operation->infosOeuf);
                    foreach ($infoOeuf as $info) {
                        list($categorieId, $qteVendu) = explode('*', $info);
                        $qteRetournee = $qteRetour[$categorieId];
                        $newQteVendu = $qteVendu - $qteRetournee;
                        $categorie = CategorieOeuf::find($categorieId);
                        if ($categorie) {
                            $categorie->qteEnplateaux += $qteRetournee;
                            $categorie->ValeurFinancier = $categorie->qteEnplateaux * $categorie->PrixPlateaux;
                            if ($newQteVendu > 0) {
                                $infos[] = $categorieId . '*' . $newQteVendu;
                            }
                        } else {
                            return redirect()->back()->with('error', 'Catégorie d\'oeuf non trouvée.');
                        }
                    }
                    $operation->infosOeuf = implode(';', $infos);
                    break;

                case 'vente-sujet':
                    $sujetInfos = explode(';', $operation->sujetInfos);
                    foreach ($sujetInfos as $info) {
                        list($bandeId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                        $qteRetournee = $qteRetour[$bandeId];
                        $newQteVendu = $qteVendu - $qteRetournee;
                        $bande = Bande::find($bandeId);
                        if ($bande) {
                            $bande->cheptel_actuel += $qteRetournee;
                            if ($newQteVendu > 0) {
                                $infos[] = $bandeId . '*' . $newQteVendu . '*' . $prixUnitaire . '*' . ($newQteVendu * $prixUnitaire);
                            }
                        } else {
                            return redirect()->back()->with('error', 'Bande non trouvée.');
                        }
                    }
                    $operation->sujetInfos = implode(';', $infos);
                    break;

                case 'vente-autre':
                    $autresInfos = explode(';', $operation->AutresInfos);
                    foreach ($autresInfos as $info) {
                        list($produitId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                        $qteRetournee = $qteRetour[$produitId];
                        $produit = Produit::find($produitId);
                        if ($produit) {
                            $produit->qte_stock= $produit->qte_stock + $qteRetournee;
                            $newQteVendu = $qteVendu - $qteRetournee;
                            if ($newQteVendu > 0) {
                                $infos[] = $produitId . '*' . $newQteVendu . '*' . $prixUnitaire . '*' . ($newQteVendu * $prixUnitaire);
                            }
                        } else {
                            return redirect()->back()->with('error', 'Produit non trouvé.');
                        }
                    }
                    $operation->AutresInfos = implode(';', $infos);
                    break;
            }

            // Mise à jour des champs pertinents
            $operation->Totalvente -= $montantRetour;
            $operation->save();
        } else {
            return redirect()->back()->with('error', 'Opération non trouvée.');
        }

        return redirect()->route('operation-retours.index')->with('success', 'Opération enregistrée avec succès.');
    }
    public function update(Request $request, $id)
{
    // Validation des données du formulaire
    $validated = $request->validate([
        'operation_id' => 'required|exists:operation,id',
        'type_operation' => 'required|string',
        'qte_retour' => 'required|array',
        'qte_retour.*' => 'numeric|min:0', // Assurez-vous que les quantités sont des nombres non négatifs
        'montant_retour' => 'required|numeric|min:0',
        'payer_par' => 'required|exists:compte,id',
        'date' => 'required|date',
    ]);

    // Récupération des données du formulaire
    $operationRetour = OperationRetour::findOrFail($id);
    $operationId = $request->input('operation_id');
    $typeOperation = $request->input('type_operation');
    $qteRetour = $request->input('qte_retour');
    $montantRetour = $request->input('montant_retour');
    $payerPar = $request->input('payer_par');
    $date = $request->input('date');

    // Création de l'enregistrement dans la table OperationRetour
    $qteRetourFormatted = [];
    foreach ($qteRetour as $elementId => $qte) {
        if ($qte > 0) { // Ignorer les quantités nulles ou négatives
            $qteRetourFormatted[] = "{$elementId}*{$qte}";
        }
    }

    $operationRetour->update([
        'date_op' => $date,
        'TypeVenteR' => $typeOperation,
        'numero_vente' => $operationId,
        'qteR' => implode(';', $qteRetourFormatted),
        'Montant_R' => $montantRetour,
        'TotalR' => $montantRetour,
        'payer_par' => $payerPar,
    ]);

    // Mise à jour du solde du compte
    $compte = Compte::find($payerPar);
    if ($compte) {
        $compte->update(['solde_actuel' => $compte->solde_actuel - $montantRetour]);
    } else {
        return redirect()->back()->with('error', 'Compte non trouvé.');
    }

    // Mise à jour de l'enregistrement dans la table Operation
    $operation = Operation::find($operationId);
    if ($operation) {
        $infos = [];
        switch ($typeOperation) {
            case 'vente-oeuf':
                $infoOeuf = explode(';', $operation->infosOeuf);
                foreach ($infoOeuf as $info) {
                    list($categorieId, $qteVendu) = explode('*', $info);
                    $qteRetournee = $qteRetour[$categorieId] ?? 0; // Utiliser 0 si non défini
                    $newQteVendu = $qteVendu - $qteRetournee;
                    $categorie = CategorieOeuf::find($categorieId);
                    if ($categorie) {
                        $categorie->qteEnplateaux += $qteRetournee;
                        $categorie->ValeurFinancier = $categorie->qteEnplateaux * $categorie->PrixPlateaux;
                        if ($newQteVendu > 0) {
                            $infos[] = $categorieId . '*' . $newQteVendu;
                        }
                    }
                }
                $operation->infosOeuf = implode(';', $infos);
                break;

            case 'vente-sujet':
                $sujetInfos = explode(';', $operation->sujetInfos);
                foreach ($sujetInfos as $info) {
                    list($bandeId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                    $qteRetournee = $qteRetour[$bandeId] ?? 0; // Utiliser 0 si non défini
                    $newQteVendu = $qteVendu - $qteRetournee;
                    $bande = Bande::find($bandeId);
                    if ($bande) {
                        $bande->cheptel_actuel += $qteRetournee;
                        if ($newQteVendu > 0) {
                            $infos[] = $bandeId . '*' . $newQteVendu . '*' . $prixUnitaire . '*' . ($newQteVendu * $prixUnitaire);
                        }
                    }
                }
                $operation->sujetInfos = implode(';', $infos);
                break;

            case 'vente-autre':
                $autresInfos = explode(';', $operation->AutresInfos);
                foreach ($autresInfos as $info) {
                    list($produitId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                    $qteRetournee = $qteRetour[$produitId] ?? 0; // Utiliser 0 si non défini
                    $produit = Produit::find($produitId);
                    if ($produit) {
                        $produit->qte_stock += $qteRetournee;
                        $newQteVendu = $qteVendu - $qteRetournee;
                        if ($newQteVendu > 0) {
                            $infos[] = $produitId . '*' . $newQteVendu . '*' . $prixUnitaire . '*' . ($newQteVendu * $prixUnitaire);
                        }
                    }
                }
                $operation->AutresInfos = implode(';', $infos);
                break;
        }


        // Mise à jour des champs pertinents
        $operation->Totalvente -= $montantRetour;
        $operation->save();
    } else {
        return redirect()->back()->with('error', 'Opération non trouvée.');
    }

    return redirect()->route('operation-retours.index')->with('success', 'Opération mise à jour avec succès.');
}

    public function fetchOperations(Request $request)
    {
        $type = $request->input('type');
        $operations = Operation::where('typevente', $type)->get();
        return response()->json(['operations' => $operations]);
    }
    public function fetchOperations1(Request $request)
    {
        $type = $request->input('type');
        $operations = Operation::where('typevente', $type)->get();
        return response()->json(['operations' => $operations]);
    }
    public function fetchOperationDetails(Request $request)
    {
        $commandeId = $request->input('commande_id');
        $commande =Commande::findOrFail($commandeId);
        $montant_paye = $commande->Montant_paye;
        $montant_dette = $commande->MontantDette;
        // Récupérer toutes les opérations avec le même commande_id
        $operations = Operation::where('commande_id', $commandeId)
            ->whereNotNull('typevente')
            ->get();

        $details = [];

        // Parcourir chaque opération pour récupérer les détails en fonction du type de vente
        foreach ($operations as $operation) {
            switch ($operation->typevente) {
                case 'vente-oeuf':
                    $infoOeuf = explode(';', $operation->infosOeuf);
                    foreach ($infoOeuf as $info) {
                        list($categorieId, $qteVendu) = explode('*', $info);
                        $categorie = CategorieOeuf::find($categorieId);
                        $details[] = [
                            'libelle' => $categorie->Denomination,
                            'qte_vendu' => $qteVendu,
                            'prix_unitaire' => $categorie->PrixPlateaux,
                            'type_vente' => 'vente-oeuf',
                            'id' => $categorieId,
                            'operationId'=> $operation->id
                        ];
                    }
                    break;

                case 'vente-sujet':
                    $sujetInfos = explode(';', $operation->sujetInfos);
                    foreach ($sujetInfos as $info) {
                        list($bandeId, $qteVendu, $prixUnitaire, $montantTotal) = explode('*', $info);
                        $bande = Bande::find($bandeId);
                        $details[] = [
                            'libelle' =>'Poulet('. $bande->nom_bande .')',
                            'qte_vendu' => $qteVendu,
                            'prix_unitaire' => $prixUnitaire,
                            'type_vente' => 'vente-sujet',
                            'id' => $bandeId,
                            'operationId'=> $operation->id
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
                            'type_vente' => 'vente-autre',
                            'id' => $produitId,
                            'operationId'=> $operation->id
                        ];
                    }
                    break;
            }
        }

            // Retourner les détails avec les montants
        return response()->json([
            'details' => $details,
            'montant_paye' => $montant_paye,
            'montant_dette' => $montant_dette
        ]);
    }


    public function fetchOperationDetails1(Request $request)
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

    public function edit($id)
    {
        // Récupérer l'opération de retour
        $operationRetour = OperationRetour::findOrFail($id);

        // Récupérer l'opération correspondante
        $operation = Operation::findOrFail($operationRetour->numero_vente);

        // Initialiser un tableau pour stocker les détails
        $details = [];
        $typeOperation = $operationRetour->TypeVenteR;

        // Récupérer les informations en fonction du type d'opération
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
                        'id' => $categorieId,
                    ];
                }
                break;

            case 'vente-sujet':
                $sujetInfos = explode(';', $operation->sujetInfos);
                foreach ($sujetInfos as $info) {
                    list($bandeId, $qteVendu, $prixUnitaire) = explode('*', $info);
                    $bande = Bande::find($bandeId);
                    $details[] = [
                        'libelle' => $bande->nom_bande,
                        'qte_vendu' => $qteVendu,
                        'prix_unitaire' => $prixUnitaire,
                        'id' => $bandeId,
                    ];
                }
                break;

            case 'vente-autre':
                $autresInfos = explode(';', $operation->AutresInfos);
                foreach ($autresInfos as $info) {
                    list($produitId, $qteVendu, $prixUnitaire) = explode('*', $info);
                    $produit = Produit::find($produitId);
                    $details[] = [
                        'libelle' => $produit->Denomination,
                        'qte_vendu' => $qteVendu,
                        'prix_unitaire' => $prixUnitaire,
                        'id' => $produitId,
                    ];
                }
                break;
        }

        // Récupérer tous les comptes disponibles
        $comptes = Compte::all();
        $operations= Operation::all();
        // Passer les données à la vue
        return view('RetourVente.edit', [
            'operationRetour' => $operationRetour,
            'details' => $details,
            'comptes' => $comptes,
            'operations' =>$operations
        ]);
    }

    public function destroy($id)
    {
        $vente = OperationRetour::findOrFail($id);
        $vente->delete();

        return response()->json(['success' => true, 'message' => 'Vente supprimée avec succès.']);
    }
}

