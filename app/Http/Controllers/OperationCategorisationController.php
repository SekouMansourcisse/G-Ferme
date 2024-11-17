<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\CategorieOeuf;
use App\Models\Classification;
use App\Models\OperationCategorisation;
use Illuminate\Http\Request;

class OperationCategorisationController extends Controller
{
    public function index()
    {
        $categories=CategorieOeuf::all();
    // Récupérer les bandes avec Total_a_categoriser > 0 et etat = 0
    $classifications = Classification::where('Total_a_categoriser', '>', 0)
                                     ->where('etat', '=', 0)
                                     ->get();


    return view('TriOeuf.categoriser', compact('classifications','categories'));

    }

    public function create()
    {
        return view('operationCategorisations.create');
    }

    public function store(Request $request)
    {
        $dateOp = $request->input('date_op');
        $categoriesData = $request->input('categories');

        foreach ($categoriesData as $bandeId => $categoryData) {
            $tableCategorie = [];

            foreach ($categoryData as $categoryId => $quantity) {
                // Créer l'entrée pour la table OperationCategorisation
                $tableCategorie[] = "$bandeId*$categoryId*$quantity";

                // Mettre à jour la table CategorieOeuf
                $categorie = CategorieOeuf::find($categoryId);
                $categorie->qteOeuf += $quantity;

                // Recalculer les quantités en plateaux et la valeur financière
                $categorie->qteEnplateaux = floor($categorie->qteOeuf / 30);
                $categorie->ValeurFinancier = $categorie->qteEnplateaux * $categorie->PrixPlateaux;
                $categorie->save();
            }

            OperationCategorisation::create([
                'date_op' => $dateOp,
                'TableCategorie' => implode(',', $tableCategorie),
            ]);

            // Mettre à jour l'état de la classification à 1
            $classification = Classification::where('bande_id', $bandeId)
                                            ->where('etat', 0)
                                            ->first();
            if ($classification) {
                $classification->etat = 1;
                $classification->save();
            }
        }

        return redirect()->route('operationCategorisations.index')->with('success', 'Opération de catégorisation enregistrée avec succès.');
    }



    public function show(OperationCategorisation $operationCategorisation)
    {
        return view('operationCategorisations.show', compact('operationCategorisation'));
    }

    public function edit(OperationCategorisation $operationCategorisation)
    {
        return view('operationCategorisations.edit', compact('operationCategorisation'));
    }

    public function update(Request $request, OperationCategorisation $operationCategorisation)
    {
        $request->validate([
            'date_op' => 'required|string|max:100',
            'TableCategorie' => 'required|string|max:100',
        ]);

        $operationCategorisation->update($request->all());
        return redirect()->route('operationCategorisations.index')->with('success', 'OperationCategorisation updated successfully.');
    }

    public function destroy(OperationCategorisation $operationCategorisation)
    {
        $operationCategorisation->delete();
        return redirect()->route('operationCategorisations.index')->with('success', 'OperationCategorisation deleted successfully.');
    }
}
