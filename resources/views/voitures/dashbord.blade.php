@include('partials._head')
<style>
    .card {
        border-radius: 10px;
    }

    .card-footer {
        background-color: rgba(0, 0, 0, 0.05);
    }

    h2 {
        font-weight: bold;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }
    .table i,.table img {
    width: 24px;
    height: 24px;
    cursor: pointer;
}
</style>
@php
    use App\Models\Voiture;
    use App\Models\Maintenance;
    use App\Models\Assurance;
    use App\Models\Vignette;

    $voitures = Voiture::count();
    $maint = Maintenance::count();
    $assurance = Assurance::count();
    $vignette = Vignette::count();

@endphp


<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="main-wrapper">
        @include('partials._topbar')
        @include('partials._sidebar_collapsed')
        @include('partials._sidebar')
        <div class="page-wrapper">
            <div class="content">
                <div class="row">

                    <!-- Nombre de Vaches -->
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count">
                            <div class="dash-counts">
                                <h4>{{ $voitures }}</h4>
                                <h5>Nombre de Voitures</h5>
                            </div>
                            <div class="dash-imgs">
                                <!-- Utilisez une icône "cow" pour les vaches -->
                                <i data-feather="truck"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre de Bovins -->
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4>{{ $assurance }}</h4>
                                <h5>Nombre d'assurance</h5>
                            </div>
                            <div class="dash-imgs">
                                <!-- Utilisez une icône pour les animaux ou la ferme -->
                                <i data-feather="file-text"></i> <!-- ou 'truck' pour symboliser les bovins viande -->
                            </div>
                        </div>
                    </div>

                    <!-- Nombre de Laitières -->
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das2">
                            <div class="dash-counts">
                                <h4>{{ $maint }}</h4>
                                <h5>Nombres de Maintenance</h5>
                            </div>
                            <div class="dash-imgs">
                                <!-- Icône spécifique à la production de lait -->
                                <i data-feather="tool"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Production Laitière -->
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das3">
                            <div class="dash-counts">
                                <h4>{{ $vignette }}</h4>
                                <h5>Nombres de Vignette </h5>
                            </div>
                            <div class="dash-imgs">
                                <!-- Icône pour production ou stockage (lait) -->
                                <i data-feather="file"></i><!-- ou 'bar-chart-2' pour un graphique -->
                            </div>
                        </div>
                    </div>

                </div>
                <br>
                <div class="row">
                    <h3>Expire Bientôt:</h3>
                    <div class="card-body">
                        <h4 class="card-title">Assurance</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Voiture Associée</th>
                                        <th>Compagnie d'Assurance</th>
                                        <th>Date d'Activation</th>
                                        <th>Date d'Expiration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assurancesExpireBientot as $assurance)
                                    <td>{{ $assurance->voiture->plaque_immatriculation }} - {{ $assurance->voiture->modele }}</td>
                                    <td>{{ $assurance->assureur }}</td>
                                    <td>{{ $assurance->date_debut }}</td>
                                    <td>{{ $assurance->date_fin }}</td>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <br>

                    <div class="card-body">
                        <h4 class="card-title">Vignette</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Voiture Associée</th>
                                        <th>Date d'Acquisition</th>
                                        <th>Date d'Expiration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vignettesExpireBientot as $vignette)
                                        <tr>
                                            <td>{{ $vignette->voiture->plaque_immatriculation }} - {{ $vignette->voiture->modele }}</td>
                                            <td>{{ $vignette->date_obtention }}</td>
                                            <td>{{ $vignette->date_expiration }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <br>
                <div class="row">
                    <h3>Expire:</h3>
                    <div class="card-body">
                        <h4 class="card-title">Assurance</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
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

                    <br>

                    <div class="card-body">
                        <h4 class="card-title">Vignette</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
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
        </div>
    </div>
    @include('partials.script')
    <script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>
    <script src="{{ asset('assets/js/jspdf.umd.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>

</body>
