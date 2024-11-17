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
                        <h4>Liste de Mouvement d'Equipements</h4>
                        <h6>Gerer Les mouvements de vos Equipements</h6>
                    </div>
                    @can('create Equipements')
                    <div class="page-btn">
                        <a href="{{url('mouvements/create')}}" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img">Ajouter
                            Mouvement</a>
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
                                        <img src="{{ asset('assets/img/icons/filter.svg')}}" alt="img">
                                        <span><img src="{{ asset('assets/img/icons/closes.svg')}}" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg')}}"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                src="{{ asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="{{ asset('assets/img/icons/excel.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="{{ asset('assets/img/icons/printer.svg')}}" alt="img"></a>
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

                                        <th>Equipement</th>
                                        <th>Origine</th>
                                        <th>Destination</th>
                                        <th>Statut</th>
                                        <th>Date du Mouvement</th>
                                        <th>Date de Création</th>
                                        @can('edit Equipements')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mouvement_equipements as $mouvement)
                                    <tr data-id="{{ $mouvement->id}}">

                                        <td>{{ $mouvement->equipement->Denomination }}</td>
                                        <td>{{ $mouvement->Origine }}</td>
                                        <td>{{ $mouvement->Destination }}</td>
                                        <td>{{ $mouvement->Statut }}</td>
                                        <td>{{ $mouvement->Date_mouvement }}</td>
                                        <td>{{ $mouvement->created_at }}</td>
                                        @can('edit Equipements')
                                        <td>

                                            <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>

                                            @can('delete Equipements')
                                            <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
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
<!-- Modal pour l'édition du mouvement -->
<div class="modal fade" id="updateMouvementModal" tabindex="-1" aria-labelledby="updateMouvementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateMouvementModalLabel">Modifier le Mouvement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateMouvementForm">
                <div class="modal-body">
                    <input type="hidden" id="mouvementId" name="mouvementId">

                    <div class="form-group mb-3">
                        <label for="equipement_id">Équipement</label>
                        <select id="equipement_id" name="Equipement_id" class="form-control" required>
                            <!-- Les options seront générées ici -->
                            @foreach($equipements as $equipement)
                                <option value="{{ $equipement->id }}">{{ $equipement->Denomination }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="origine">Origine</label>
                        <input type="text" id="origine" name="Origine" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="destination">Destination</label>
                        <input type="text" id="destination" name="Destination" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="statut">Statut</label>
                        <select id="statut" name="Statut" class="form-control" required>
                            <option value="Effectué">Effectué</option>
                            <option value="En Attente">En Attente</option>

                            <!-- Ajoutez d'autres options si nécessaire -->
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="date_mouvement">Date du Mouvement</label>
                        <input type="date" id="date_mouvement" name="Date_mouvement" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
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
    <script src="{{ asset('js/backend/listmouvement.js')}}"></script>
</body>
