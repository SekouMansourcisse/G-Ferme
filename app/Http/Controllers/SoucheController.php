<?php

namespace App\Http\Controllers;

use App\Models\Souche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class SoucheController extends Controller
{
    public function index()
    {
        $souches=Souche::all();
        return view('Parametre.listsouche',compact('souches'));
    }
    public function create()
    {
        return view('Parametre.addSouche');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Denomination' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'infos_supp'=>'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $souche = new Souche();
            $souche->Denomination = $request->input('Denomination');
            $souche->type = $request->input('type');
            $souche->infos_supp = $request->input('infos_supp');
            // Ajoutez d'autres champs au besoin

            $souche->save();

            return redirect()->route('souches.index')->with('success', 'Souches ajoutée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du souche: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'Denomination' => 'required|string|max:255',
        'type' => 'required|string',
        'infos_supp' => 'nullable|string'
    ]);

    $souche = Souche::findOrFail($id);
    $souche->update($validatedData);

    return response()->json(['success' => true, 'message' => 'Souche mise à jour avec succès.']);
}

public function destroy($id)
{
    $souche = Souche::findOrFail($id);
    $souche->delete();

    return response()->json(['success' => true, 'message' => 'Souche supprimée avec succès.']);
}

}
