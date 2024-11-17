<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\Classification;
use App\Models\Poulailler;
use App\Models\RamassageOeuf;
use Illuminate\Http\Request;
use Carbon\Carbon;
class RamassageOeufController extends Controller
{
    public function index()
    {
        $ramassageOeufs = RamassageOeuf::all();
        return view('ramassageoeufs.index', compact('ramassageOeufs'));
    }

    public function create()
    {
        return view('ramassageoeufs.create');
    }

    public function store(Request $request)
    {
        $date = $request->input('Date');
        $typeR = $request->input('typeR');
        $commentaire = $request->input('Resume');
        $bande_id = $request->input('bande_id'); // Assurez-vous que cette variable est définie correctement
        $qteInput = $request->input('qte');
        $bande = Bande::find($bande_id);

        $ramassage = RamassageOeuf::where('Date', $date)->first();

        $poulaillerData = [];
        foreach ($qteInput as $poulaillerId => $qte) {
            if ($typeR == 1) {
                $poulaillerData[] = "$poulaillerId*$qte*0*0";
            } elseif ($typeR == 2) {
                if ($ramassage) {
                    $poulaillerInfos = explode(',', $ramassage->poulailler);
                    foreach ($poulaillerInfos as $info) {
                        list($id, $qte1, $qte2, $qte3) = explode('*', $info);
                        if ($id == $poulaillerId) {
                            $poulaillerData[] = "$id*$qte1*$qte*0";
                        }
                    }
                }
            } elseif ($typeR == 3) {
                if ($ramassage) {
                    $poulaillerInfos = explode(',', $ramassage->poulailler);
                    foreach ($poulaillerInfos as $info) {
                        list($id, $qte1, $qte2, $qte3) = explode('*', $info);
                        if ($id == $poulaillerId) {
                            $poulaillerData[] = "$id*$qte1*$qte2*$qte";
                        }
                    }
                }
            }
        }

        $totalOeufs = array_sum(array_map(function ($data) {
            list($id, $qte1, $qte2, $qte3) = explode('*', $data);
            return $qte1 + $qte2 + $qte3;
        }, $poulaillerData));

        $totalCheptel = array_sum(array_map(function ($poulailler) {
            list($id, $cheptel) = explode('*', $poulailler);
            return $cheptel;
        }, explode(',', $bande->poulailler)));

        $tauxPointe = ($totalOeufs / $totalCheptel) * 100;

        if ($ramassage) {
            $ramassage->update([
                'poulailler' => implode(',', $poulaillerData),
                'NumRamassage' => $typeR,
                'commentaire' => $commentaire,
                'Total' => $totalOeufs,
                'taux_pointe' => $tauxPointe,
                'bande_id' => $bande_id,
            ]);
        } else {
            RamassageOeuf::create([
                'Date' => $date,
                'poulailler' => implode(',', $poulaillerData),
                'NumRamassage' => $typeR,
                'commentaire' => $commentaire,
                'Total' => $totalOeufs,
                'taux_pointe' => $tauxPointe,
                'bande_id' => $bande_id,
            ]);
        }

        // Ajouter la quantité ramassée dans la table classification
        Classification::create([
            'OeufTotal' => $totalOeufs,
            'bande_id' => $bande_id,
            'date' => $date,
        ]);

        session()->flash('active_tab', 'ramassage-oeufs');

        return redirect()->route('operation', ['id' => $bande_id])->with('success', 'Ramassage enregistré avec succès.');
    }

    public function getPointeStatistics(Request $request)
    {
        $bandeId = $request->input('bande_id');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Filtrer les enregistrements de ramassage_oeufs
        $ramassages = RamassageOeuf::where('bande_id', $bandeId)
                                    ->whereBetween('Date', [$startDate, $endDate])
                                    ->get();

        $bande = Bande::find($bandeId);

        // Calculer les statistiques
        $pointeRate = $this->calculatePointeRate($ramassages, $bande->cheptel_actuel);
        $oeufsByPoulailler = $this->calculateOeufsByPoulailler($ramassages);
        $pointeRateByPoulailler = $this->calculatePointeRateByPoulailler($ramassages, $bande->cheptel_actuel);
        $pointeRateByDate = $this->calculatePointeRateByDate($ramassages);

        return response()->json([
            'Rate' => $pointeRate,
            'DonutChartData' => $oeufsByPoulailler,
            'LineChartData' => $pointeRateByPoulailler,
            'AreaChartData' => $pointeRateByDate,
            'Nombre'=>$ramassages->sum('Total'),
        ]);
    }

    private function calculatePointeRate($ramassages, $cheptelActuel)
    {
        $totalOeufs = $ramassages->sum('Total');
        return $totalOeufs / $cheptelActuel * 100;
    }

    private function calculateOeufsByPoulailler($ramassages)
    {
        $oeufsByPoulailler = [];

        foreach ($ramassages as $ramassage) {
            $poulaillerEntries = explode(',', $ramassage->poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($poulaillerId, $qte1, $qte2, $qte3) = explode('*', $entry);
                if (!isset($oeufsByPoulailler[$poulaillerId])) {
                    $oeufsByPoulailler[$poulaillerId] = 0;
                }
                $oeufsByPoulailler[$poulaillerId] += $qte1 + $qte2 + $qte3;
            }
        }

        $poulaillerNames = Poulailler::whereIn('id', array_keys($oeufsByPoulailler))->pluck('Denomination', 'id')->toArray();
        $oeufsByPoulaillerWithNames = [];
        foreach ($oeufsByPoulailler as $id => $totalOeufs) {
            $oeufsByPoulaillerWithNames[$poulaillerNames[$id]] = $totalOeufs;
        }

        return $oeufsByPoulaillerWithNames;
    }

    private function calculatePointeRateByPoulailler($ramassages, $cheptelActuel)
    {
        $pointeRateByPoulailler = [];

        foreach ($ramassages as $ramassage) {
            $date = Carbon::parse($ramassage->Date)->format('d M');
            $poulaillerEntries = explode(',', $ramassage->poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($poulaillerId, $qte1, $qte2, $qte3) = explode('*', $entry);
                if (!isset($pointeRateByPoulailler[$poulaillerId])) {
                    $pointeRateByPoulailler[$poulaillerId] = [];
                }
                if (!isset($pointeRateByPoulailler[$poulaillerId][$date])) {
                    $pointeRateByPoulailler[$poulaillerId][$date] = 0;
                }
                $totalOeufs = $qte1 + $qte2 + $qte3;
                $pointeRateByPoulailler[$poulaillerId][$date] += $totalOeufs / $cheptelActuel * 100;
            }
        }

        $poulaillerNames = Poulailler::whereIn('id', array_keys($pointeRateByPoulailler))->pluck('Denomination', 'id')->toArray();
        $pointeRateByPoulaillerWithNames = [];
        foreach ($pointeRateByPoulailler as $id => $rates) {
            $pointeRateByPoulaillerWithNames[$poulaillerNames[$id]] = $rates;
        }

        return $pointeRateByPoulaillerWithNames;
    }

    private function calculatePointeRateByDate($ramassages)
    {
        $pointeRateByDate = [];

        foreach ($ramassages as $ramassage) {
            $date = Carbon::parse($ramassage->Date)->format('d M');
            if (!isset($pointeRateByDate[$date])) {
                $pointeRateByDate[$date] = 0;
            }
            $pointeRateByDate[$date] += $ramassage->taux_pointe;
        }

        return $pointeRateByDate;
    }


    public function show($id)
    {
        $ramassageOeuf = RamassageOeuf::find($id);
        return view('ramassageoeufs.show', compact('ramassageOeuf'));
    }

    public function edit($id)
    {
        $ramassageOeuf = RamassageOeuf::find($id);
        return view('ramassageoeufs.edit', compact('ramassageOeuf'));
    }

    public function update(Request $request, $id)
    {
        $ramassageOeuf = RamassageOeuf::find($id);
        $ramassageOeuf->update($request->all());
        return redirect()->route('ramassageoeufs.index');
    }

    public function destroy($id)
    {
        $ramassageOeuf = RamassageOeuf::find($id);
        $ramassageOeuf->delete();
        return redirect()->route('ramassageoeufs.index');
    }
}

