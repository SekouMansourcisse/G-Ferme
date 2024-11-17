@include('partials._head')
<style>

    .card {
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.1);
    }

    .card-body h5 {
        font-size: 1.2rem;
        color: #333;
    }

    .card-body p {
        color: #FF9F43;
    }

    .bande-card {
        border: 3px solid #f39c12;
        position: relative;
        padding: 10px;
        margin-bottom: 30px;
    }

    .bande-title {
        position: absolute;
        top: -20px;
        left: 20px;
        background-color: white;
        padding: 0 5px;
        z-index: 10;
        font-weight: bold;
    }

    .bande-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 5px solid #1b2850;
        z-index: -1;
        box-sizing: border-box;
    }
</style>
@php
    use App\Models\Client;
    use App\Models\Fournisseur;
    use App\Models\Depense;
    use App\Models\Operation;
    use App\Models\Ravitaillement;
    use App\Models\PerteProduitOEuf;
    use App\Models\Traitement;
    use App\Models\Assurance;
    use App\Models\Vignette;
    use Carbon\Carbon;

    // Pour les assurances qui sont déjà expirées
    $assurancesExpirees = Assurance::where('date_fin', '<', Carbon::now())->get();
    $soinvaches = Traitement::where('date', '>=', Carbon::now())->where('bande_id',null)->where('etat',1)
    ->get();
    $soinbandes = Traitement::where('date', '>=', Carbon::now())->where('vache_id',null)->where('etat',1)
    ->get();
    // Pour les vignettes qui sont déjà expirées
    $vignettesExpirees = Vignette::where('date_expiration', '<', Carbon::now())->get();
    $clients = Client::count();
    $fournisseurs = Fournisseur::count();
    $totalDepenses = Depense::sum('Montant_d');
    $factureD= Depense::count();
    $totalVentes = Operation::sum('Totalvente');
    $totalRavitaillement = Operation::where('typeOperation', '!=', 'vente')->sum('TotalRavitaillement');
    $factureV=Operation::where('typeOperation', '=', 'vente')->count();
    $totalPerte = PerteProduitOEuf::sum('montant_perdu');
@endphp

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="main-wrapper">
        @include('partials._topbar')

        @include('partials._sidebar')
        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget">
                            <div class="dash-widgetimg">
                                <span><img src="{{ asset('assets/img/icons/dash1.svg') }}" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>{{ number_format($totalDepenses, 2, ',', ' ') }} FCFA</h5>
                                <h6>Montant Total Dépenses</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash1">
                            <div class="dash-widgetimg">
                                <span><img src="{{ asset('assets/img/icons/dash2.svg') }}" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>{{ number_format($totalVentes, 2, ',', ' ') }} FCFA</h5>
                                <h6>Montant Total Ventes</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash2">
                            <div class="dash-widgetimg">
                                <span><img src="{{ asset('assets/img/icons/dash3.svg') }}" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>{{ number_format($totalRavitaillement, 2, ',', ' ') }} FCFA</h5>
                                <h6>Total Ravitaillement</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash3">
                            <div class="dash-widgetimg">
                                <span><img src="{{ asset('assets/img/icons/dash4.svg') }}" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>{{ number_format($totalPerte, 2, ',', ' ') }} FCFA</h5>
                                <h6>Total Perte</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count">
                            <div class="dash-counts">
                                <h4>{{ $clients }}</h4>
                                <h5>Clients</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4>{{ $fournisseurs }}</h4>
                                <h5>Fournisseurs</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das2">
                            <div class="dash-counts">
                                <h4>{{ $factureD }}</h4>
                                <h5>Facture de depenses</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das3">
                            <div class="dash-counts">
                                <h4>{{ $factureV }}</h4>
                                <h5>Facture de ventes</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 col-sm-12 col-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Evolution des ventes</h5>
                                <div class="graph-sets">
                                    <form id="filterForm">
                                        <div class="row">

                                            <div class="col-md-4" id="startD">
                                                <input type="date" class="form-control" id="startDate" name="startDate"
                                                    value="2024-05-12">
                                            </div>
                                            <div class="col-md-4" id="endD">
                                                <input type="date" class="form-control" id="endDate" name="endDate"
                                                    value="{{ date('Y-m-d') }}">
                                            </div>

                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-primary mt-1"
                                                    onclick="applyFilter()">Filtrer</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <div class="card bg-info mb-4">
                                    <div class="card-header" id="mainChartHeader">Redevance Total fournisseurs <p
                                            id="Nombre"></p>
                                    </div>
                                    <div class="card-body text-center">
                                        <h2 class="card-title" style="font-size: 3em; color: #FF6F61;" id="mainChartValue">
                                        </h2>

                                    </div>
                                </div>
                                <div class="card bg-secondary mb-4">
                                    <div class="card-header" id="mainChartHeader">Dette Total des clients <p id="Nombre">
                                        </p>
                                    </div>
                                    <div class="card-body text-center">
                                        <h2 class="card-title" style="font-size: 3em; color: #FF6F61;" id="mainChartValue">
                                        </h2>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bande-card">
                    <div class="bande-title" id="st">Situation activité</div>
                    <div class="row">
                        <br><br><br>
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Totale Oeufs Vendu</h5>
                                    <p id="oeuf_vendu"> XOF</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Totale Sujet Vendu</h5>
                                    <p id="sujet_vendu"> XOF</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Totale autres ventes</h5>
                                    <p id="autre_vente">XOF</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <p class="text-center" id="depense">Graphes des dépenses</p>

                            <div id="DepenseChart"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-6 d-flex">
                        <div class="card flex-fill default-cover w-100 mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Vignettes expiré</h4>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless best-seller">
                                        <thead>
                                            <tr>
                                                <th>Voiture Associée</th>
                                                <th>Date d'Acquisition</th>
                                                <th>Date d'Expiration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($vignettesExpirees as $vignette)
                                                <tr>
                                                    <td>{{ $vignette->voiture->marque_modele }}</td>
                                                    <td>{{ $vignette->date_acquisition }}</td>
                                                    <td>{{ $vignette->date_expiration }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-6 d-flex">
                        <div class="card flex-fill default-cover w-100 mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Assurance expiré</h4>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless recent-transactions">
                                        <thead>
                                            <tr>
                                                <th>Voiture Associée</th>
                                                <th>Compagnie d'Assurance</th>
                                                <th>Date d'Activation</th>
                                                <th>Date d'Expiration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assurancesExpirees as $assurance)
                                                <tr>
                                                    <td>{{ $assurance->voiture->plaque_immatriculation }} - {{ $assurance->voiture->modele }}</td>
                                                    <td>{{ $assurance->assureur }}</td>
                                                    <td>{{ $assurance->date_debut }}</td>
                                                    <td>{{ $assurance->date_fin }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-6 d-flex">
                        <div class="card flex-fill default-cover w-100 mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Soin des vaches en attente</h4>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless best-seller">
                                        <thead>
                                            <tr>
                                                <th>N°Vache </th>
                                                <th>Date soin</th>
                                                <th>Denomination soin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($soinvaches  as $soin)
                                                <tr>
                                                    <td>{{ $soin->vache->nom }}</td>
                                                    <td>{{ $soin->date }}</td>
                                                    <td>{{ $soin->denomination }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-6 d-flex">
                        <div class="card flex-fill default-cover w-100 mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Soin bande en attente</h4>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless recent-transactions">
                                        <thead>
                                            <tr>
                                                <th>Nom bande </th>
                                                <th>Date soin</th>
                                                <th>Denomination soin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($soinbandes as $soin)
                                                <tr>
                                                    <td>{{ $soin->bande->nom_bande }}</td>
                                                    <td>{{ $soin->date }}</td>
                                                    <td>{{ $soin->denomination }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('partials.script')

    <script src="{{ asset('js/backend/statistique.js')}}"></script>
</body>
