<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\Pesage;
use App\Models\Poulailler;
use Illuminate\Http\Request;

class PesageController extends Controller
{
    public function index()
    {
        $pesages = Pesage::all();
        return view('pesages.index', compact('pesages'));
    }

    public function create()
    {
        return view('pesages.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $bande = Bande::find($data['bande_id']);

        $poulaillers_data = [];
        $total_qte = 0;

        foreach (explode(',', $bande->poulailler) as $index => $poulailler) {
            list($id, $cheptel) = explode('*', $poulailler);
            $poulailler_n = Poulailler::find($id)->Denomination;
            $qte = $data['poids_moyen'][$index];
            $photo = $request->file('photo')[$index]->store('photos', 'public'); // Sauvegarder la photo
            $poidTotal=$qte*$cheptel;
            $poulaillers_data[] = "$id*$qte*$cheptel*$poidTotal*$photo";
            $total_qte += $qte;
        }
        $poulaillerString = implode(',', $poulaillers_data);
        Pesage::create([
            'date' => $data['Date'],
            'semaine_p' => $data['SemaineP'],
            'poulailler' => $poulaillerString,
            'commentaire' => $data['Resume'],
            'bande_id' => $data['bande_id']
        ]);

        // Redirection vers la vue operation avec l'onglet 'pesage' actif
        return redirect()->route('operation', ['id' => $data['bande_id']])
                         ->with('active_tab', 'pesage');
    }
    public function getCroissanceStatistics(Request $request)
    {
        $bandeId = $request->input('bande_id');
        $startSemaine = $request->input('startSemaine');
        $endSemaine = $request->input('endSemaine');

        // Filtrer les enregistrements de pesages
        $pesages = Pesage::where('bande_id', $bandeId)
                         ->whereBetween('semaine_p', [$startSemaine, $endSemaine])
                         ->get();
        $bande = Bande::find($bandeId);
        // Calculer les statistiques
        $croissanceRate = $this->calculateCroissanceRate($pesages);
        $poidsByPoulailler = $this->calculatePoidsByPoulailler($pesages);
        $croissanceRateByPoulailler = $this->calculateCroissanceRateByPoulailler($pesages);
        $croissanceRateBySemaine = $this->calculateCroissanceRateBySemaine($pesages);

        return response()->json([
            'Rate' => $croissanceRate,
            'DonutChartData' => $poidsByPoulailler,
            'LineChartData' => $croissanceRateByPoulailler,
            'AreaChartData' => $croissanceRateBySemaine,
            'Nombre' => $pesages->sum('poids_total'), // Remplacez 'poids_total' par la colonne appropriée
        ]);
    }

    private function calculateCroissanceRate($pesages)
    {
        $totalPoids = 0;
        $totalCheptel = 0;

        foreach ($pesages as $pesage) {
            $poulaillerEntries = explode(',', $pesage->poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($id, $qte, $cheptel, $poidsTotal, $photo) = explode('*', $entry);
                $totalPoids += $poidsTotal;
                $totalCheptel += $cheptel;
            }
        }

        return $totalCheptel > 0 ? ($totalPoids / $totalCheptel) : 0;
    }

    private function calculatePoidsByPoulailler($pesages)
    {
        $poidsByPoulailler = [];

        foreach ($pesages as $pesage) {
            $poulaillerEntries = explode(',', $pesage->poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($id, $qte, $cheptel, $poidsTotal, $photo) = explode('*', $entry);
                if (!isset($poidsByPoulailler[$id])) {
                    $poidsByPoulailler[$id] = 0;
                }
                $poidsByPoulailler[$id] += $poidsTotal;
            }
        }

        $poulaillerNames = Poulailler::whereIn('id', array_keys($poidsByPoulailler))->pluck('Denomination', 'id')->toArray();
        $poidsByPoulaillerWithNames = [];
        foreach ($poidsByPoulailler as $id => $totalPoids) {
            $poidsByPoulaillerWithNames[$poulaillerNames[$id]] = $totalPoids;
        }

        return $poidsByPoulaillerWithNames;
    }

    private function calculateCroissanceRateByPoulailler($pesages)
    {
        $croissanceRateByPoulailler = [];

        foreach ($pesages as $pesage) {
            $semaine = $pesage->semaine_p;
            $poulaillerEntries = explode(',', $pesage->poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($id, $qte, $cheptel, $poidsTotal, $photo) = explode('*', $entry);
                if (!isset($croissanceRateByPoulailler[$id])) {
                    $croissanceRateByPoulailler[$id] = [];
                }
                if (!isset($croissanceRateByPoulailler[$id][$semaine])) {
                    $croissanceRateByPoulailler[$id][$semaine] = 0;
                }
                $croissanceRateByPoulailler[$id][$semaine] += $poidsTotal;
            }
        }

        $poulaillerNames = Poulailler::whereIn('id', array_keys($croissanceRateByPoulailler))->pluck('Denomination', 'id')->toArray();
        $croissanceRateByPoulaillerWithNames = [];
        foreach ($croissanceRateByPoulailler as $id => $rates) {
            $croissanceRateByPoulaillerWithNames[$poulaillerNames[$id]] = $rates;
        }

        return $croissanceRateByPoulaillerWithNames;
    }

    private function calculateCroissanceRateBySemaine($pesages)
    {
        $croissanceRateBySemaine = [];

        foreach ($pesages as $pesage) {
            $semaine = $pesage->semaine_p;
            if (!isset($croissanceRateBySemaine[$semaine])) {
                $croissanceRateBySemaine[$semaine] = 0;
            }
            $poulaillerEntries = explode(',', $pesage->poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($id, $qte, $cheptel, $poidsTotal, $photo) = explode('*', $entry);
                $croissanceRateBySemaine[$semaine] += $poidsTotal;
            }
        }

        return $croissanceRateBySemaine;
    }

    public function show($id)
    {
        $pesage = Pesage::find($id);
        return view('pesages.show', compact('pesage'));
    }

    public function edit($id)
    {
        $pesage = Pesage::find($id);
        return view('pesages.edit', compact('pesage'));
    }

    public function update(Request $request, $id)
    {


        // Récupérer l'enregistrement à mettre à jour
        $pesage = Pesage::findOrFail($id);
        $bande = Bande::findOrFail($pesage->bande_id); // Obtenir la bande associée

        // Traitement des données des poulaillers
        $poulaillers_data = [];
        $total_qte = 0;

        // Vérifier les poulaillers de la bande
        foreach (explode(',', $bande->poulailler) as $index => $poulailler) {
            list($idPoulailler, $cheptel) = explode('*', $poulailler);
            $poulailler_n = Poulailler::find($idPoulailler)->Denomination;
            $qte = $request->input('poids_moyen')[$index];

            // Vérifier si une nouvelle photo est téléchargée
            if ($request->hasFile('photo') && isset($request->file('photo')[$index])) {
                $photo = $request->file('photo')[$index]->store('photos', 'public'); // Sauvegarder la photo
            } else {
                $photo = ''; // Ou récupérer l'ancienne photo si nécessaire
            }

            // Calculer le poids total
            $poidTotal = $qte * $cheptel;
            $poulaillers_data[] = "$idPoulailler*$qte*$cheptel*$poidTotal*$photo";
            $total_qte += $qte;
        }

        // Convertir les données des poulaillers en chaîne
        $poulaillerString = implode(',', $poulaillers_data);

        // Mettre à jour les champs de l'enregistrement
        $pesage->date = $request->Date;
        $pesage->semaine_p = $request->SemaineP;
        $pesage->poulailler = $poulaillerString;
        $pesage->commentaire = $request->Resume;

        // Enregistrer les modifications
        $pesage->save();

        return response()->json(['success' => true, 'message' => 'Pesage mise à jour avec succès']);
    }



    public function destroy($id)
    {
        $pesage = Pesage::find($id);
        $pesage->delete();
        return response()->json(['success' => true, 'message' => 'Pesage supprimée avec succès']);
    }
}

