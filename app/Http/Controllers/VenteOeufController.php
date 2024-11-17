<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\CategorieOeuf;
use App\Models\Client;
use App\Models\Compte;
use App\Models\Operation;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class VenteOeufController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer toutes les opérations de type "vente"
        $ventes = Operation::where('typevente', 'vente-oeuf')->get();

        // Retourner la vue avec les ventes
        return view('venteOeuf.list', compact('ventes'));
    }

    public function exportPdf()
    {
        $ventes = Operation::where('typevente', 'vente-oeuf')->get();
        $pdf = Pdf::loadView('venteOeuf.pdf', compact('ventes'));

        return $pdf->download('liste_Vente-Oeufs.pdf');
    }
    public function Venteview()
    {
        $categories = CategorieOeuf::all();
        $bandes = Bande::where('etat',1)->get();
        $produits = Produit::where('typeProduit','Les produits commercialisable')->get();
        $clients= Client::all();
        $comptes=Compte::all();
        return view('commande.add',compact('categories','clients','comptes','produits','bandes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = CategorieOeuf::all();
        $clients= Client::all();
        $comptes=Compte::all();
        return view('VenteOeuf.add',compact('categories','clients','comptes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'date' => 'required|date',
            'type_client' => 'required|string',
            'client_comptoir' => 'nullable|string',
            'client_id' => 'nullable|exists:client,id',
            'phone' => 'required|string',
            'total_ravitaillement' => 'required|numeric',
            'total_remise' => 'required|numeric',
            'net_payer' => 'required|numeric',
            'montant_paye' => 'required|numeric',
            'dette_a_paye' => 'required|numeric',
            'payer_par' => 'required|exists:compte,id',
            'qte_plateaux.*' => 'required|integer|min:0',
            'alv.*' => 'nullable',
        ]);

        // Créer les infos sur les œufs vendus
        $infosOeuf = [];
        foreach ($request->qte_plateaux as $categorieId => $qtePlateaux) {
            $infosOeuf[] = $categorieId . '*' . $qtePlateaux;
        }
        $infosOeufString = implode(',', $infosOeuf);
        if($request->client_id){
            $client=Client::find($request->client_id);
            $nomPrenom=$client->nom .' '. $client->prenom ;
        }
        // Début de la transaction
        DB::beginTransaction();

        try {
            // Créer une nouvelle opération
            $operation = Operation::create([
                'NomPrenomClient' => $request->type_client === 'Client Comptoir' ? 'client au comptoir' : $nomPrenom,
                'client' => $request->type_client === 'Client Fidèle' ? $request->client_id : null,
                'infosOeuf' => $infosOeufString,
                'Montant_facture' => $request->net_payer,
                'Totalvente' => $request->total_ravitaillement,
                'montant_payé' => $request->montant_paye,
                'montantDette' => $request->dette_a_paye,
                'totalRemise' => $request->total_remise,
                'typeOperation' => 'vente',
                'Fournisseur' => null,
                'NomPrenomFournisseur' => null,
                'Produit' => null,
                'TotalRavitaillement' => $request->total_ravitaillement,
                'Payer_par' => $request->payer_par,
                'Date_op' => $request->date,
                'typevente'=>$request->type,
            ]);

            // Mettre à jour les quantités dans la table categorieoeuf
            foreach ($request->qte_plateaux as $categorieId => $qtePlateaux) {
                $categorieOeuf = CategorieOeuf::findOrFail($categorieId);
                $categorieOeuf->qteEnplateaux -= $qtePlateaux;
                $categorieOeuf->qteOeuf-= (int)($qtePlateaux * 30);
                $categorieOeuf->ValeurFinancier=$categorieOeuf->PrixPlateaux*$categorieOeuf->qteEnplateaux;
                // Assurez-vous que la colonne 'oeufs' soit mise à jour en conséquence si nécessaire
                $categorieOeuf->save();
            }
            // Mettre à jour le compte
            $compte = Compte::where('id', $request->payer_par)->first();
            $compte->update(['solde_actuel' => $compte->solde_actuel + $request->montant_paye]);
            // Confirmer la transaction
            DB::commit();

            return redirect()->route('vente-oeufs.index')->with('success', 'Vente enregistrée avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollback();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la vente.');
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
    public function edit($id)
    {
        // Récupérer la vente d'œufs par son ID
        $venteOeuf = Operation::where('typevente', 'vente-oeuf')
            ->where('id', $id)
            ->firstOrFail();

        // Récupérer les clients, comptes et catégories si nécessaire
        $clients = Client::all();
        $comptes = Compte::all();
        $categories = CategorieOeuf::all();

        // Analyser la chaîne d'infosOeuf pour créer un tableau de catégories
        $infosOeufArray = [];
        if (!empty($venteOeuf->infosOeuf)) {
            $infos = explode(',', $venteOeuf->infosOeuf);
            foreach ($infos as $info) {
                list($categorieId, $quantite) = explode('*', $info);
                // Vous pouvez récupérer plus d'informations sur la catégorie si nécessaire
                $categorie = CategorieOeuf::find($categorieId);
                $infosOeufArray[] = [
                    'categorie' => $categorie,
                    'quantite' => $quantite,
                    'prixplateaux'=>$categorie->PrixPlateaux
                ];
            }
        }

        return view('venteOeuf.edit', compact('venteOeuf', 'clients', 'comptes', 'categories', 'infosOeufArray'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Valider les données du formulaire
        $request->validate([
            'date' => 'required|date',
            'type_client' => 'required|string',
            'client_comptoir' => 'nullable|string',
            'client_id' => 'nullable|exists:client,id',
            'phone' => 'required|string',
            'total_ravitaillement' => 'required|numeric',
            'total_remise' => 'required|numeric',
            'net_payer' => 'required|numeric',
            'montant_paye' => 'required|numeric',
            'dette_a_paye' => 'required|numeric',
            'payer_par' => 'required|exists:compte,id',
            'qte_plateaux.*' => 'required|integer|min:0',
            'alv.*' => 'nullable',
        ]);

        // Récupérer la vente existante
        $vente = Operation::findOrFail($id);
        $oldQuantities = explode(',', $vente->infosOeuf);

        // Annuler les anciennes quantités dans la table categorieoeuf
        foreach ($oldQuantities as $info) {
            list($categorieId, $qtePlateaux) = explode('*', $info);
            $categorieOeuf = CategorieOeuf::findOrFail($categorieId);

            // Restaurer les valeurs d'origine
            $categorieOeuf->qteEnplateaux += $qtePlateaux;
            $categorieOeuf->qteOeuf += (int)($qtePlateaux * 30);

            // Mise à jour de la valeur financière après restauration
            $categorieOeuf->ValeurFinancier = $categorieOeuf->PrixPlateaux * $categorieOeuf->qteEnplateaux;
            $categorieOeuf->save();
        }

        // Annuler l'ancien montant payé dans le compte
        $compte = Compte::findOrFail($vente->Payer_par);
        $compte->update(['solde_actuel' => $compte->solde_actuel - $vente->montant_payé]);

        // Préparer les nouvelles informations sur les œufs vendus
        $infosOeuf = [];
        foreach ($request->qte_plateaux as $categorieId => $qtePlateaux) {
            $infosOeuf[] = $categorieId . '*' . $qtePlateaux;
        }
        $infosOeufString = implode(',', $infosOeuf);

        if ($request->client_id) {
            $client = Client::find($request->client_id);
            $nomPrenom = $client->nom . ' ' . $client->prenom;
        }

        // Mettre à jour les informations de vente
        $vente->update([
            'NomPrenomClient' => $request->type_client === 'Client Comptoir' ? 'client Comptoir' : $nomPrenom,
            'client' => $request->type_client === 'Client Fidèle' ? $request->client_id : null,
            'infosOeuf' => $infosOeufString,
            'Montant_facture' => $request->net_payer,
            'Totalvente' => $request->total_ravitaillement,
            'montant_payé' => $request->montant_paye,
            'montantDette' => $request->dette_a_paye,
            'totalRemise' => $request->total_remise,
            'Payer_par' => $request->payer_par,
            'Date_op' => $request->date,
        ]);

        // Mettre à jour les nouvelles quantités dans la table categorieoeuf
        foreach ($request->qte_plateaux as $categorieId => $qtePlateaux) {
            $categorieOeuf = CategorieOeuf::findOrFail($categorieId);

            // Appliquer les nouvelles valeurs
            $categorieOeuf->qteEnplateaux -= $qtePlateaux;
            $categorieOeuf->qteOeuf -= (int)($qtePlateaux * 30);

            // Calcul de la nouvelle valeur financière
            $categorieOeuf->ValeurFinancier = $categorieOeuf->PrixPlateaux * $categorieOeuf->qteEnplateaux;
            $categorieOeuf->save();
        }

        // Mettre à jour le compte avec le nouveau montant payé
        $compte = Compte::findOrFail($request->payer_par);
        $compte->update(['solde_actuel' => $compte->solde_actuel + $request->montant_paye]);


        return redirect()->route('vente-oeufs.index')->with('success', 'Vente mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $venteOeuf = Operation::findOrFail($id);
        $venteOeuf->delete();

        return response()->json(['success' => true, 'message' => 'Vente supprimée avec succès.']);
    }

}
