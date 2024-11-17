@include('partials._head')
<style>
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
                        <h4>Liste des Abattoires</h4>
                        <h6>Gerer vos Abattoires</h6>
                    </div>
                    @can('create abbatoire')
                    <div class="page-btn">
                        <a href="{{url('abbatoires/create')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img">Ajouter
                            Abattoires</a>
                    </div>
                    @endcan

                </div>
                @if (session('success'))
                <div id="success-message" class="alert alert-success alert-dismissible fade show"
                    role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
                <div class="card">
                    <div class="card-body">
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" href="{{ url('listassurancepdf')}}"><img
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
                                        <th>Dénomination</th>
                                        <th>Quantité de Sujets</th>
                                        <th>Adresse</th>
                                        @can('edit abbatoire')
                                        <th>Actions</th>
                                        @endcan

                                    </tr>
                                </thead>
                                <tbody id="abbatoireTable">
                                    @foreach($abattoires as $abattoire)
                                        <tr data-id="{{ $abattoire->id}}">
                                            <td>{{ $abattoire->denomination }}</td>
                                            <td>{{ $abattoire->quantite_sujet }}</td>
                                            <td>{{ $abattoire->adresse }}</td>
                                            @can('edit abbatoire')
                                                <td>

                                                    <a class="me-3 edit-item-btn" href="javascript:void(0);"
                                                    id="edit-item-btn">
                                                        <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="img">
                                                    </a>

                                                    @can('delete abbatoire')
                                                    <a class="me-3 delete-item-btn" id="delete-item-btn"
                                                        href="javascript:void(0);">
                                                        <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="img">
                                                    </a>
                                                    @endcan

                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Pagination Controls -->
                            <div id="paginationControls" class="pagination-wrapper"></div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="editAbattoireModal" tabindex="-1" aria-labelledby="editAbattoireModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAbattoireModalLabel">Éditer Abattoir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editAbattoireForm">
                    @csrf
                    <input type="hidden" name="abattoire_id" id="abattoire_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="denomination">Dénomination de l'Abattoir <span class="text-danger">*</span></label>
                                    <input type="text" name="denomination" id="denomination" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="quantite_sujet">Quantité de Sujets <span class="text-danger">*</span></label>
                                    <input type="number" name="quantite_sujet" id="quantite_sujet" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="adresse">Adresse de l'Abattoir <span class="text-danger">*</span></label>
                                    <textarea name="adresse" id="adresse" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{ asset('js/backend/abbatoire.js')}}"></script>
    <script>
                document.addEventListener('DOMContentLoaded', function() {
            let rowsPerPage = 5; // Nombre de lignes par page
            let tableBody = document.getElementById('abbatoireTable');
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
    </script>
</body>
