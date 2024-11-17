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
    use App\Models\Vache;
    use App\Models\VacheLaitiere;
    use App\Models\Bovin;

    // Comptage des vaches
    $vaches = Vache::count();

    // Comptage des bovins (type viande)
    $bovins = Vache::where('type_elevage', 'viande')->count();

    // Comptage des vaches laitières (type lait)
    $laitier = Vache::where('type_elevage', 'lait')->count();

    // Calcul de la production totale de lait (matin + soir)
    $lait = DB::table('vacheslaitieres')
        ->select(DB::raw('SUM(production_matin + production_soir) as total_production'))
        ->value('total_production'); // Utiliser value() au lieu de get() pour obtenir un seul résultat

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
                                <h4>{{ $vaches }}</h4>
                                <h5>Nombre de Vaches</h5>
                            </div>
                            <div class="dash-imgs">
                                <!-- Utilisez une icône "cow" pour les vaches -->
                                <i data-feather="gitlab"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre de Bovins -->
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4>{{ $bovins }}</h4>
                                <h5>Nombre de Bovins</h5>
                            </div>
                            <div class="dash-imgs">
                                <!-- Utilisez une icône pour les animaux ou la ferme -->
                                <i data-feather="layers"></i> <!-- ou 'truck' pour symboliser les bovins viande -->
                            </div>
                        </div>
                    </div>

                    <!-- Nombre de Laitières -->
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das2">
                            <div class="dash-counts">
                                <h4>{{ $laitier }}</h4>
                                <h5>Nombres de Laitières</h5>
                            </div>
                            <div class="dash-imgs">
                                <!-- Icône spécifique à la production de lait -->
                                <i data-feather="droplet"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Production Laitière -->
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das3">
                            <div class="dash-counts">
                                <h4>{{ $lait }} Litres</h4>
                                <h5>Production Laitière </h5>
                            </div>
                            <div class="dash-imgs">
                                <!-- Icône pour production ou stockage (lait) -->
                                <i data-feather="archive"></i> <!-- ou 'bar-chart-2' pour un graphique -->
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Formulaire de filtrage -->
                <div class="row mb-4" id="filtre">
                    <div class="col-md-12">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-2" id="startD">
                                    <input type="date" class="form-control" id="startDate" name="startDate">
                                </div>
                                <div class="col-md-2" id="endD">
                                    <input type="date" class="form-control" id="endDate" name="endDate"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary mt-1"
                                        onclick="fetchData()">Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Graphiques de production laitière et santé des vaches -->
                <div class="row">
                    <!-- Graphique: Production laitière quotidienne/mensuelle -->
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                Production Laitière (Journalière/Mensuelle)
                            </div>
                            <div class="card-body">
                                <canvas id="productionLaitiereChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                Consommation des vaches
                            </div>
                            <div class="card-body">
                                <canvas id="consommationChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Traitement programmée</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>N°Vache </th>
                                        <th>Date soin</th>
                                        <th>Denomination soin</th>
                                        <th>Description soin</th>
                                        <th>Produit </th>
                                        <th>statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($soins as $soin)
                                        <tr data-id="{{ $soin->id }}">
                                            <td>{{ $soin->vache->nom }}</td>
                                            <td>{{ $soin->date }}</td>
                                            <td>{{ $soin->denomination }}</td>
                                            <td>{{ $soin->description }}</td>
                                            <td>{{ $soin->Produit }}</td>
                                            <td>{{ $soin->etat == 1 ? 'en attente' : 'Effectué' }}</td>
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
    <script>
        // Fonction pour obtenir le premier jour du mois en cours
        function getFirstDayOfMonth() {
            var today = new Date();
            return new Date(today.getFullYear(), today.getMonth(), 1);
        }

        // Fonction pour obtenir le dernier jour du mois en cours
        function getLastDayOfMonth() {
            var today = new Date();
            return new Date(today.getFullYear(), today.getMonth() + 1, 0);
        }

        // Formatage de la date en 'YYYY-MM-DD'
        function formatDate(date) {
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            return date.getFullYear() + "-" + month + "-" + day;
        }

        // Appliquer les dates par défaut uniquement si elles ne sont pas définies
        document.addEventListener('DOMContentLoaded', function() {
            var startDateInput = document.getElementById('startDate');
            var endDateInput = document.getElementById('endDate');

            if (!startDateInput.value) {
                startDateInput.value = formatDate(getFirstDayOfMonth());
            }

            if (!endDateInput.value) {
                endDateInput.value = formatDate(getLastDayOfMonth());
            }
        });

// Fonction pour mettre à jour les graphiques avec les nouvelles données
function updateCharts(data) {
    // Mise à jour des labels (dates)
    productionLaitiereChart.data.labels = data.dates;
    consommationChart.data.labels = data.dates2;

    console.log(data.dates2);

    // Mise à jour des données de production laitière
    productionLaitiereChart.data.datasets[0].data = data.productionLaitiere;
    productionLaitiereChart.update();
console.log(data.consommationGlobale);
    // Mise à jour des données de consommation globale
    consommationChart.data.datasets[0].data = data.consommationGlobale;
    consommationChart.update();

}


// Récupérer les données via AJAX et mettre à jour les graphiques
function fetchData() {
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;
    $.ajax({
        url: '/data/charts', // Votre route Laravel
        method: 'GET',
        data: {
            date_1: startDate,
            date_2: endDate
        },
        success: function(response) {
            // Mettre à jour les graphiques avec les nouvelles données
            updateCharts(response);
        },
        error: function(xhr, status, error) {
            console.log('Erreur lors de la récupération des données:', error);
        }
    });
}

// Exécuter la récupération des données au chargement de la page
$(document).ready(function() {
    fetchData();
});

// Configuration initiale des graphiques
var ctx = document.getElementById('productionLaitiereChart').getContext('2d');
var productionLaitiereChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [], // Vides au départ, remplis via AJAX
        datasets: [{
            label: 'Production laitière (L)',
            data: [],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});



var ctx3 = document.getElementById('consommationChart').getContext('2d');
var consommationChart = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: [], // Vides au départ, remplis via AJAX
        datasets: [{
            label: 'Consommation (kg)',
            data: [],
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});



    </script>


</body>
