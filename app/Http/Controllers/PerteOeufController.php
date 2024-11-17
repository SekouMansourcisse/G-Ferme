<?php

namespace App\Http\Controllers;

use App\Models\CategorieOeuf;
use App\Models\Parametre;
use App\Models\PerteProduitOEuf;
use App\Models\Produit;
use Illuminate\Http\Request;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
class PerteOeufController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pertes = PerteProduitOEuf::all();
        return view('PerteOeufProduit.perteOeuf', compact('pertes'));
    }
    public function exportPdf()
    {
        $pertes = PerteProduitOEuf::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('PerteOeufProduit.pdf_perteOeuf', compact('pertes','settings'));

        return $pdf->download('liste_perteOeufs.pdf');
    }

    public function create()
    {

        $categories = CategorieOeuf::all();
        return view('PerteOeufProduit.add_perteOeuf',compact('categories'));
    }

    public function store(Request $request)
    {
        // Validation des données (ajoutez ici les règles de validation si nécessaire)

        try {
            // Créer l'enregistrement de la perte d'œufs
            $perte = new PerteProduitOeuf();
            $perte->date_p = $request->input('Date');
            $perte->description = $request->input('Resume');
            $perte->type_perte = $request->input('type_perte');

            //traitement perte oeuf
            $qtePerdu = $request->input('qte_perdu');
            $oeufStrings = [];
            foreach ($qtePerdu as $id => $qte) {
                $oeufStrings[] = "$id*$qte";
            }
            $perte->Oeuf = implode(',', $oeufStrings);

            $perte->save();

            // Mise à jour des quantités des catégories d'œufs
            foreach ($request->input('qte_perdu') as $categorie_id => $qte_perdu) {
                $categorieOeuf = CategorieOeuf::find($categorie_id);

                if ($categorieOeuf) {
                    // Soustraire la quantité perdue
                    $categorieOeuf->qteOeuf -= $qte_perdu;

                    // Recalculer le nombre de plateaux
                    $categorieOeuf->qteEnplateaux = (int)($categorieOeuf->qteOeuf / 30);

                    // Sauvegarder les modifications
                    $categorieOeuf->save();
                }
            }

            return redirect()->route('perte-eufs.index')->with('success', 'Perte d\'œufs enregistrée avec succès.');
        } catch (Exception $e) {
            \Log::error('Erreur lors de l\'ajout de la perte d\'œufs: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        try {
            $perte = PerteProduitOeuf::findOrFail($id);

            // Transformez les oeufs en un format lisible (e.g., id*qte format)
            $oeufs = [];
            foreach (explode(',', $perte->Oeuf) as $oeuf) {
                list($categorie_id, $qte) = explode('*', $oeuf);
                $oeufNom = CategorieOeuf::find($categorie_id)->Denomination;
                $oeufs[] = ['id' => $categorie_id, 'nom' => $oeufNom, 'qte' => $qte];
            }

            return response()->json([
                'date_p' => $perte->date_p,
                'description' => $perte->description,
                'oeufs' => $oeufs,
            ]);
        } catch (Exception $e) {
            \Log::error('Erreur lors de la récupération de la perte d\'œufs: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $perte = PerteProduitOeuf::findOrFail($id);
            $perte->date_p = $request->input('date'); // Make sure the input name is correct
            $perte->description = $request->input('description');
            $perte->type_perte = $request->input('type_perte');

            $qtePerdu = $request->input('qte_perdu');
            $oeufStrings = [];

            foreach ($qtePerdu as $categorie_id => $qte) {
                $oeufStrings[] = "$categorie_id*$qte";

                $categorieOeuf = CategorieOeuf::find($categorie_id);
                if ($categorieOeuf) {
                    // Check if `qte_perdu` is an array and has the key `$categorie_id`
                    if (is_array($perte->qte_perdu) && array_key_exists($categorie_id, $perte->qte_perdu)) {
                        $previousQte = $perte->qte_perdu[$categorie_id];
                    } else {
                        $previousQte = 0;
                    }

                    $categorieOeuf->qteOeuf -= $qte - $previousQte;
                    $categorieOeuf->qteEnplateaux = (int)($categorieOeuf->qteOeuf / 30);
                    $categorieOeuf->save();
                }
            }

            // Update the `Oeuf` field
            $perte->Oeuf = implode(',', $oeufStrings);
            $perte->save();

            return response()->json(['success' => true, 'message' => 'Perte d\'œufs mise à jour avec succès.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $perte = PerteProduitOeuf::findOrFail($id);
            $oeufs = explode(',', $perte->Oeuf);

            // Mise à jour des quantités des catégories d'œufs
            foreach ($oeufs as $oeuf) {
                list($categorie_id, $qte_perdu) = explode('*', $oeuf);
                $categorieOeuf = CategorieOeuf::find($categorie_id);

                if ($categorieOeuf) {
                    $categorieOeuf->qteOeuf += $qte_perdu;
                    $categorieOeuf->qteEnplateaux = (int)($categorieOeuf->qteOeuf / 30);
                    $categorieOeuf->save();
                }
            }

            $perte->delete();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            \Log::error('Erreur lors de la suppression de la perte d\'œufs: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


}
