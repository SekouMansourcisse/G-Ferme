<?php

namespace App\Http\Controllers;

use App\Models\Abbatoire;
use App\Models\Alimentation;
use App\Models\Pesage;
use App\Models\RamassageLitiere;
use App\Models\RamassageOeuf;
use App\Models\Souche;
use App\Models\Traitement;
use Illuminate\Http\Request;
use App\Models\Bande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\Operation;
use App\Models\Compte;
use App\Models\User;
use App\Models\Poulailler;
use App\Models\Journalisation;
use Carbon\Carbon;
class BandeController extends Controller
{
    //


    public function getAgeDetails($startDate) {
        $start = Carbon::parse($startDate);
        $now = Carbon::now();
        $diffInMonths = $start->diffInMonths($now);
        $diffInWeeks = $start->diffInWeeks($now) % 4;
        $diffInDays = $start->diffInDays($now) % 7;

        return [
            'months' => $diffInMonths,
            'weeks' => $diffInWeeks,
            'days' => $diffInDays
        ];
    }

    public function index()
    {
        $bandes = Bande::all();

        foreach ($bandes as $bande) {
            $journalisations = Journalisation::where('bande_id', $bande->id)->get();

            $totalDeaths = $journalisations->sum('Sujet_Mort');
            $totalSick = $journalisations->sum('Sujet_Malade');

            $bande->totalDeaths = $totalDeaths;
            $bande->totalSick = $totalSick;
            $bande->ageDetails = $this->getAgeDetails($bande->date_demarrage);
        }

        return view('bande.list', compact('bandes'));
    }

    public function edit($id)
    {
        // Récupérez la bande avec les données nécessaires pour le formulaire
        $bande = Bande::findOrFail($id);
        $users = User::all();
        $souches = Souche::all();
        $fournisseurs = Fournisseur::all();
        $comptes = Compte::all();
        $produits = Produit::all();
        $poulaillers = Poulailler::all();

        // Obtenez la chaîne des poulaillers pour cette bande
        $poulaillersString = $bande->poulailler; // Assurez-vous que vous avez un champ qui contient cette chaîne

        // Convertir la chaîne en tableau
        $bandePoulaillers = [];
        if ($poulaillersString) {
            $bandePoulaillers = explode(',', $poulaillersString);
        }

        // Retourne la vue d'édition avec les données
        return view('bande.edit', compact('bande', 'users', 'souches', 'fournisseurs', 'comptes', 'poulaillers', 'produits', 'bandePoulaillers'));
    }

    public function update(Request $request, $id)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'responsable' => 'required',
            'nom_bande' => 'required|string|max:255',
            'cheptel_depart' => 'required|integer',
            'type_elevage' => 'required',
            'souche_bande' => 'required',
            'date_demarrage' => 'required|date',
            'date_fin' => 'required|date',
            'age_arrive' => 'required|integer',
            'poid_moyen_depart' => 'required|numeric',
            'cout_acquisition' => 'required|numeric',
            // Ajoutez d'autres validations si nécessaire
        ]);

        // Récupérez la bande à mettre à jour
        $bande = Bande::findOrFail($id);

        // Mettez à jour les données de la bande
        $bande->responsable = $request->input('responsable');
        $bande->nom_bande = $request->input('nom_bande');
        $bande->cheptel_depart = $request->input('cheptel_depart');
        $bande->type_elevage = $request->input('type_elevage');
        $bande->souche_bande = $request->input('souche_bande');
        $bande->date_demarrage = $request->input('date_demarrage');
        $bande->date_fin = $request->input('date_fin');
        $bande->age_arrive = $request->input('age_arrive');
        $bande->poid_moyen_depart = $request->input('poid_moyen_depart');
        $bande->cout_acquisition = $request->input('cout_acquisition');

        // Mettez à jour les poulaillers et construisez la chaîne de données
        $poulaillersData = [];
        $poulaillers = $request->input('poulailler_selectionne');
        $cheptels = $request->input('nombre_cheptel');

        foreach ($poulaillers as $key => $poulaillerId) {
            if ($request->has('poulailler_selectionne.'.$key)) {
                $cheptel = $cheptels[$key];
                $poulailler = Poulailler::find($poulaillerId);
                $poulailler->update(['etat' => 1]);
                $poulaillersData[] = $poulaillerId . '*' . $cheptel;
            }
        }
        $bande->poulailler = implode(',', $poulaillersData);

        // Sauvegarder les modifications
        $bande->save();

        return redirect()->route('bandes.index')->with('success', 'Bande modifiée avec succès.');
    }

public function destroy($id)
{
    // Récupérez la bande à supprimer
    $bande = Bande::findOrFail($id);

    // Récupérez les poulaillers associés et mettez à jour leur état
    $poulaillerIds = explode(',', $bande->poulailler);
    foreach ($poulaillerIds as $data) {
        $poulaillerData = explode('*', $data);
        $poulaillerId = $poulaillerData[0];
        $poulailler = Poulailler::find($poulaillerId);
        $poulailler->update(['etat' => 0]);
    }

    // Supprimez la bande
    $bande->delete();

    return redirect()->route('nom_route_affichage')->with('success', 'Bande supprimée avec succès');
}

    public function create()
    {
        //
        $poulaillers = Poulailler::where('etat','0')->get();
        $fournisseurs = Fournisseur::all();
        $comptes = Compte::all();
        $produits = Produit::all();
        $users = User::all();
        $souches=Souche::all();
        return view('bande.add',compact('poulaillers','comptes','fournisseurs','produits','users','souches'));
    }
    public function cloturerBande(Request $request)
    {
        $bandeId = $request->input('bande_id');

        // Récupérer la bande
        $bande = Bande::find($bandeId);
        if (!$bande) {
            return response()->json(['success' => false, 'message' => 'Bande non trouvée.']);
        }

        // Mettre à jour l'état de la bande
        $bande->etat = 2;
        $bande->save();

        // Ajouter l'effectif actuel de la bande à l'abattoir
        $abattoir = Abbatoire::first(); // On suppose qu'il y a un seul abattoir, vous pouvez ajuster si nécessaire
        if ($abattoir) {
            $abattoir->quantite_sujet += $bande->cheptel_actuel;
            $abattoir->save();
        }
        session()->flash('active_tab', 'cloturé');
        return response()->json(['success' => true, 'message' => 'Bande clôturée avec succès.']);
    }

    public function store(Request $request)
    {
        // Validez les données du formulaire
        $validatedData = $request->validate([
            // Définissez vos règles de validation ici
        ]);

        // Récupérez les poulaillers sélectionnés et leurs nombres de cheptel
        $poulaillers = $request->input('poulailler_selectionne');
        $cheptels = $request->input('nombre_cheptel');

        // Construisez la chaîne de données pour les poulaillers sélectionnés
        $poulaillersData = [];
        foreach ($poulaillers as $key => $poulaillerId) {
            if ($request->has('poulailler_selectionne.'.$key)) {
                $cheptel = $cheptels[$key];
                $poulailler = Poulailler::find($poulaillerId);
                $poulailler->update(['etat' => 1]);
                $poulaillersData[] = $poulaillerId . '*' . $cheptel;
            }
        }
        $poulaillersString = implode(',', $poulaillersData);
        $t=$request->input('type_elevage');
        if($t=="Poulet de chair"){
            $type=1;
        }else{
            $type=2;
        }
        // Traitez les autres données du formulaire et enregistrez la bande en base de données
        $fournisseur_nom = Fournisseur::find($request->fournisseur_id)->nom;
        $bande = new Bande();
        $bande->responsable = $request->input('responsable');
        $bande->nom_bande = $request->input('nom_bande');
        $bande->cheptel_depart = $request->input('cheptel_depart');
        $bande->cheptel_actuel = $request->input('cheptel_depart');
        $bande->type_elevage = $request->input('type_elevage');
        $bande->type = $type;
        $bande->etat=1;
        $bande->souche_bande = $request->input('souche_bande');
        $bande->date_demarrage = $request->input('date_demarrage');
        $bande->date_fin = $request->input('date_fin');
        $bande->age_arrive = $request->input('age_arrive');
        $bande->poid_moyen_depart = $request->input('poid_moyen_depart');
        $bande->cout_acquisition = $request->input('cout_acquisition');
        $bande->montant_paye = $request->input('montant_paye');
        $bande->fournisseur = $request->input('fournisseur_id');
        $bande->nomFournisseur = $fournisseur_nom;
        $bande->observation = $request->input('observation');
        $bande->poulailler = $poulaillersString;

        // Sauvegardez la bande et récupérez son ID
        $bande->save();
        $bande_id = $bande->id;
        // Log pour vérifier les données de la bande
        \Log::info('Bande créée:', $bande->toArray());
        // Créez l'opération de ravitaillement
        $ravitaillement = Operation::create([
            'NomPrenomFournisseur' => $fournisseur_nom,
            'Fournisseur' => $request->fournisseur_id,
            'bande' => $bande_id,
            'cout_acquisition' => $request->cout_acquisition,
            'Montant_facture' => $request->net_payer,
            'totalRemise' => $request->total_remise,
            'montant_payé' => $request->montant_paye,
            'montantDette' => $request->dette_a_paye,
            'typeOperation' => 'acquisition bande',
            'Payer_par' => $request->payer_par,
            'Date_op' => $request->date_demarrage
        ]);

        $compte = Compte::where('id', $request->payer_par)->first();
        $compte->update(['solde_actuel' => $compte->solde_actuel - $request->montant_paye]);

        // Retournez une réponse JSON pour indiquer le succès de l'opération
        return response()->json(['success' => true]);
    }
    public function calendrierT()
    {
        return view('bande.teste');
    }
    public function Operation($id)
    {
        $bande = Bande::findOrFail($id);

            $journalisations = Journalisation::where('bande_id', $bande->id)->get();

            $totalDeaths = $journalisations->sum('Sujet_Mort');
            $totalSick = $journalisations->sum('Sujet_Malade');

            $bande->totalDeaths = $totalDeaths;
            $bande->totalSick = $totalSick;
            $bande->ageDetails = $this->getAgeDetails($bande->date_demarrage);

        $produits = Produit::all() ;
        $alimentations = Alimentation::where('bande_id', $id)->get();
        $Ramassages = RamassageOeuf::where('bande_id', $id)->get();
        $RamassagesL = RamassageLitiere::where('bande_id', $id)->get();
        $PesagesL = Pesage::where('bande_id', $id)->get(); // Supposons que les pesages sont également dans RamassageLitiere
        $ramassageOeufs=RamassageOeuf::where('bande_id', $id)->get();
        $soins=Traitement::where('bande_id',$id)->get();
        return view('bande.operation', compact('bande','journalisations', 'produits', 'alimentations', 'Ramassages', 'RamassagesL', 'PesagesL','ramassageOeufs','soins'));
    }
    public function Statistique($id)
    {
        $bande = Bande::findOrFail($id);
        $pesages = Pesage::where('bande_id', $id)->get();
     return view('bande.statistique', compact('bande','pesages'));
    }


    public function getPoulaillerInfo($poulaillerString) {
        $poulaillers = explode(',', $poulaillerString);
        $info = [];

        foreach ($poulaillers as $poulailler) {
            list($id, $cheptel) = explode('*', $poulailler);
            $poulailler_n = Poulailler::find($id)->Denomination;
            $info[] = "Poulailler: $poulailler_n, Cheptel: $cheptel";
        }

        return implode('<br>', $info);
    }
    public function getPoulaillerInfo2($poulaillerString) {
        $poulaillers = explode(',', $poulaillerString);
        $info = [];

        foreach ($poulaillers as $poulailler) {
            list($id, $cheptel) = explode('*', $poulailler);
            $poulailler_n = Poulailler::find($id)->Denomination;
            $info[] = "$poulailler_n ($cheptel)";
        }

        return implode('<br>', $info);
    }


}
