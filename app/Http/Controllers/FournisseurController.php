<?php
namespace App\Http\Controllers;
use App\Models\Fournisseur;
use App\Models\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Produit;
use Barryvdh\DomPDF\Facade\Pdf;
class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::all();
        return view('fournisseur.liste', ['fournisseurs' => $fournisseurs]);
    }

    public function create()
    {
        $produits = Produit::all();
        return view('fournisseur.add', compact('produits'));
    }
    public function exportPdf()
    {
        $fournisseurs = Fournisseur::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('fournisseur.pdf', compact('fournisseurs','settings'));

        return $pdf->download('liste_fournisseurs.pdf');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'redevance_initiale' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'num_whatsapp' => 'required|string|max:255',
            'adresse_physique' => 'required|string|max:255',
            'infos_supp' => 'nullable|string',
            'produit' => 'required',
            // Ajoutez d'autres règles de validation au besoin
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $fournisseur = new Fournisseur();
            $fournisseur->nom = $request->input('nom');
            $fournisseur->prenom = $request->input('prenom');
            $fournisseur->redevance_initiale = $request->input('redevance_initiale');
            $fournisseur->phone = $request->input('phone');
            $fournisseur->num_whatsapp = $request->input('num_whatsapp');
            $fournisseur->adresse_physique = $request->input('adresse_physique');
            $fournisseur->infos_supp = $request->input('infos_supp');
            $fournisseur->produit_id = $request->input('produit');

            $fournisseur->save();

            return response()->json(['success' => true, 'fournisseur' => $fournisseur], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du fournisseur: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::error('Toutes les données: ' . json_encode($request->all()));
            $request->validate([
                'edit-nom' => 'required|string|max:255',
                'edit-prenom' => 'required|string|max:255',
                'edit-redevance-initiale' => 'required|string|max:255',
                'edit-telephone' => 'required|string|max:255',
                'edit-num-whatsapp' => 'required|string|max:255',
                'edit-adresse' => 'required|string|max:255',
                'edit-infos-supplementaires' => 'nullable|string',
                // Ajoutez d'autres règles de validation au besoin
            ]);

            $fournisseur = Fournisseur::findOrFail($id);
            $data = [
                'nom' => $request->input('edit-nom'),
                'prenom' => $request->input('edit-prenom'),
                'redevance_initiale' => $request->input('edit-redevance-initiale'),
                'phone' => $request->input('edit-telephone'),
                'num_whatsapp' => $request->input('edit-num-whatsapp'),
                'adresse_physique' => $request->input('edit-adresse'),
                'infos_supp' => $request->input('edit-infos-supplementaires'),
            ];

            $fournisseur->update($data);

            return response()->json(['success' => true, 'message' => 'Fournisseur mis à jour avec succès']);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du poulailler: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour du fournisseur', 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $fournisseur = Fournisseur::findOrFail($id);
            $fournisseur->delete();
            return response()->json(['success' => true, 'message' => 'Fournisseur supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du fournisseur', 'error' => $e->getMessage()]);
        }
    }
}

