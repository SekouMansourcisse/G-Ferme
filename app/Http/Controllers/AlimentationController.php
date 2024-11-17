<?php

namespace App\Http\Controllers;

use App\Models\Alimentation;
use App\Models\Bande;
use App\Models\Poulailler;
use App\Models\Produit;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;

class AlimentationController extends Controller
{
    public function index()
    {
        $alimentations = Alimentation::all();
        return view('alimentations.index', compact('alimentations'));
    }

    public function create()
    {
        return view('alimentations.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Calcul de l'âge en jours basé sur la date de la bande et la date actuelle
        $bande = Bande::find($data['bande_id']);

        $ageEnJour = $request->input('Age');

        // Préparation des données pour la colonne 'poulailler'
        $poulaillerData = [];
        foreach (explode(',', $bande->poulailler) as $poulailler) {
            list($poulaillerId, $cheptel) = explode('*', $poulailler);
            foreach ($data['qte_consomme'] as $produitId => $quantites) {
                if (isset($quantites[$poulaillerId])) {
                    $poulaillerData[] = "$poulaillerId*$produitId*{$quantites[$poulaillerId]}";

                    // Mettre à jour le stock du produit
                    $produit = Produit::find($produitId);
                    if ($produit) {
                        $produit->qte_stock -= $quantites[$poulaillerId];
                        $produit->save();
                    }
                }
            }
        }
        $poulaillerString = implode(',', $poulaillerData);

        // Création de la nouvelle entrée
        Alimentation::create([
            'Age_en_jour' => $ageEnJour,
            'Poulailler' => $poulaillerString,
            'commentaire' => $data['Resume'],
            'bande_id' => $data['bande_id'],
            'date' => $data['Date'],
        ]);

        // Rediriger vers la vue 'operation' avec l'onglet 'ramassagelitiere' actif
        session()->flash('active_tab', 'alimentation');

        return redirect()->route('operation', ['id' => $bande->id])->with('success', 'Alimentation effectuée avec succès.');
    }
    public function getConsommationStatistics(Request $request)
    {
        $bandeId = $request->input('bande_id');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Filtrer les enregistrements d'alimentations
        $alimentations = Alimentation::where('bande_id', $bandeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $bande = Bande::find($bandeId);

        // Calculer les statistiques
        $consommationRate = $this->calculateConsommationRate($alimentations, $bande->cheptel_actuel);
        $consommationByPoulailler = $this->calculateConsommationByPoulailler($alimentations);
        $consommationRateByPoulailler = $this->calculateConsommationRateByPoulailler($alimentations, $bande->cheptel_actuel);
        $consommationRateByDate = $this->calculateConsommationRateByDate($alimentations);

        return response()->json([
            'Rate' => $consommationRate,
            'DonutChartData' => $consommationByPoulailler,
            'LineChartData' => $consommationRateByPoulailler,
            'AreaChartData' => $consommationRateByDate,
            'Nombre' => $alimentations->sum('Total'),
        ]);
    }
    private function calculateConsommationRate($alimentations, $cheptelActuel)
    {
        $totalConsommation = 0;
        foreach ($alimentations as $alimentation) {
            $poulaillerEntries = explode(',', $alimentation->Poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($poulaillerId, $produitId, $qte) = explode('*', $entry);
                $totalConsommation += $qte;
            }
        }
        return $totalConsommation;
    }

    private function calculateConsommationByPoulailler($alimentations)
    {
        $consommationByPoulailler = [];

        foreach ($alimentations as $alimentation) {
            $poulaillerEntries = explode(',', $alimentation->Poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($poulaillerId, $produitId, $qte) = explode('*', $entry);
                if (!isset($consommationByPoulailler[$poulaillerId])) {
                    $consommationByPoulailler[$poulaillerId] = 0;
                }
                $consommationByPoulailler[$poulaillerId] += $qte;
            }
        }

        $poulaillerNames = Poulailler::whereIn('id', array_keys($consommationByPoulailler))->pluck('Denomination', 'id')->toArray();
        $consommationByPoulaillerWithNames = [];
        foreach ($consommationByPoulailler as $id => $totalConsommation) {
            $consommationByPoulaillerWithNames[$poulaillerNames[$id]] = $totalConsommation;
        }

        return $consommationByPoulaillerWithNames;
    }

    private function calculateConsommationRateByPoulailler($alimentations, $cheptelActuel)
    {
        $consommationRateByPoulailler = [];

        foreach ($alimentations as $alimentation) {
            $date = Carbon::parse($alimentation->date)->format('d M');
            $poulaillerEntries = explode(',', $alimentation->Poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($poulaillerId, $produitId, $qte) = explode('*', $entry);
                if (!isset($consommationRateByPoulailler[$poulaillerId])) {
                    $consommationRateByPoulailler[$poulaillerId] = [];
                }
                if (!isset($consommationRateByPoulailler[$poulaillerId][$date])) {
                    $consommationRateByPoulailler[$poulaillerId][$date] = 0;
                }
                $consommationRateByPoulailler[$poulaillerId][$date] += $qte;
            }
        }

        $poulaillerNames = Poulailler::whereIn('id', array_keys($consommationRateByPoulailler))->pluck('Denomination', 'id')->toArray();
        $consommationRateByPoulaillerWithNames = [];
        foreach ($consommationRateByPoulailler as $id => $rates) {
            $consommationRateByPoulaillerWithNames[$poulaillerNames[$id]] = $rates;
        }

        return $consommationRateByPoulaillerWithNames;
    }
    private function calculateConsommationRateByDate($alimentations)
    {
        $consommationRateByDate = [];

        foreach ($alimentations as $alimentation) {
            $date = Carbon::parse($alimentation->date)->format('d M');
            if (!isset($consommationRateByDate[$date])) {
                $consommationRateByDate[$date] = 0;
            }
            $poulaillerEntries = explode(',', $alimentation->Poulailler);
            foreach ($poulaillerEntries as $entry) {
                list($poulaillerId, $produitId, $qte) = explode('*', $entry);
                $consommationRateByDate[$date] += $qte;
            }
        }

        return $consommationRateByDate;
    }
    public function show($id)
    {
        $alimentation = Alimentation::find($id);
        return view('alimentations.show', compact('alimentation'));
    }

    public function edit($id)
    {
        $alimentation = Alimentation::find($id);
        return view('alimentations.edit', compact('alimentation'));
    }

    public function update(Request $request, $id)
    {
        // Valider les données reçues
        $request->validate([
            'Age' => 'required|integer',
            'Resume' => 'nullable|string',
            'Date' => 'required|date',
            'bande_id' => 'required|exists:bande,id',
            'qte_consomme' => 'array',
            'qte_consomme.*' => 'required|integer|min:0'
        ]);

        Log::info($request->all());

        // Récupérer l'alimentation à mettre à jour
        $alimentation = Alimentation::findOrFail($id);
        Log::info($alimentation);

        // Démarrer une transaction pour garantir la cohérence des données
        DB::beginTransaction();

        try {
            // Mettre à jour les champs principaux
            $alimentation->update([
                'Age_en_jour' => $request->input('Age'),
                'commentaire' => $request->input('Resume'),
                'date' => $request->input('Date'),
                'bande_id' => $request->input('bande_id'),
            ]);

            // Initialiser la liste pour stocker les informations de poulailler
            $poulaillerData = [];

            // Parcourir les quantités consommées pour chaque produit
            foreach ($request->input('qte_consomme') as $produitId => $quantite) {
                // Puisque chaque produit est associé à un seul poulailler ici, définissez un poulaillerId par défaut
                $poulaillerId = 1; // Remplacez par l'ID correct si nécessaire

                // Construire la chaîne pour le champ poulailler
                $poulaillerData[] = "$poulaillerId*$produitId*$quantite";

                // Mettre à jour le stock du produit
                $produit = Produit::find($produitId);
                if ($produit) {
                    // Ajuster la quantité en stock
                    $produit->qte_stock -= $quantite;
                    $produit->save();
                }
            }

            // Mettre à jour le champ `Poulailler` dans la table `Alimentation`
            $alimentation->Poulailler = implode(',', $poulaillerData);
            $alimentation->save();

            // Valider les modifications en base de données
            DB::commit();
            session()->flash('active_tab', 'alimentation');
            return response()->json(['success' => true, 'message' => 'Alimentation mise à jour avec succès']);
        } catch (\Exception $e) {
            // Annuler toutes les modifications en cas d'échec
            DB::rollBack();
            return redirect()->back()->withErrors('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }




    public function destroy($id)
    {
        $alimentation = Alimentation::findOrFail($id);
        $alimentation->delete();

        return response()->json(['success' => true]);
    }

}

