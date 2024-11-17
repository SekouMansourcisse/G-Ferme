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
                        <h4>Liste des Assurances pour les voitures de service</h4>
                        <h6>Gerer vos Assurances</h6>
                    </div>
                    @can('create assurances')
                    @endcan
                    <div class="page-btn">
                        <a href="{{ url('assurances/create') }}" class="btn btn-added"><img
                                src="{{ asset('assets/img/icons/plus.svg') }}" alt="img">Ajouter
                            Assurance</a>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"
                                            href="{{ url('listassurancepdf') }}"><img src="{{ asset('assets/img/icons/pdf.svg') }}"
                                                alt="img"></a>
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
                                    <div class="searchinputs" id="dropdownMenuClickable" data-bs-auto-close="false">
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
                                        <th>Voiture Associée</th>
                                        <th>Compagnie d'Assurance</th>
                                        <th>Date d'Activation</th>
                                        <th>Date d'Expiration</th>
                                        <th>Montant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="assuranceTables">
                                    @foreach ($assurances as $assurance)
                                        <tr data-id="{{$assurance->id}}">
                                            <td>{{ $assurance->voiture->plaque_immatriculation }} -
                                                {{ $assurance->voiture->modele }}</td>
                                            <td>{{ $assurance->assureur }}</td>
                                            <td>{{ $assurance->date_debut }}</td>
                                            <td>{{ $assurance->date_fin }}</td>
                                            <td>{{ $assurance->montant }}</td>
                                            <td>
                                                @can('edit assurances')
                                                    <a class="me-3 edit-assurance-btn" href="javascript:void(0);"
                                                        id="edit-assurance-btn">
                                                        <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                                                    </a>
                                                @endcan
                                                @can('delete assurances')
                                                    <a class="me-3 delete-assurance-btn" id="delete-assurance-btn"
                                                        href="javascript:void(0);">
                                                        <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="img">
                                                    </a>
                                                @endcan

                                            </td>
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
    <div class="modal fade" id="editAssuranceModal" tabindex="-1" aria-labelledby="editAssuranceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAssuranceModalLabel">Modifier l'Assurance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateAssuranceForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="assuranceId">
                        <div class="mb-3">
                            <label for="assureur" class="form-label">Assureur</label>
                            <input type="text" class="form-control" id="assureur" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de Début</label>
                            <input type="date" class="form-control" id="date_debut" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de Fin</label>
                            <input type="date" class="form-control" id="date_fin" required>
                        </div>
                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant</label>
                            <input type="number" class="form-control" id="montant" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('js/backend/listassurance.js') }}"></script>

</body>
