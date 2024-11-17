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
                        <h4>Liste des Maintenances des voitures</h4>
                        <h6>Gerer vos Maintenances</h6>
                    </div>
                    @can('create maintenances')
                    <div class="page-btn">
                        <a href="{{url('maintenances/create')}}" class="btn btn-added"><img src="{{asset('assets/img/icons/plus.svg')}}" alt="img">Enregistrer une maintenance
                            </a>
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
                                        <img src="{{asset('assets/img/icons/filter.svg')}}" alt="img">
                                        <span><img src="{{asset('assets/img/icons/closes.svg')}}" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="{{asset('assets/img/icons/search-white.svg')}}"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" href="{{ url('listmaintenancepdf')}}"><img
                                                src="{{asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="{{asset('assets/img/icons/excel.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="{{asset('assets/img/icons/printer.svg')}}" alt="img"></a>
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

                                        <th>Voiture Associée</th>
                                        <th>Date de Maintenance</th>
                                        <th>Type de Maintenance</th>
                                        <th>Description</th>
                                        <th>Coût</th>
                                        @can('edit maintenances')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($maintenances as $maintenance)
                                        <tr data-id="{{ $maintenance->id }}">

                                            <td>{{ $maintenance->voiture->plaque_immatriculation }} - {{ $maintenance->voiture->modele }}</td>
                                            <td>{{ $maintenance->date_maintenance }}</td>
                                            <td>{{ $maintenance->type_maintenance }}</td>
                                            <td>{{ $maintenance->commentaire }}</td>
                                            <td>{{ $maintenance->cout }}</td>
                                            @can('edit maintenances')
                                            <td>

                                                <a class="me-3 edit-maintenance-btn" href="javascript:void(0);" id="edit-maintenance-btn">
                                                    <img src="{{asset('assets/img/icons/edit.svg')}}" alt="img">
                                                </a>

                                                @can('delete maintenances')
                                                <a class="me-3 delete-maintenance-btn" id="delete-maintenance-btn" href="javascript:void(0);">
                                                    <img src="{{asset('assets/img/icons/delete.svg')}}" alt="img">
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
    <div class="modal fade" id="editMaintenanceModal" tabindex="-1" role="dialog" aria-labelledby="editMaintenanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Modifier ici pour la largeur -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMaintenanceModalLabel">Éditer Maintenance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateMaintenanceForm">
                    <div class="modal-body">
                        <input type="hidden" id="maintenanceId">
                        <div class="form-group">
                            <label>Date de Maintenance</label>
                            <input type="date" id="date_maintenance" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Type de Maintenance</label>
                            <input type="text" id="type_maintenance" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Coût</label>
                            <input type="number" id="cout" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Commentaires</label>
                            <textarea id="commentaire" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
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
    <script src="{{ asset('js/backend/listmaint.js')}}"></script>
</body>
