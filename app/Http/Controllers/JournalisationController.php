<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\Journalisation;
use App\Models\Poulailler;
use Illuminate\Http\Request;
use Carbon\Carbon;
class JournalisationController extends Controller
{
    public function index()
    {
        $journalisations = Journalisation::all();
        return view('bande.journalisation', compact('journalisations'));
    }

    public function create()
    {
        return view('journalisations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Date' => 'required|date',
            'Age' => 'required|integer',
            'Tri' => 'required|array',
            'Malade' => 'required|array',
            'Mort' => 'required|array',
            'Retour_malade' => 'required|array',
            'Resume' => 'required|string',
            'bande_id' => 'required|integer'
        ]);

        $bande = Bande::findOrFail($data['bande_id']);
        $poulaillerEntries = explode(',', $bande->poulailler);
        $poulaillerData = [];
        $totalTri = 0;
        $totalMalade = 0;
        $totalMort = 0;
        $totalRetourMalade = 0;

        foreach ($poulaillerEntries as $index => $poulaillerEntry) {
            list($id, $cheptel) = explode('*', $poulaillerEntry);
            $tri = $data['Tri'][$index];
            $malade = $data['Malade'][$index];
            $mort = $data['Mort'][$index];
            $retourMalade = $data['Retour_malade'][$index];

            $poulaillerData[] = "$id*$tri*$malade*$mort*$retourMalade";

            $totalTri += $tri;
            $totalMalade += $malade;
            $totalMort += $mort;
            $totalRetourMalade += $retourMalade;
        }

        $journalisation = new Journalisation([
            'Date' => $data['Date'],
            'Age' => $data['Age'],
            'Poulailler' => implode(',', $poulaillerData),
            'Sujet_Tri' => $totalTri,
            'Sujet_Malade' => $totalMalade,
            'Sujet_Mort' => $totalMort,
            'Sujet_retour_maladie' => $totalRetourMalade,
            'commentaire' => $data['Resume'],
            'bande_id' => $bande->id,
        ]);

        $journalisation->save();

        // Deduct the number of dead subjects from the current livestock
        $bande->cheptel_actuel -= $totalMort;
        $bande->save();

        // Set the active tab
        session()->flash('active_tab', 'journalisation');

        return redirect()->route('operation', ['id' => $bande->id])->with('success', 'Journalisation ajoutée avec succès.');
    }
    public function getMortaliteStatistics(Request $request)
    {
        $bandeId = $request->input('bande_id');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Récupérer les journalisations pour la période spécifiée et la bande
        $journalisations = Journalisation::where('bande_id', $bandeId)
                                          ->whereBetween('Date', [$startDate, $endDate])
                                          ->get();

        $bande = Bande::find($bandeId);

        // Calculer les statistiques
        $mortalityRate = $this->calculateMortalityRate($journalisations, $bande->cheptel_actuel);
        $mortByPoulailler = $this->calculateMortByPoulailler($journalisations);
        $mortalityRateByPoulailler = $this->calculateMortalityRateByPoulailler($journalisations, $bande->cheptel_actuel);
        $mortalityRateByDate = $this->calculateMortalityRateByDate($journalisations, $bande->cheptel_actuel);

        // Récupérer les noms des poulaillers
        $poulaillerNames = Poulailler::whereIn('id', array_keys($mortByPoulailler))->pluck('Denomination', 'id')->toArray();

        // Remplacer les IDs par les noms dans les résultats
        $mortByPoulailler = $this->replacePoulaillerIdsWithNames($mortByPoulailler, $poulaillerNames);
        $mortalityRateByPoulailler = $this->replacePoulaillerIdsWithNamesInNestedArray($mortalityRateByPoulailler, $poulaillerNames);

        return response()->json([
            'Rate' => $mortalityRate,
            'DonutChartData' => $mortByPoulailler,
            'LineChartData' => $mortalityRateByPoulailler,
            'AreaChartData' => $mortalityRateByDate,
            'Nombre'=>$journalisations->sum('Sujet_Mort'),
        ]);
    }
    private function calculateMortalityRate($journalisations, $cheptelActuel)
    {
        $totalMort = $journalisations->sum('Sujet_Mort');
        return $totalMort / $cheptelActuel * 100;
    }

    private function calculateMortByPoulailler($journalisations)
    {
        $mortByPoulailler = [];

        foreach ($journalisations as $journal) {
            $poulaillerEntries = explode(',', $journal->Poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($poulaillerId, $tri, $malade, $mort, $retourMalade) = explode('*', $entry);
                if (!isset($mortByPoulailler[$poulaillerId])) {
                    $mortByPoulailler[$poulaillerId] = 0;
                }
                $mortByPoulailler[$poulaillerId] += $mort;
            }
        }

        return $mortByPoulailler;
    }

    private function calculateMortalityRateByPoulailler($journalisations, $cheptelActuel)
    {
        $mortalityRateByPoulailler = [];

        foreach ($journalisations as $journal) {
            $date = Carbon::parse($journal->Date)->format('d M');
            $poulaillerEntries = explode(',', $journal->Poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($poulaillerId, $tri, $malade, $mort, $retourMalade) = explode('*', $entry);
                if (!isset($mortalityRateByPoulailler[$poulaillerId])) {
                    $mortalityRateByPoulailler[$poulaillerId] = [];
                }
                if (!isset($mortalityRateByPoulailler[$poulaillerId][$date])) {
                    $mortalityRateByPoulailler[$poulaillerId][$date] = 0;
                }
                $mortalityRateByPoulailler[$poulaillerId][$date] += $mort / $cheptelActuel * 100;
            }
        }

        return $mortalityRateByPoulailler;
    }

    private function calculateMortalityRateByDate($journalisations, $cheptelActuel)
    {
        $mortalityRateByDate = [];

        foreach ($journalisations as $journal) {
            $date = Carbon::parse($journal->Date)->format('d M');
            if (!isset($mortalityRateByDate[$date])) {
                $mortalityRateByDate[$date] = 0;
            }
            $mortalityRateByDate[$date] += $journal->Sujet_Mort / $cheptelActuel * 100;
        }

        return $mortalityRateByDate;
    }

    private function replacePoulaillerIdsWithNames($data, $poulaillerNames)
    {
        $result = [];
        foreach ($data as $id => $value) {
            $name = $poulaillerNames[$id] ?? $id;
            $result[$name] = $value;
        }
        return $result;
    }

    private function replacePoulaillerIdsWithNamesInNestedArray($data, $poulaillerNames)
    {
        $result = [];
        foreach ($data as $id => $nestedArray) {
            $name = $poulaillerNames[$id] ?? $id;
            $result[$name] = $nestedArray;
        }
        return $result;
    }


    public function show($id)
    {
        $journalisation = Journalisation::find($id);
        return view('journalisations.show', compact('journalisation'));
    }

    public function edit($id)
    {
        $journalisation = Journalisation::find($id);
        return view('journalisations.edit', compact('journalisation'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'Date' => 'required|date',
            'Age' => 'required|integer',
            'Tri' => 'required|array',
            'Malade' => 'required|array',
            'Mort' => 'required|array',
            'Retour_malade' => 'required|array',
            'Resume' => 'required|string',
            'bande_id' => 'required|integer'
        ]);

        $journalisation = Journalisation::findOrFail($id);
        $bande = Bande::findOrFail($data['bande_id']);

        // Préparer les variables pour les nouveaux totaux
        $poulaillerEntries = explode(',', $bande->poulailler);
        $poulaillerData = [];
        $totalTri = 0;
        $totalMalade = 0;
        $totalMort = 0;
        $totalRetourMalade = 0;

        foreach ($poulaillerEntries as $index => $poulaillerEntry) {
            list($poulaillerId, $cheptel) = explode('*', $poulaillerEntry);
            $tri = $data['Tri'][$index];
            $malade = $data['Malade'][$index];
            $mort = $data['Mort'][$index];
            $retourMaladie = $data['Retour_malade'][$index];

            $poulaillerData[] = "$poulaillerId*$tri*$malade*$mort*$retourMaladie";

            $totalTri += $tri;
            $totalMalade += $malade;
            $totalMort += $mort;
            $totalRetourMalade += $retourMaladie;
        }

        // Calculer la différence de mortalité
        $diffMort = $totalMort - $journalisation->Sujet_Mort;

        // Mettre à jour la journalisation
        $journalisation->update([
            'Date' => $data['Date'],
            'Age' => $data['Age'],
            'Poulailler' => implode(',', $poulaillerData),
            'Sujet_Tri' => $totalTri,
            'Sujet_Malade' => $totalMalade,
            'Sujet_Mort' => $totalMort,
            'Sujet_retour_maladie' => $totalRetourMalade,
            'commentaire' => $data['Resume']
        ]);

        // Ajuster le cheptel actuel si le nombre de morts a changé
        $bande->cheptel_actuel -= $diffMort;
        $bande->save();

        // Flash pour l'onglet actif
        session()->flash('active_tab', 'journalisation');

        return response()->json(['success' => true, 'message' => 'Journal mise à jour avec succès']);
    }


    public function destroy($id)
    {
        $journalisation = Journalisation::find($id);
        $journalisation->delete();
        return response()->json(['success' => true, 'message' => 'Journal supprimée avec succès']);
    }
}

