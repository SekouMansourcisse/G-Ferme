<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Fournisseur;
use App\Models\Compte;
use App\Models\Operation;
use App\Models\Ravitaillement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use App\Exports\RavitaillementExport;
use Maatwebsite\Excel\Facades\Excel;
class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Afficher tous les produits
        $produits = Produit::all();
        return view('produit.list', compact('produits'));
    }

    public function exportExcel()
{
    return Excel::download(new RavitaillementExport, 'ravitaillements.xlsx');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('produit.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::error('Toutes les données: ' . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'reference_produit' => 'required|string|max:255',
            'Denomination' => 'required|string|max:255',
            'qte_stock' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'stock_seuil' => 'nullable|numeric|min:0',
            'infos_supp' => 'nullable|string',
            'type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $produit = new Produit();
            $produit->reference_produit = $request->input('reference_produit');
            $produit->Denomination = $request->input('Denomination');
            $produit->qte_stock = $request->input('qte_stock');
            $produit->prix_unitaire = $request->input('prix_unitaire');
            $produit->stock_seuil = $request->input('stock_seuil');
            $produit->infos_supp = $request->input('infos_supp');
            $produit->typeProduit = $request->input('type');

            $produit->save();

            return response()->json(['success' => true, 'produit' => $produit], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'ajout du produit: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function exportPdf()
    {
        $produits = Produit::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('produit.pdf', compact('produits','settings'));

        return $pdf->download('liste_produits.pdf');
    }


    public function exportRavitaillementPDF()
    {
        $ravitaillements = Operation::where('typeOperation', 'ravitaillement')->get();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('produit.pdfRavi', compact('ravitaillements', 'settings'));

        return $pdf->download('liste_ravitaillement.pdf');
    }


    public function RavitailleProduit()
    {
        $produits = Produit::all();
        $ravitaillements = Operation::where('typeOperation', 'ravitaillement')->get();
        $fournisseur = Fournisseur::all();
        $compte = Compte::all();
        return view('produit.ravitaillement', ['produits' => $produits, 'ravitaillements' => $ravitaillements, 'fournisseurs' => $fournisseur, 'comptes' => $compte]);
    }

    public function AddViewRavi()
    {
        $produits = Produit::all();
        $ravitaillements = Operation::all();
        $fournisseur = Fournisseur::all();
        $compte = Compte::all();
        return view('produit.add_new_ravi', ['produits' => $produits, 'ravitaillements' => $ravitaillements, 'fournisseurs' => $fournisseur, 'comptes' => $compte]);
    }
    public function enregistrerRavitaillement(Request $request)
    {

        try {
            // Préparer les données des produits dans le format spécifié
            $produits = [];
            foreach ($request->produit_id as $index => $produit_id) {
                $qte = $request->qte_produit[$index];
                $prix_unitaire = $request->prix_unitaire_p[$index];
                $prix_total = $request->prix_total_p[$index];

                // Mettre à jour les détails du produit
                $produit = Produit::find($produit_id);
                $produit->update([
                    'qte_stock' => $produit->qte_stock + $qte,
                    'prix_unitaire' => $prix_unitaire
                ]);

                $produits[] = "$produit_id*$qte*$prix_unitaire*$prix_total";
            }

            // Convertir le tableau en une chaîne séparée par des virgules
            $produitsString = implode(',', $produits);
            $fournisseur_nom = Fournisseur::find($request->fournisseur_id)->nom;

            // Créer l'opération de ravitaillement
            $ravitaillement = Operation::create([
                'NomPrenomFournisseur' => $fournisseur_nom,
                'Fournisseur' => $request->fournisseur_id,
                'Produit' => $produitsString,
                'TotalRavitaillement' => $request->total_ravitaillement,
                'Montant_facture' => $request->net_payer,
                'totalRemise' => $request->total_remise,
                'montant_payé' => $request->montant_paye,
                'montantDette' => $request->dette_a_paye,
                'typeOperation' => 'ravitaillement',
                'Payer_par' => $request->payer_par,
                'Date_op' => $request->date

            ]);

            // Mettre à jour le compte
            $compte = Compte::where('id', $request->payer_par)->first();
            $compte->update(['solde_actuel' => $compte->solde_actuel - $request->montant_paye]);

            return response()->json(['success' => true, 'ravitaillement' => $ravitaillement], 201);
        } catch (Exception $e) {
            \Log::error('Erreur lors de l\'ajout du ravitaillement: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function supprimerRavitaillement($id)
    {
        try {
            // Rechercher le ravitaillement à supprimer
            $ravitaillement = Operation::findOrFail($id);

            // Récupérer les informations du produit pour ajuster le stock
            $produits = explode(',', $ravitaillement->Produit);
            foreach ($produits as $produit) {
                list($produit_id, $qte, $prix_unitaire, $prix_total) = explode('*', $produit);

                $produitModel = Produit::find($produit_id);
                if ($produitModel) {
                    // Mettre à jour le stock en soustrayant la quantité du ravitaillement
                    $produitModel->update(['qte_stock' => $produitModel->qte_stock - $qte]);
                }
            }

            // Mettre à jour le solde du compte
            $compte = Compte::where('id', $ravitaillement->Payer_par)->first();
            if ($compte) {
                $compte->update(['solde_actuel' => $compte->solde_actuel + $ravitaillement->montant_payé]);
            }

            // Supprimer le ravitaillement
            $ravitaillement->delete();

            return response()->json(['success' => true, 'message' => 'Ravitaillement supprimé avec succès.']);
        } catch (Exception $e) {
            \Log::error('Erreur lors de la suppression du ravitaillement: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du ravitaillement.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Récupérer l'opération existante
            $ravitaillement = Operation::findOrFail($id);

            // Récupérer les anciens produits et montants
            $oldProduitsString = $ravitaillement->Produit;
            $oldMontantPaye = $ravitaillement->montant_payé;

            // Annuler les anciens changements dans les produits et compte
            $this->annulerAnciennesValeurs($oldProduitsString, $oldMontantPaye, $ravitaillement->Payer_par);
            $fournisseur_nom = Fournisseur::find($request->fournisseur_id)->nom;
            // Mettre à jour les champs de l'opération
            $ravitaillement->update([
                'Date_op' => $request->date,
                'NomPrenomFournisseur' => $fournisseur_nom,
                'Fournisseur' => $request->fournisseur_id,
                'TotalRavitaillement' => $request->total_ravitaillement,
                'Montant_facture' => $request->net_payer,
                'totalRemise' => $request->total_remise,
                'montant_payé' => $request->montant_paye,
                'montantDette' => $request->dette_a_paye,
                'Payer_par' => $request->payer_par
            ]);

            // Mettre à jour les produits
            $produits = [];
            foreach ($request->produit_id as $index => $produit_id) {
                $qte = $request->qte_produit[$index];
                $prix_unitaire = $request->prix_unitaire_p[$index];
                $prix_total = $request->prix_total_p[$index];

                // Mettre à jour les détails du produit
                $produit = Produit::find($produit_id);
                $produit->update([
                    'qte_stock' => $produit->qte_stock + $qte,
                    'prix_unitaire' => $prix_unitaire
                ]);

                $produits[] = "$produit_id*$qte*$prix_unitaire*$prix_total";
            }

            // Convertir le tableau en une chaîne séparée par des virgules
            $produitsString = implode(',', $produits);
            $ravitaillement->update(['Produit' => $produitsString]);

            // Mettre à jour le compte
            $compte = Compte::where('id', $request->payer_par)->first();
            $compte->update(['solde_actuel' => $compte->solde_actuel - $request->montant_paye]);

            return response()->json(['success' => true, 'ravitaillement' => $ravitaillement]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du ravitaillement: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function annulerAnciennesValeurs($produitsString, $oldMontantPaye, $payerPar)
    {
        $produitArray = explode(',', $produitsString);

        foreach ($produitArray as $produit) {
            list($id, $quantite, $prixUnitaire, $prixTotal) = explode('*', $produit);

            // Mettre à jour les détails du produit
            $produit = Produit::find($id);
            $produit->update([
                'qte_stock' => $produit->qte_stock - $quantite,
                'prix_unitaire' => $prixUnitaire
            ]);
        }

        // Mettre à jour le compte
        $compte = Compte::where('id', $payerPar)->first();
        $compte->update(['solde_actuel' => $compte->solde_actuel + $oldMontantPaye]);
    }


    public function show($id)
    {
        $ravitaillement = Operation::with('fournisseur', 'compte')->findOrFail($id);
        $produits = $this->parseProduits($ravitaillement->Produit);

        return response()->json([
            'success' => true,
            'ravitaillement' => $ravitaillement,
            'produits' => $produits
        ]);
    }

    private function parseProduits($produitString)
    {
        $produits = [];
        $produitArray = explode(',', $produitString);

        foreach ($produitArray as $produit) {
            list($id, $quantite, $prixUnitaire, $prixTotal) = explode('*', $produit);
            $nom = Produit::find($id)->Denomination;
            $produits[] = [
                'Denomination' => $nom,
                'quantite' => $quantite,
                'prix_unitaire' => $prixUnitaire,
                'prix_total' => $prixTotal,
            ];
        }

        return $produits;
    }

    public function destroyOperation($id)
    {
        try {
            // Récupérer le ravitaillement et les détails des produits associés
            $ravitaillement = Operation::findOrFail($id);
            $produits = $this->parseProduits($ravitaillement->Produit);

            // Annuler les changements dans la table produits
            foreach ($produits as $produit) {
                $produitModel = Produit::find($produit['id']);
                $produitModel->qte_stock -= $produit['quantite'];
                $produitModel->save();
            }

            // Annuler les changements dans la table compte
            $compte = Compte::where('payer_par', $ravitaillement->Payer_par)->first();
            $compte->solde_actuelle += $ravitaillement->montant_payé;
            $compte->save();

            // Supprimer le ravitaillement
            $ravitaillement->delete();

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du ravitaillement: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Fonction pour supprimer un produit

    public function updateProduit(Request $request, $id)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'reference_produit' => 'required|string|max:255', // Changement ici
            'Denomination' => 'required|string|max:255', // Changement ici
            'qte_stock' => 'required|numeric', // Changement ici
            'stock_seuil' => 'required|numeric', // Changement ici
            'prix_unitaire' => 'required|numeric', // Changement ici
            'infos_supp' => 'nullable|string|max:255', // Changement ici
        ]);

        // Recherche et mise à jour du produit
        $ravitaillement = Produit::findOrFail($id);
        $ravitaillement->update($validatedData);

        return response()->json(['success' => true]);
    }


    public function destroy($id)
    {
        \Log::info('Tentative de suppression du produit avec ID: ' . $id);
        try {
            // Récupérer le produit à supprimer
            $produit = Produit::findOrFail($id);
            // Supprimer le produit
            $produit->delete();
            return response()->json(['success' => true, 'message' => 'Produit supprimé avec succès']);
        } catch (Exception $e) {
            \Log::error('Erreur lors de la suppression: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du produit', 'error' => $e->getMessage()]);
        }
    }


}
