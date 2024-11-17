<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poulailler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;
class PoulaillerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $poulaillers = Poulailler::all();
        return view('poulailler.list', ['poulaillers' => $poulaillers]);
    }
    public function getNom($id)
{
    $poulailler = Poulailler::find($id);
    return response()->json(['nom' => $poulailler ? $poulailler->Denomination : null]);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('poulailler.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::error('Toutes les données: ' . json_encode($request->all()));
        $validator = Validator::make($request->all(), [
            'denomination' => 'required|string|max:255',
            'contenance_normale' => 'required|string|max:255',
            'dimension' => 'required|string|max:255',
            'infos_supp' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $poulailler = new Poulailler();
            $poulailler->Denomination = $request->input('denomination');
            $poulailler->contenance_normale = $request->input('contenance_normale');
            $poulailler->Dimension = $request->input('dimension');
            $poulailler->infos_supp = $request->input('infos_supp');
            $poulailler->etat = 0;
            $poulailler->save();

            return response()->json(['success' => true, 'poulailler' => $poulailler], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'ajout du poulailler: ' . $e->getMessage());
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            // Valider les données du formulaire
            $request->validate([
                'edit-denomination' => 'required|string',
                'edit-contenance-normale' => 'required|string',
                'edit-dimension' => 'required|string',
                'edit-infos-supplementaires' => 'nullable|string',
                // Ajoutez d'autres règles de validation au besoin
            ]);

            // Récupérer le poulailler à mettre à jour
            $poulailler = Poulailler::findOrFail($id);
            $data = [
                'Denomination' => $request->input('edit-denomination'),
                'contenance_normale' => $request->input('edit-contenance-normale'),
                'Dimension' => $request->input('edit-dimension'),
                'infos_supp' => $request->input('edit-infos-supplementaires'),
            ];

            // Mettre à jour les données du poulailler
            $poulailler->update($data);

            return response()->json(['success' => true, 'message' => 'Poulailler mis à jour avec succès']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour du poulailler', 'error' => $e->getMessage()]);
        }
    }

    // Fonction pour supprimer un poulailler
    public function destroy($id)
    {
        try {
            // Récupérer le poulailler à supprimer
            $poulailler = Poulailler::findOrFail($id);

            // Supprimer le poulailler
            $poulailler->delete();

            return response()->json(['success' => true, 'message' => 'Poulailler supprimé avec succès']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du poulailler', 'error' => $e->getMessage()]);
        }
    }

}
