<?php

namespace App\Http\Controllers;

use App\Models\AlimentationBetail;
use App\Models\Ferme;
use App\Models\Race;
use App\Models\Traitement;
use App\Models\Vache;
use App\Models\VacheLaitiere;
use DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class VacheController extends Controller
{
    public function index()
    {
        $vaches = Vache::where('etat',1)->get();
        $races=Race::all();
        return view('vaches.list', compact('vaches','races'));
    }

    public function exportList()
    {
        $vaches = Vache::where('etat',1)->get();;
        $pdf = Pdf::loadView('vaches.listvache_pdf', compact('vaches'));

        return $pdf->download('List_vaches.pdf');
    }

    public function create()
    {
        $fermes= Ferme::where('TypeFerme','lait')->get();
        $races=Race::all();
        return view('vaches.add', compact('fermes','races'));
    }

    public function sante(Request $request)
    {
        $vache=Vache::find($request->input('vache_id'));
        $vache->etat_sante=$request->input('etat');
        $vache->save();
        return redirect()->back()->with('success','Modification effectuée avec succès');
    }
    public function dashboard()
    {
        $soins = Traitement::where('date', '>=', Carbon::now())->where('bande_id',null)
        ->get();

        return view('vaches.dashboard',compact('soins'));
    }
    public function getChartData(Request $request)
    {
        // Récupérer les deux dates du formulaire ou requête AJAX
        $date_1 = $request->input('date_1');
        $date_2 = $request->input('date_2');

        if (!$date_1) {
            $date_1 = Carbon::now()->startOfMonth()->toDateString(); // Début du mois en cours
        }
        if (!$date_2) {
            $date_2 = Carbon::now()->endOfMonth()->toDateString(); // Fin du mois en cours
        }

        // Production laitière entre les deux dates
        $productions = VacheLaitiere::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(production_matin + production_soir) as production_totale')
        )
        ->whereBetween('created_at', [$date_1, $date_2])
        ->groupBy('date')
        ->get();

        // Consommation alimentaire de toutes les vaches entre les deux dates
        $alimentationGlobale = AlimentationBetail::select(
            DB::raw('DATE(date_alimentation) as date_ali'),
            DB::raw('SUM(quantite) as consommation_totale')
        )
        ->whereBetween('created_at', [$date_1, $date_2])
        ->groupBy('date_alimentation') // Grouper par date seulement (sans heure)
        ->get();

        // Préparer les tableaux pour les dates, production et consommation
        $dates = [];
        $dates2=[];
        $productionData = [];
        $consommationGlobaleData = [];

        // Indexer les données de production par date
        foreach ($productions as $production) {
            $dates[] = $production->date;
            $productionData[] = $production->production_totale;
        }

        // Indexer les données de consommation globale par date
        foreach ($alimentationGlobale as $aliment) {
            $dates2[]=$aliment->date_ali;
            $consommationGlobaleData[] = $aliment->consommation_totale;
        }

        return response()->json([
            'dates' => $dates,
            'dates2'=>$dates2,
            'productionLaitiere' => $productionData,
            'consommationGlobale' => $consommationGlobaleData,
        ]);
    }

    public function store(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'race' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'etat_sante' => 'required|string|max:255',
            'type_elevage' => 'required|string|max:255',
            'ferme_id' => 'required|string|max:255',
        ]);

        // Création d'une nouvelle vache
        $vache = new Vache([
            'race_id' => $request->get('race'),
            'date_naissance' => $request->get('date_naissance'),
            'etat_sante' => $request->get('etat_sante'),
            'ferme_id' => $request->get('ferme_id'),
            'type_elevage' => $request->get('type_elevage'),
            'etat'=>1
        ]);

        // Sauvegarde de la vache dans la base de données
        $vache->save();

        // Redirection avec un message de succès
        return redirect()->route('vaches.index')->with('success', 'Vache ajoutée avec succès !');
    }

    public function show(Vache $vache)
    {
        return view('vaches.show', compact('vache'));
    }

    public function edit($id)
    {
        $vache = Vache::findOrFail($id);
        return response()->json($vache);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'race_id' => 'required|exists:races,id',
            'type_elevage' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'etat_sante' => 'required|string|max:255',
        ]);

        $vache = Vache::findOrFail($id);
        $vache->update([
            'nom' => $request->nom,
            'race_id' => $request->race_id,
            'type_elevage' => $request->type_elevage,
            'date_naissance' => $request->date_naissance,
            'etat_sante' => $request->etat_sante,
        ]);
        return response()->json(['success' => true, 'message' => 'Vache mise à jour avec succès']);
    }


    public function destroy($id)
    {
        $vache = Vache::findOrFail($id);
        $vache->delete();

        return response()->json(['success' => true, 'message' => 'Vache supprimée avec succès.']);
    }
}

