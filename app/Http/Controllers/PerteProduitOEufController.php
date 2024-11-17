<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use App\Models\PerteProduitOEuf;
use App\Models\Produit;
use Illuminate\Http\Request;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
class PerteProduitOEufController extends Controller
{
    public function index()
    {
        $pertes = PerteProduitOEuf::all();
        return view('PerteOeufProduit.perteProduit', compact('pertes'));
    }
    public function exportPdf()
    {
        $pertes = PerteProduitOEuf::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('PerteOeufProduit.pdfProduit', compact('pertes','settings'));

        return $pdf->download('liste_perteProduit.pdf');
    }
    public function create()
    {
        $produits=Produit::all();
        return view('PerteOeufProduit.add_perteProduit',compact('produits'));
    }

    public function store(Request $request)
    {
        // Validation des données (ajoutez ici les règles de validation si nécessaire)

        try {
            // Créer l'enregistrement de la perte de produit ou d'œuf
            $perteProduitOeuf = new PerteProduitOeuf();
            $perteProduitOeuf->date_p = $request->input('Date');
            $perteProduitOeuf->description = $request->input('Resume');
            $perteProduitOeuf->type_perte = $request->input('type_perte');

            // Traitement des données pour Produit
            $qtePerdu = $request->input('qte_perdu');
            $produitStrings = [];
            foreach ($qtePerdu as $id => $qte) {
                $produitStrings[] = "$id*$qte";
            }
            $perteProduitOeuf->produit = implode(',', $produitStrings);

            $perteProduitOeuf->save();

            // Mise à jour des quantités des produits
            foreach ($qtePerdu as $produit_id => $qtePerdu1) {
                $produit = Produit::find($produit_id);
                if ($produit) {
                    $produit->qte_stock -= $qtePerdu1; // Réduire la quantité perdue
                    $produit->save();
                }
            }

            return redirect()->route('perte-produit-o-eufs.index')->with('success', 'Perte enregistrée avec succès.');
        } catch (Exception $e) {
            \Log::error('Erreur lors de l\'ajout de la perte: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function show(PerteProduitOEuf $perte)
    {
        return view('pertes.show', compact('perte'));
    }

    public function edit($id)
    {
        try {
            $perte = PerteProduitOeuf::findOrFail($id);

            // Transformez les produits en un format lisible (e.g., id*qte format)
            $produits = [];
            foreach (explode(',', $perte->produit) as $produit) {
                list($produit_id, $qte) = explode('*', $produit);
                $produitNom = Produit::find($produit_id)->Denomination; // Assurez-vous d'utiliser le bon attribut
                $produits[] = ['id' => $produit_id, 'nom' => $produitNom, 'qte' => $qte];
            }

            return response()->json([
                'date_p' => $perte->date_p,
                'description' => $perte->description,
                'produits' => $produits,
            ]);
        } catch (Exception $e) {
            \Log::error('Erreur lors de la récupération de la perte de produit: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        // Validation des données (ajoutez ici les règles de validation si nécessaire)

        try {
            // Trouver l'enregistrement de la perte à mettre à jour
            $perteProduitOeuf = PerteProduitOeuf::findOrFail($id);

            // Mettre à jour les informations de la perte
            $perteProduitOeuf->date_p = $request->input('date');
            $perteProduitOeuf->description = $request->input('description');
            $perteProduitOeuf->type_perte = $request->input('type_perte');

            // Traitement des données pour Produit
            $qtePerdu = $request->input('qte_perdu');
            $produitStrings = [];
            foreach ($qtePerdu as $produit_id => $qte) {
                $produitStrings[] = "$produit_id*$qte";
            }
            $perteProduitOeuf->produit = implode(',', $produitStrings);

            $perteProduitOeuf->save();

            // Mise à jour des quantités des produits
            foreach ($qtePerdu as $produit_id => $qtePerdu1) {
                $produit = Produit::find($produit_id);
                if ($produit) {
                    // Réajuster la quantité stockée, en fonction de votre logique métier
                    $produit->qte_stock -= $qtePerdu1;
                    $produit->save();
                }
            }

            return response()->json(['success' => true, 'message' => 'Perte mise à jour avec succès.']);
        } catch (Exception $e) {
            \Log::error('Erreur lors de la mise à jour de la perte: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $perte = PerteProduitOeuf::findOrFail($id);

            // Récupérer les quantités perdues pour les produits avant de supprimer
            $qtePerdu = explode(',', $perte->produit);
            foreach ($qtePerdu as $produit) {
                list($produit_id, $qte) = explode('*', $produit);
                $produitModel = Produit::find($produit_id);
                if ($produitModel) {
                    $produitModel->qte_stock += $qte; // Réajuster la quantité dans le stock
                    $produitModel->save();
                }
            }

            // Supprimer l'enregistrement de la perte
            $perte->delete();

            return response()->json(['success' => true, 'message' => 'Perte supprimée avec succès.']);
        } catch (Exception $e) {
            \Log::error('Erreur lors de la suppression de la perte: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}

