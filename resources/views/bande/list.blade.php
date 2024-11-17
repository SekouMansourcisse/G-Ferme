@include('partials._head')
<style>
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table i,
    .table img {
        width: 24px;
        height: 24px;
        cursor: pointer;
    }

    .pagination-wrapper button {
        margin: 2px;
        padding: 5px 10px;
        cursor: pointer;
    }

    .pagination-wrapper .active {
        font-weight: bold;
        background-color: #ff9f43;
        color: white;
    }
</style>

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
                <div class="page-header">
                    <div class="page-title">
                        <h4>Liste des bandes</h4>
                    </div>
                </div>

                @if (session('success'))
                    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card-body">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item">
                            <a class="nav-link {{ session('active_tab', 'encour') == 'encour' ? 'active' : '' }}"
                                href="#encour" data-bs-toggle="tab">Cycle en cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ session('active_tab') == 'cloturé' ? 'active' : '' }}" href="#cloturé"
                                data-bs-toggle="tab">Cycle clôturé</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Onglet Non Payé -->
                        <div class="tab-pane {{ session('active_tab', 'encour') == 'encour' ? 'show active' : '' }}"
                            id="encour">

                            <div class="card">
                                <div class="card-body">
                                    <div class="page-header">
                                        <div class="page-title" id="titre">
                                            <h4>Bande en cour</h4>
                                            <h6>Liste Cycle en cour</h6>
                                        </div>
                                    </div>
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search">
                                                    <img src="assets/img/icons/filter.svg" alt="img">
                                                    <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                                                </a>
                                            </div>
                                            <div class="search-input">
                                                <a class="btn btn-searchset"><img
                                                        src="assets/img/icons/search-white.svg" alt="img"></a>
                                            </div>
                                        </div>
                                        <div class="wordset">
                                            <ul>
                                                <li>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="pdf"><img src="assets/img/icons/pdf.svg"
                                                            alt="img"></a>
                                                </li>
                                                <li>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="excel"><img src="assets/img/icons/excel.svg"
                                                            alt="img"></a>
                                                </li>
                                                <li>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="print"><img src="assets/img/icons/printer.svg"
                                                            alt="img"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                        <div class="card" id="filter_inputs">
                            <div class="card-body pb-0">
                                <form action="#" class="dropdown">
                                    <div class="searchinputs" id="dropdownMenuClickable"
                                        data-bs-auto-close="false">
                                        <input type="text" placeholder="Search">
                                        <div class="search-addon">
                                            <span><i data-feather="x-circle" class="feather-14"></i></span>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Informations sur la bande</th>
                                                    <th>Nombre de sujet actuel</th>
                                                    <th>Info poulailler</th>

                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bandesTable">
                                                @foreach ($bandes as $bande)
                                                    @if ($bande->etat == 1)
                                                        <tr data-id="{{ $bande->id }}">
                                                            <td>
                                                                <p><b>Date arrivée</b>: {{ $bande->date_demarrage }}</p>
                                                                <p><b>Âge arrivée</b>: {{ $bande->age_arrive }}</p>
                                                                <p><b>Effectif arrivée</b>: {{ $bande->cheptel_depart }}
                                                                </p>
                                                                <p><b>Nom</b>: {{ $bande->nom_bande }}</p>
                                                                <p><b>Elevage</b>: {{ $bande->type_elevage }}</p>
                                                                <p><b>Résumé</b>: {{ $bande->observation }}</p>
                                                            </td>
                                                            <td>
                                                                <p><b>Âge actuel</b>:
                                                                    @if ($bande->ageDetails['months'] >= 1)
                                                                        {{ $bande->ageDetails['months'] }} mois
                                                                        @if ($bande->ageDetails['weeks'] >= 1)
                                                                            , {{ $bande->ageDetails['weeks'] }}
                                                                            semaines
                                                                        @endif
                                                                        @if ($bande->ageDetails['days'] >= 1)
                                                                            , {{ $bande->ageDetails['days'] }} jours
                                                                        @endif
                                                                    @elseif ($bande->ageDetails['weeks'] >= 1)
                                                                        {{ $bande->ageDetails['weeks'] }} semaines
                                                                        @if ($bande->ageDetails['days'] >= 1)
                                                                            , {{ $bande->ageDetails['days'] }} jours
                                                                        @endif
                                                                    @else
                                                                        {{ $bande->ageDetails['days'] }} jours
                                                                    @endif
                                                                </p>
                                                                <p><b>Nombre décès</b>: {{ $bande->totalDeaths }}</p>
                                                                <p><b>Nombre malades</b>: {{ $bande->totalSick }}</p>
                                                                <p><b>Nombre vendus</b>:{{ $bande->qte_vendu }} </p>
                                                                <p><b>Nombre actuel</b>: {{ $bande->cheptel_actuel }}
                                                                </p>
                                                                <p><b>Traitement imminent</b>: </p>
                                                            </td>
                                                            <td>
                                                                <p>{!! app('App\Http\Controllers\BandeController')->getPoulaillerInfo2($bande->poulailler) !!}</p>
                                                            </td>

                                                            <td>
                                                                @can('edit Cycle Production')
                                                                <a class="me-3 edit-item-btn"
                                                                href="{{ route('bandes.edit', $bande->id) }}"
                                                                id="edit-item-btn">
                                                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                                                            </a>
                                                                @endcan

                                                                @can('delete Cycle Production')
                                                                <a class="me-3 delete-item-btn"
                                                                href="javascript:void(0);" id="delete-item-btn">
                                                                <img src="assets/img/icons/delete.svg"
                                                                    alt="delete">
                                                            </a>
                                                                @endcan

                                                                <a class="me-3 custom-icon-2"
                                                                    href="javascript:void(0);" id="custom-icon-2">
                                                                    <img src="assets/img/icons/close-circle1.svg"
                                                                        alt="custom2">
                                                                </a>
                                                                <a class="me-3 custom-icon-1"
                                                                    href="{{ url('bandeOperation/' . $bande->id) }}"
                                                                    id="custom-icon-1">
                                                                    <img src="assets/img/icons/settings.svg"
                                                                        alt="custom1">
                                                                </a>
                                                                <a class="me-3 custom-icon-2"
                                                                    href="{{ url('bandeStat/' . $bande->id) }}"
                                                                    id="custom-icon-2">
                                                                    <i data-feather="bar-chart-2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                @endforeach
                                            </tbody>
                                        </table>
                                        <!-- Pagination Controls -->
                                        <div id="paginationControls" class="pagination-wrapper"></div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Onglet Payé -->
                        <div class="tab-pane {{ session('active_tab') == 'cloturé' ? 'show active' : '' }}"
                            id="cloturé">
                            <div class="card">
                                <div class="card-body">
                                    <div class="page-header">
                                        <div class="page-title" id="titre">
                                            <h4>Bande cloturé</h4>
                                            <h6>Liste des Bandes cloturés</h6>
                                        </div>
                                    </div>
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search">
                                                    <img src="{{ asset('assets/img/icons/filter.svg') }}" alt="img">
                                                    <span><img src="{{ asset('assets/img/icons/closes.svg') }}" alt="img"></span>
                                                </a>
                                            </div>
                                            <div class="search-input">
                                                <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}"
                                                        alt="img"></a>
                                            </div>
                                        </div>
                                        <div class="wordset">
                                            <ul>
                                                <li>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top"
                                                        href="" title="pdf"><img
                                                            src="{{ asset('assets/img/icons/pdf.svg') }}" alt="img"></a>
                                                </li>
                                                <li>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                            src="{{ asset('assets/img/icons/excel.svg') }}" alt="img"></a>
                                                </li>
                                                <li>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                            src="{{ asset('assets/img/icons/printer.svg') }}" alt="img"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                        <div class="card" id="filter_inputs">
                            <div class="card-body pb-0">
                                <form action="#" class="dropdown">
                                    <div class="searchinputs" id="dropdownMenuClickable"
                                        data-bs-auto-close="false">
                                        <input type="text" placeholder="Search">
                                        <div class="search-addon">
                                            <span><i data-feather="x-circle" class="feather-14"></i></span>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Informations sur la bande</th>
                                                    <th>Nombre de sujet actuel</th>
                                                    <th>Info poulailler</th>

                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bandesTable2">
                                                @foreach ($bandes as $bande)
                                                    @if ($bande->etat == 2)
                                                        <tr data-id="{{ $bande->id }}">
                                                            <td>
                                                                <p><b>Date arrivée</b>: {{ $bande->date_demarrage }}
                                                                </p>
                                                                <p><b>Âge arrivée</b>: {{ $bande->age_arrive }}</p>
                                                                <p><b>Effectif arrivée</b>:
                                                                    {{ $bande->cheptel_depart }}</p>
                                                                <p><b>Nom</b>: {{ $bande->nom_bande }}</p>
                                                                <p><b>Elevage</b>: {{ $bande->type_elevage }}</p>
                                                                <p><b>Résumé</b>: {{ $bande->observation }}</p>
                                                            </td>
                                                            <td>
                                                                <p><b>Âge actuel</b>:
                                                                    @if ($bande->ageDetails['months'] >= 1)
                                                                        {{ $bande->ageDetails['months'] }} mois
                                                                        @if ($bande->ageDetails['weeks'] >= 1)
                                                                            , {{ $bande->ageDetails['weeks'] }}
                                                                            semaines
                                                                        @endif
                                                                        @if ($bande->ageDetails['days'] >= 1)
                                                                            , {{ $bande->ageDetails['days'] }} jours
                                                                        @endif
                                                                    @elseif ($bande->ageDetails['weeks'] >= 1)
                                                                        {{ $bande->ageDetails['weeks'] }} semaines
                                                                        @if ($bande->ageDetails['days'] >= 1)
                                                                            , {{ $bande->ageDetails['days'] }} jours
                                                                        @endif
                                                                    @else
                                                                        {{ $bande->ageDetails['days'] }} jours
                                                                    @endif
                                                                </p>
                                                                <p><b>Nombre décès</b>: {{ $bande->totalDeaths }}</p>
                                                                <p><b>Nombre malades</b>: {{ $bande->totalSick }}</p>
                                                                <p><b>Nombre vendus</b>:{{ $bande->qte_vendu }} </p>
                                                                <p><b>Nombre actuel</b>: {{ $bande->cheptel_actuel }}
                                                                </p>
                                                                <p><b>Traitement imminent</b>: </p>
                                                            </td>
                                                            <td>
                                                                <p>{!! app('App\Http\Controllers\BandeController')->getPoulaillerInfo2($bande->poulailler) !!}</p>
                                                            </td>

                                                            <td>

                                                                @can('delete Cycle Production')
                                                                <a class="me-3 delete-item-btn"
                                                                href="javascript:void(0);" id="delete-item-btn">
                                                                <img src="assets/img/icons/delete.svg"
                                                                    alt="delete">
                                                            </a>
                                                                @endcan
                                                                @can('read operation sur bande')
                                                                <a class="me-3 custom-icon-1"
                                                                href="{{ url('bandeOperation/' . $bande->id) }}"
                                                                id="custom-icon-1">
                                                                <img src="assets/img/icons/settings.svg"
                                                                    alt="custom1">
                                                            </a>
                                                                @endcan

                                                                <a class="me-3 custom-icon-2"
                                                                href="{{ url('bandeStat/' . $bande->id) }}"
                                                                id="custom-icon-2">
                                                                <i data-feather="bar-chart-2"></i>
                                                            </a>


                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <!-- Pagination Controls -->
                                        <div id="paginationControls2" class="pagination-wrapper"></div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials.script')

    <script src="{{ asset('assets/version/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowsPerPage = 5; // Nombre de lignes par page
            let tableBody = document.getElementById('bandesTable');
            let rows = tableBody.getElementsByTagName('tr');
            let paginationControls = document.getElementById('paginationControls');
            let totalRows = rows.length;
            let totalPages = Math.ceil(totalRows / rowsPerPage);
            let currentPage = 1;

            // Fonction pour afficher une page
            function displayPage(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                // Masquer toutes les lignes
                for (let i = 0; i < totalRows; i++) {
                    rows[i].style.display = 'none';
                }

                // Afficher les lignes pour la page en cours
                for (let i = start; i < end && i < totalRows; i++) {
                    rows[i].style.display = '';
                }

                // Mettre à jour les boutons de pagination
                updatePaginationControls(page);
            }

            // Fonction pour créer les boutons de pagination
            function updatePaginationControls(page) {
                paginationControls.innerHTML = '';

                // Ajouter le bouton "Précédent"
                if (page > 1) {
                    let prevButton = document.createElement('button');
                    prevButton.innerText = 'Précédent';
                    prevButton.addEventListener('click', function() {
                        displayPage(page - 1);
                    });
                    paginationControls.appendChild(prevButton);
                }

                // Ajouter les boutons pour chaque page
                for (let i = 1; i <= totalPages; i++) {
                    let pageButton = document.createElement('button');
                    pageButton.innerText = i;
                    if (i === page) {
                        pageButton.classList.add('active');
                    }
                    pageButton.addEventListener('click', function() {
                        displayPage(i);
                    });
                    paginationControls.appendChild(pageButton);
                }

                // Ajouter le bouton "Suivant"
                if (page < totalPages) {
                    let nextButton = document.createElement('button');
                    nextButton.innerText = 'Suivant';
                    nextButton.addEventListener('click', function() {
                        displayPage(page + 1);
                    });
                    paginationControls.appendChild(nextButton);
                }
            }

            // Afficher la première page au chargement
            displayPage(currentPage);
        });
        document.addEventListener('DOMContentLoaded', function() {
            let rowsPerPage = 5; // Nombre de lignes par page
            let tableBody = document.getElementById('bandesTable2');
            let rows = tableBody.getElementsByTagName('tr');
            let paginationControls = document.getElementById('paginationControls2');
            let totalRows = rows.length;
            let totalPages = Math.ceil(totalRows / rowsPerPage);
            let currentPage = 1;

            // Fonction pour afficher une page
            function displayPage(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                // Masquer toutes les lignes
                for (let i = 0; i < totalRows; i++) {
                    rows[i].style.display = 'none';
                }

                // Afficher les lignes pour la page en cours
                for (let i = start; i < end && i < totalRows; i++) {
                    rows[i].style.display = '';
                }

                // Mettre à jour les boutons de pagination
                updatePaginationControls(page);
            }

            // Fonction pour créer les boutons de pagination
            function updatePaginationControls(page) {
                paginationControls.innerHTML = '';

                // Ajouter le bouton "Précédent"
                if (page > 1) {
                    let prevButton = document.createElement('button');
                    prevButton.innerText = 'Précédent';
                    prevButton.addEventListener('click', function() {
                        displayPage(page - 1);
                    });
                    paginationControls.appendChild(prevButton);
                }

                // Ajouter les boutons pour chaque page
                for (let i = 1; i <= totalPages; i++) {
                    let pageButton = document.createElement('button');
                    pageButton.innerText = i;
                    if (i === page) {
                        pageButton.classList.add('active');
                    }
                    pageButton.addEventListener('click', function() {
                        displayPage(i);
                    });
                    paginationControls.appendChild(pageButton);
                }

                // Ajouter le bouton "Suivant"
                if (page < totalPages) {
                    let nextButton = document.createElement('button');
                    nextButton.innerText = 'Suivant';
                    nextButton.addEventListener('click', function() {
                        displayPage(page + 1);
                    });
                    paginationControls.appendChild(nextButton);
                }
            }

            // Afficher la première page au chargement
            displayPage(currentPage);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sélectionner tous les éléments ayant l'ID 'custom-icon-2'
            $(document).ready(function() {
                // Déléguer l'événement de clic pour les boutons dans le tableau
                $(document).on('click', '.custom-icon-2', function() {
                    // Récupérer le 'tr' parent avec 'data-id'
                    var bandeId = $(this).closest('tr').data('id');

                    // Vérifier si 'bandeId' est bien récupéré
                    if (bandeId) {
                        Swal.fire({
                            title: 'Voulez-vous vraiment clôturer la bande?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Oui',
                            cancelButtonText: 'Non',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Faire une requête AJAX pour envoyer 'bandeId' au backend
                                $.ajax({
                                    url: '/cloturer-bande', // L'URL vers laquelle envoyer la requête
                                    method: 'POST',
                                    data: {
                                        bande_id: bandeId,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Succès',
                                            text: 'La bande a été cloturé avec succès.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'btn btn-primary'
                                            }
                                        }).then(() => {
                                            location.reload();
                                        });
                                        // Mettre à jour la page ou effectuer une autre action après succès
                                    },
                                    error: function(error) {
                                        Swal.fire('Erreur',
                                            'Une erreur est survenue!',
                                            'error');
                                    }
                                });
                            }
                        });
                    } else {
                        alert("Impossible de récupérer l'ID de la bande.");
                    }
                });
            });

        });
    </script>

</body>
