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
                        <h4>Liste des races</h4>
                        <h6>Gerer vos races</h6>
                    </div>
                    @can('create Paramétrages')
                        <div class="page-btn">
                            <a href="{{ url('races/create') }}" class="btn btn-added"><img src="assets/img/icons/plus.svg"
                                    alt="img">Ajouter
                                race</a>
                        </div>
                    @endcan
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
                                        <span><img src="{{ asset('assets/img/icons/closes.sv') }}g"
                                                alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img
                                            src="{{ asset('assets/img/icons/search-white.svg') }}" alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
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

                                        <th>Denomination de la race</th>
                                        <th>Type d'elevage</th>
                                        @can('edit Paramétrages')
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($races as $race)
                                        <tr data-id="{{ $race->id }}" data-nom="{{ $race->denomination }}" data-type="{{ $race->elevage }}">
                                            <td>{{ $race->denomination }}</td>
                                            <td>{{ $race->elevage }}</td>

                                            @can('edit Paramétrages')
                                                <td>

                                                    <a href="javascript:void(0);" class="me-3 edit-race-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editRaceModal">
                                                    <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                                </a>

                                                    @can('delete Paramétrages')
                                                    <a class="me-3 delete-traite-btn" href="javascript:void(0);" onclick="confirmDelete2({{ $race->id }})">
                                                        <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="Delete">
                                                    </a>
                                                    @endcan
                                                </td>
                                            @endcan
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
    <div class="modal fade" id="editRaceModal" tabindex="-1" aria-labelledby="editRaceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRaceModalLabel">Éditer Race</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editRaceForm">
                    @csrf
                    <input type="hidden" name="race_id" id="race_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>Denomination Race</label>
                                <input type="text" name="denomination" id="Denomination" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label for="elevage">Type d'élevage</label>
                                <select name="elevage" id="elevage" class="form-control" required>
                                    <option value="" disabled selected>Selectionner le type</option>
                                    <option value="elevage laitière">Elevage laitière</option>
                                    <option value="elevage bovin">Elevage bovin</option>
                                    <option value="elevage mixte">Elevage mixte</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
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
    <script src="{{ asset('js/backend/listrace.js') }}"></script>

</body>
