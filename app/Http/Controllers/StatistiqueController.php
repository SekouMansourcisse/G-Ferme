<?php

namespace App\Http\Controllers;

use App\Models\Alimentation;
use App\Models\Bande;
use App\Models\CategorieOeuf;
use App\Models\Depense;
use App\Models\Journalisation;
use App\Models\Operation;
use App\Models\Parametre;
use App\Models\Pesage;
use App\Models\Produit;
use App\Models\RamassageOeuf;
use App\Models\Remboursement;
use App\Models\Traitement;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class StatistiqueController extends Controller
{
    public function index()
    {

    }
    public function DepenseRecette()
    {
        $settings = Parametre::first();
        return view('Statistique.RecetteDepense',compact('settings'));
    }
    public function getRecetteDepenseStatistics(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Récupérer les données pour les ventes
        $ventes = Operation::select('typevente', DB::raw('SUM(montant_payé) as total'))
            ->whereNotNull('typevente')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->groupBy('typevente')
            ->get();

        // Récupérer les données pour les remboursements des clients
        $remboursements = Remboursement::select(DB::raw('"remboursement" as type'), DB::raw('SUM(montant_paye) as total'))
            ->whereNotNull('client')
            ->whereBetween('date_r', [$startDate, $endDate])
            ->groupBy('type')
            ->get();

        // Combiner les résultats pour les ventes et les remboursements
        $result = $ventes->concat($remboursements);

        // Récupérer les données pour le donut chart
        $totalRavitaillement = DB::table('operation')
            ->whereIn('typeOperation', ['ravitaillement', 'acquisition bande'])
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->sum('montant_payé');

        $totalRedevance = DB::table('operation')
            ->whereIn('typeOperation', ['ravitaillement', 'acquisition bande'])
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->sum('montantDette');

        $totalPertes = DB::table('PerteProduitOeuf')
            ->whereBetween('date_p', [$startDate, $endDate])
            ->sum('montant_perdu');

        $totalDepenses = DB::table('depenses')
            ->whereBetween('Date_depense', [$startDate, $endDate])
            ->sum('Montant_paye');

        // Ajouter les nouvelles données au résultat
        $donutData = [
            ['typeS' => 'Total Ravitaillement', 'total' => $totalRavitaillement],
            ['typeS' => 'Total Redevance Fournisseur', 'total' => $totalRedevance],
            ['typeS' => 'Montant Total des Pertes', 'total' => $totalPertes],
            ['typeS' => 'Montant Total des Dépenses', 'total' => $totalDepenses],
        ];

        $result = $result->concat(collect($donutData));

        return response()->json($result);
    }
    public function getBestArticles(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Pour les ventes d'œufs
        $oeufs = Operation::whereNotNull('typevente')
            ->where('typevente', 'vente-oeuf')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->get()
            ->groupBy('infosOeuf')
            ->map(function ($group) {
                $categorieQuantite = explode('*', $group->first()->infosOeuf);

                // Vérification que $categorieQuantite a au moins deux éléments
                if (count($categorieQuantite) < 2) {
                    return [
                        'designation' => 'Inconnu',
                        'totalVentes' => 0,
                        'quantiteVendue' => 0
                    ];
                }

                $categorieOeuf = CategorieOeuf::find($categorieQuantite[0]);
                $nomCategorie = $categorieOeuf ? $categorieOeuf->Denomination : 'Inconnu';

                $totalVentes = $group->sum('montant_payé');
                $quantiteTotale = $group->sum(function ($operation) use ($categorieQuantite) {
                    return intval($categorieQuantite[1]);
                });

                return [
                    'designation' => $nomCategorie,
                    'totalVentes' => $totalVentes,
                    'quantiteVendue' => $quantiteTotale
                ];
            });


        // Pour les ventes de sujets
        $sujets = Operation::whereNotNull('typevente')
            ->where('typevente', 'vente-sujet')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->get()
            ->groupBy('sujetInfos')
            ->map(function ($group) {
                $bandeQuantite = explode('*', $group->first()->sujetInfos);

                if (count($bandeQuantite) < 2) {
                    return [
                        'designation' => 'Inconnu',
                        'totalVentes' => 0,
                        'quantiteVendue' => 0
                    ];
                }

                $bande = Bande::find($bandeQuantite[0]);
                $nomBande = $bande ? $bande->nom_bande : 'Inconnu';
                $nomBande = "Bande " . $nomBande;

                $totalVentes = $group->sum('montant_payé');
                $quantiteTotale = $group->sum(function ($operation) use ($bandeQuantite) {
                    return intval($bandeQuantite[1]);
                });

                return [
                    'designation' => $nomBande,
                    'totalVentes' => $totalVentes,
                    'quantiteVendue' => $quantiteTotale
                ];
            });


        // Pour les ventes d'autres produits
        $autres = Operation::whereNotNull('typevente')
            ->where('typevente', 'vente-autre')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->get()
            ->groupBy('AutresInfos')
            ->map(function ($group) {
                $produitQuantite = explode('*', $group->first()->AutresInfos);

                if (count($produitQuantite) < 2) {
                    return [
                        'designation' => 'Inconnu',
                        'totalVentes' => 0,
                        'quantiteVendue' => 0
                    ];
                }

                $produit = Produit::find($produitQuantite[0]);
                $nomProduit = $produit ? $produit->Denomination : 'Inconnu';

                $totalVentes = $group->sum('montant_payé');
                $quantiteTotale = $group->sum(function ($operation) use ($produitQuantite) {
                    return intval($produitQuantite[1]);
                });

                return [
                    'designation' => $nomProduit,
                    'totalVentes' => $totalVentes,
                    'quantiteVendue' => $quantiteTotale
                ];
            });


        // Fusionner les résultats et les trier par totalVentes
        $articles = $oeufs->concat($sujets)->concat($autres)->sortByDesc('totalVentes')->values();

        return response()->json($articles);
    }

    public function getBestClients(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Regrouper par client
        $clients = Operation::whereNotNull('typevente')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->get()
            ->groupBy('client')
            ->map(function ($group) {
                $totalVentes = $group->sum('Totalvente');
                $totalQuantite = $group->sum(function ($operation) {
                    $quantite = 0;

                    // Calculer la quantité en fonction du type de vente
                    if ($operation->typevente === 'vente-oeuf') {
                        $categorieQuantite = explode('*', $operation->infosOeuf);
                        if (count($categorieQuantite) >= 2) {
                            $quantite = intval($categorieQuantite[1]);
                        }
                    } elseif ($operation->typevente === 'vente-sujet') {
                        $bandeQuantite = explode('*', $operation->sujetInfos);
                        if (count($bandeQuantite) >= 2) {
                            $quantite = intval($bandeQuantite[1]);
                        }
                    } elseif ($operation->typevente === 'vente-autre') {
                        $produitQuantite = explode('*', $operation->AutresInfos);
                        if (count($produitQuantite) >= 2) {
                            $quantite = intval($produitQuantite[1]);
                        }
                    }

                    return $quantite;
                });

                $clientNom = $group->first()->NomPrenomClient;

                return [
                    'client' => $clientNom,
                    'totalVentes' => $totalVentes,
                    'totalQuantite' => $totalQuantite
                ];
            });

        // Trier les clients par totalVentes
        $bestClients = $clients->sortByDesc('totalVentes')->values();

        return response()->json($bestClients);
    }

    public function getStatferme()
    {
        $settings = Parametre::first();
        return view('Statistique.StatistiqueFerme',compact('settings'));
    }


    public function BestClient()
    {
        return view('Statistique.BestClient');
    }
    public function BestArticle()
    {
        return view('Statistique.BestArticle');
    }
    public function getAgeDetails($startDate)
    {
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
    public function getProductionReports()
    {
        // Récupérer toutes les bandes
        $bandes = Bande::all();
        $rapports = [];

        foreach ($bandes as $bande) {
            // Calcul de l'âge
            $ageDetails = $this->getAgeDetails($bande->date_demarrage);

            // Récupération des données de journalisation, alimentation, et pesage pour chaque bande
            $journalisation = Journalisation::where('bande_id', $bande->id)->get();
            $alimentations = Alimentation::where('bande_id', $bande->id)->get();
            $pesage = Pesage::where('bande_id', $bande->id)->get();

            // Calcul du taux de ponte ou de croissance selon le type d'élevage
            $tauxPointe = null;
            $totalOeuf = null;
            $tauxCroissance = null;
            $qteConsomme = null;
            $totalQuantite = 0;
            foreach ($alimentations as $alimentation) {
                // Split the poulailler data
                $poulaillerEntries = explode(',', $alimentation->Poulailler);

                // Loop through each entry and extract the quantity
                foreach ($poulaillerEntries as $entry) {
                    list($poulaillerId, $produitId, $quantite) = explode('*', $entry);
                    $totalQuantite += (float) $quantite;
                }
            }
            $qteConsomme = $totalQuantite;

            if ($bande->type_elevage === 'Poules pondeuses') {
                $totalOeufs = RamassageOeuf::where('bande_id', $bande->id)->sum('Total');
                $totalCheptel = array_sum(array_map(function ($poulailler) {
                    list($id, $cheptel) = explode('*', $poulailler);
                    return $cheptel;
                }, explode(',', $bande->poulailler)));

                $tauxPointe = ($totalOeufs / $totalCheptel) * 100;
                $totalOeuf = $totalOeufs;
            } else {
                $totalPoids = $pesage->sum(function ($p) {
                    $poulaillers = explode(',', $p->poulailler);
                    return array_sum(array_map(function ($poulailler) {
                        list($id, $qte, $cheptel, $poidTotal, $photo) = explode('*', $poulailler);
                        return $poidTotal;
                    }, $poulaillers));
                });

                $totalCheptel = array_sum(array_map(function ($poulailler) {
                    list($id, $cheptel) = explode('*', $poulailler);
                    return $cheptel;
                }, explode(',', $bande->poulailler)));

                $tauxCroissance = ($totalPoids / $totalCheptel);
            }

            // Ajouter les données de la bande au rapport
            $rapports[] = [
                'bande' => $bande,
                'journalisation' => $journalisation,
                'alimentation' => $alimentation,
                'pesage' => $pesage,
                'qte_c' => $qteConsomme,
                'oeufs' => $totalOeuf,
                'tauxPointe' => $tauxPointe,
                'tauxCroissance' => $tauxCroissance,
                'ageDetails' => $ageDetails,
            ];
        }

        $settings = Parametre::first();
        // Retourner les rapports pour toutes les bandes
        return view('Statistique.rapport_prod', [
            'rapports' => $rapports,
            'settings'=>$settings
        ]);
    }

    public function generateRapportFinancier(Request $request)
    {
        $startDate = $request->input('startDate', '2024-05-12');
        $endDate = $request->input('endDate', date('Y-m-d'));

        // Fetch necessary data
        $recette = $this->getRecette($startDate, $endDate);
        $charge = $this->getCharge($startDate, $endDate);
        $solde = $this->getSolde();

        // Consolidate data into a unique report
        $rapportFinancier = [
            'Oeufs_v' => $recette['oeufs_vendus'],
            'Sujet_v' => $recette['sujets_vendus'],
            'autres_v' => $recette['autres_ventes'],
            'ravitaillements' => $charge['ravitaillement'],
            'depenses' => $charge['depenses'],
            'redevance_fournisseur' => $solde['redevances_fournisseurs'],
            'dette_client' => $solde['dettes_clients']
        ];
        $settings = Parametre::first();
        return view('Statistique.rapport_fin', ['rapports' => $rapportFinancier , 'settings'=>$settings]);
    }

    public function getSolde()
    {
        $dettesClients = Operation::where('typeOperation', 'vente')
            ->where('montantDette', '>', 0)
            ->get(['client', 'montantDette']);

        $redevancesFournisseurs = Operation::where('typeOperation', '!=', 'vente')
            ->where('montantDette', '>', 0)
            ->get(['Fournisseur', 'montantDette']);

        return [
            'dettes_clients' => $dettesClients,
            'redevances_fournisseurs' => $redevancesFournisseurs
        ];
    }
    public function getCharge($startDate, $endDate)
    {
        // Group products by typeProduit and calculate total ravitaillement
        $produitsGrouped = Produit::select('typeProduit')
            ->groupBy('typeProduit')
            ->with([
                'operations' => function ($query) use ($startDate, $endDate) {
                    $query->where('typeOperation', 'ravitaillement')
                        ->whereBetween('Date_op', [$startDate, $endDate]);
                }
            ])
            ->get();

        $charges = [];
        foreach ($produitsGrouped as $produitGroup) {
            $totalRavitaillement = 0;
            foreach ($produitGroup->operations as $operation) {
                // Parse the Produit column to get quantities and prices
                $produitsDetails = explode(',', $operation->Produit);
                foreach ($produitsDetails as $details) {
                    list($produitId, $qte, $prixUnitaire, $prixTotal) = explode('*', $details);
                    // Sum up the total price of the products

                    $totalRavitaillement += $prixTotal;
                }
            }
            $charges[] = [
                'typeProduit' => $produitGroup->typeProduit,
                'totalRavitaillement' => $totalRavitaillement
            ];
        }

        // Group expenses by TypeDepense and calculate the total for each
        $depensesGrouped = Depense::select('TypeDepense_id')
            ->groupBy('TypeDepense_id')
            ->with('typedepense') // Assuming the relationship between Depense and TypeDepense exists
            ->whereBetween('Date_depense', [$startDate, $endDate])
            ->get();

        $depenses = [];
        foreach ($depensesGrouped as $depenseGroup) {
            $totalDepenses = Depense::where('TypeDepense_id', $depenseGroup->TypeDepense_id)
                ->whereBetween('Date_depense', [$startDate, $endDate])
                ->sum('Montant_d');

            $depenses[] = [
                'typeDepense' => $depenseGroup->typedepense->Denomination,
                'totalDepenses' => $totalDepenses
            ];
        }

        return [
            'ravitaillement' => $charges, // Grouped by typeProduit
            'depenses' => $depenses // Grouped by TypeDepense
        ];
    }
    public function getFermeStatRepport(Request $request)
    {
        // Récupération des dates de filtrage
        $startDate = $request->input('startDate', '2024-05-12');
        $endDate = $request->input('endDate', date('Y-m-d'));

        // Calcul de la redevance totale clients (ventes)
        $totalDetteClients = Operation::where('typeoperation', 'vente')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->sum('montantDette');

        // Calcul de la redevance totale fournisseurs (non-ventes)
        $totalDetteFournisseurs = Operation::where('typeoperation', '!=', 'vente')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->sum('montantDette');

        // Données pour les ventes d'œufs, sujets et autres
        $recettes = $this->getRecette($startDate, $endDate);

        // Récupération des ventes pour le graphique
        $ventesOeuf = Operation::where('typevente', 'vente-oeuf')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->selectRaw('DATE(Date_op) as date, SUM(Totalvente) as total')
            ->groupBy('date')
            ->get();

        $ventesSujets = Operation::where('typevente', 'vente-sujet')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->selectRaw('DATE(Date_op) as date, SUM(Totalvente) as total')
            ->groupBy('date')
            ->get();

        $ventesAutres = Operation::where('typevente', 'vente-autre')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->selectRaw('DATE(Date_op) as date, SUM(Totalvente) as total')
            ->groupBy('date')
            ->get();

        $ramassages = RamassageOeuf::whereBetween('Date', [$startDate, $endDate])
            ->selectRaw('DATE(Date) as date, SUM(Total) as total')
            ->groupBy('date')
            ->get();

        // Données pour le graphe Pesages : poids moyen par semaine
        $pesages = Pesage::whereBetween('date', [$startDate, $endDate])->get();

        $groupedPesages = $pesages->groupBy('semaine_p')->map(function ($items) {
            $totalQte = 0;
            $totalCount = 0;

            foreach ($items as $pesage) {
                foreach (explode(',', $pesage->poulailler) as $poulailler) {
                    list($id, $qte, $cheptel, $poidTotal, $photo) = explode('*', $poulailler);
                    $totalQte += $qte;  // Somme des quantités
                    $totalCount++;  // Compter le nombre d'entrées
                }
            }

            return $totalCount > 0 ? ($totalQte / $totalCount) : 0;  // Poids moyen par semaine
        });

        $pesageResults = $groupedPesages->map(function ($poidMoyen, $semaine_p) {
            return [
                'semaine_p' => $semaine_p,
                'poid_moyen' => $poidMoyen
            ];
        })->values()->toArray(); // Convert Collection to array



        $depenses = Depense::whereBetween('Date_depense', [$startDate, $endDate])
            ->selectRaw('DATE(Date_depense) as date, SUM(Montant_d) as montant_total')
            ->groupBy('date')
            ->get();
        // Retourner les données à la vue
        $result = [
            'totalDetteClients' => $totalDetteClients,
            'totalDetteFournisseurs' => $totalDetteFournisseurs,
            'recettes' => $recettes,
            'ventesOeuf' => $ventesOeuf,
            'ventesSujets' => $ventesSujets,
            'ventesAutres' => $ventesAutres,
            'ramassages' => $ramassages,       // Ajout
            'pesages' => $pesageResults,             // Ajout
            'depenses' => $depenses            // Ajout
        ];
        return response()->json($result);
    }
    public function getRecette($startDate, $endDate)
    {
        $totalOeufsVendus = Operation::where('typevente', 'vente-oeuf')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->sum('Totalvente');

        $totalSujetsVendus = Operation::where('typevente', 'vente-sujet')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->sum('Totalvente');

        $totalAutresVentes = Operation::where('typevente', 'vente-autre')
            ->whereBetween('Date_op', [$startDate, $endDate])
            ->sum('Totalvente');

        return [
            'oeufs_vendus' => $totalOeufsVendus,
            'sujets_vendus' => $totalSujetsVendus,
            'autres_ventes' => $totalAutresVentes
        ];
    }
    public function getBandAge(Request $request)
    {
        $fermeId = $request->input('fermeId', null);  // Optionally filter by farm
        $bands = Bande::select('nom_bande', 'date_demarrage', 'date_fin')->get();

        // Calculate age in days
        $bands = $bands->map(function ($band) {
            // If the band has an end date, calculate the difference from start to end
            if ($band->date_fin) {
                $age = Carbon::parse($band->date_demarrage)->diffInDays($band->date_fin);
            } else {
                // Otherwise, calculate the difference from start to now
                $age = Carbon::parse($band->date_demarrage)->diffInDays(now());
            }

            return [
                'bandName' => $band->nom_bande,
                'age' => $age
            ];
        });

        return response()->json($bands);
    }

    public function getBandPerformance(Request $request)
    {
        $fermeId = $request->input('fermeId', null); // Optionally filter by farm

        // Récupérer toutes les bandes, filtrer par ferme si nécessaire
        /*$bands = Bande::when($fermeId, function ($query) use ($fermeId) {
            return $query->where('ferme_id', $fermeId);
        })->get();*/
        $bands = Bande::all();

        // Pour stocker les performances de chaque bande
        $bandPerformances = [];

        foreach ($bands as $band) {
            $bandeId = $band->id;

            // Calcul du taux de mortalité
            $journalisations = Journalisation::where('bande_id', $bandeId)->get();
            $mortalityRate = $this->calculateMortalityRate($journalisations, $band->cheptel_actuel);

            // Calcul du taux de pointe (pour les poules pondeuses)
            $pointeRate = 0;
            if ($band->type_elevage === 'Poules pondeuses') {
                $ramassages = RamassageOeuf::where('bande_id', $bandeId)->get();
                $pointeRate = $this->calculatePointeRate($ramassages, $band->cheptel_actuel);
            }

            // Calcul du taux de croissance (pour les poulets de chair)
            $croissanceRate = 0;
            if ($band->type_elevage === 'Poulet de chair') {
                $pesages = Pesage::where('bande_id', $bandeId)->get();
                $croissanceRate = $this->calculateCroissanceRate($pesages);
            }

            // Ajouter les résultats à la liste
            $bandPerformances[] = [
                'bandName' => $band->nom_bande,
                'mortalite' => $mortalityRate,
                'pointeRate' => $pointeRate,
                'croissance' => $croissanceRate
            ];
        }

        return response()->json($bandPerformances);
    }

    private function calculateMortalityRate($journalisations, $cheptelActuel)
    {
        $totalMort = $journalisations->sum('Sujet_Mort');
        return $totalMort / $cheptelActuel * 100;
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
    private function calculatePointeRate($ramassages, $cheptelActuel)
    {
        $totalOeufs = $ramassages->sum('Total');
        return $totalOeufs / $cheptelActuel * 100;
    }




}
