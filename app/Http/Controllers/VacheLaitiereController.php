<?php

namespace App\Http\Controllers;

use App\Models\AlimentationBetail;
use App\Models\Produit;
use App\Models\Traitement;
use App\Models\Vache;
use App\Models\VacheLaitiere;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Log;

class VacheLaitiereController extends Controller
{
    public function index()
    {
        $vaches = Vache::where('type_elevage','lait')->where('etat',1)
        ->get();
        return view('vaches.vachesLait', compact('vaches'));
    }
    public function exportList()
    {
        $vaches =  Vache::where('type_elevage','lait')->where('etat',1)
        ->get();
        $pdf = Pdf::loadView('vaches.pdf_vacheL', compact('vaches'));

        return $pdf->download('List_vache_laitieres.pdf');
    }
    public function productionLait()
    {
        $productions = VacheLaitiere::select(
            DB::raw('DATE(date_production) as date'),
            DB::raw('SUM(production_matin) as production_matin'),
            DB::raw('SUM(production_soir) as production_soir'),
            DB::raw('SUM(production_matin + production_soir) as production_totale')
        )->groupBy(DB::raw('DATE(date_production)'))->get();

        return view('vaches.productionlait', compact('productions'));
    }


    public function create()
    {
        return view('vaches_laitieres.create');
    }
    public function OperationL($id)
    {

        $vache=Vache::findOrFail($id);
        $traites=VacheLaitiere::where('vache_id',$id)->get();
        $produits=Produit::where('typeProduit','Autres produits')->get();
        $soins=Traitement::where('vache_id',$id)->get();
        $alimentations=AlimentationBetail::where('vache_id',$id)->get();
        return view('vaches.operation',compact('vache','traites','produits','alimentations','soins'));
    }
    public function statistiqueL($id)
    {
        $vache=Vache::findOrFail($id);
        $traites=VacheLaitiere::where('vache_id',$id)->get();
        return view('vaches.statistique',compact('vache','traites'));
    }
    public function getVacheStatistics(Request $request)
    {
        // Récupérer les paramètres de la requête
        $vache_id = $request->input('vache_id');
        $date_1 = $request->input('date_1');
        $date_2 = $request->input('date_2');

        // Récupérer la production de lait pour la vache et la période donnée
        $productions = DB::table('vacheslaitieres')
            ->where('vache_id', $vache_id)
            ->whereBetween('date_production', [$date_1, $date_2])
            ->select(DB::raw('DATE(date_production) as date'),
                     DB::raw('SUM(production_matin + production_soir) as total_production'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Récupérer la consommation de nourriture pour la vache et la période donnée
        $alimentation = DB::table('alimentation_betail')
            ->where('vache_id', $vache_id)
            ->whereBetween('date_alimentation', [$date_1, $date_2])
            ->select(DB::raw('DATE(date_alimentation) as date'),
                     DB::raw('SUM(quantite) as total_consommation'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Préparer les tableaux pour les dates, production et consommation
        $dates = [];
        $productionData = [];
        $consommationData = [];

        // Indexer les données de production par date
        foreach ($productions as $production) {
            $dates[] = $production->date;
            $productionData[] = $production->total_production;
        }

        // Indexer les données de consommation par date
        foreach ($alimentation as $aliment) {
            // Si la date n'est pas déjà dans le tableau des dates, on l'ajoute
            if (!in_array($aliment->date, $dates)) {
                $dates[] = $aliment->date;
            }
            $consommationData[] = $aliment->total_consommation;
        }

        // Assurer que les dates soient triées dans l'ordre croissant
        sort($dates);

        // Retourner les données en format JSON
        return response()->json([
            'dates' => $dates,
            'production' => $productionData,
            'consommation' => $consommationData,
        ]);
    }

    public function store(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'date' => 'required|date',
            'qte_m' => 'required|numeric|min:0',
            'qte_s' => 'required|numeric|min:0',
            'vache_id' => 'required|string|max:255',
        ]);

        // Création d'une nouvelle vache
        $vache = new VacheLaitiere([
            'vache_id' => $request->get('vache_id'),
            'production_matin' => $request->get('qte_m'),
            'date_production' => $request->get('date'),
            'production_soir' => $request->get('qte_s'),
        ]);

        // Sauvegarde de la vache dans la base de données
        $vache->save();
        $quantite=$request->get('qte_m')+$request->get('qte_s');
        $lait=Produit::where('Denomination','Lait')->first();
        if($lait){

            $lait->qte_stock += $quantite;
            $lait->save();
        }


                // Redirection avec un message de succès
                session()->flash('active_tab', 'Traite');
        return redirect()->back()->with('success', 'Traite ajoutée avec succès !');
    }

    public function show(VacheLaitiere $vacheLaitiere)
    {
        return view('vaches_laitieres.show', compact('vacheLaitiere'));
    }

    public function edit(VacheLaitiere $vacheLaitiere)
    {
        return view('vaches_laitieres.edit', compact('vacheLaitiere'));
    }

    public function update(Request $request, $id)
    {
        $vacheLaitiere=VacheLaitiere::find($id);
        $vacheLaitiere->update($request->all());
        return response()->json(['success' => true, 'message' => 'traite mise à jour avec succès']);
    }

    public function destroy($id)
    {
        $vacheLaitiere = VacheLaitiere::find($id);
        $vacheLaitiere->delete();
        return response()->json(['success' => true, 'message' => 'traite supprimée avec succès.']);
    }
}

