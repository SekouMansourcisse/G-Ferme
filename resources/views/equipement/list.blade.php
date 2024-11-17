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
                        <h4>Liste des Equipements</h4>
                        <h6>Gerer vos Equipements</h6>
                    </div>
                    @can('create Equipements')
                    <div class="page-btn">
                        <a href="{{url('equipements/create')}}" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img">Ajouter
                            Un Equipement</a>
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
                                        <th>Dénomination</th>
                                        <th>Ferme</th>
                                        <th>Emplacement</th>
                                        <th>Prix d'Achat</th>
                                        <th>Fournisseur</th>
                                        <th>Date de Création</th>
                                        @can('edit Equipements')
                                        <th>Actions</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipements as $equipement)
                                    <tr data-id="{{ $equipement->id }}">
                                        <td>{{ $equipement->Denomination }}</td>
                                        <td>{{ $equipement->ferme->name }}</td>
                                        <td>{{ $equipement->Emplacement }}</td>
                                        <td>{{ $equipement->PrixAchat }}</td>
                                        <td>{{ $equipement->responsable }}</td>
                                        <td>{{ $equipement->created_at->format('d/m/Y') }}</td>
                                        @can('edit Equipements')
                                        <td>
                                            <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                            </a>
                                            @can('delete Equipements')
                                            <a class="me-3 delete-item-btn" href="javascript:void(0);" id="delete-item-btn">
                                                <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="delete">
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
    <div class="modal fade" id="editEquipementModal" tabindex="-1" role="dialog" aria-labelledby="editEquipementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEquipementModalLabel">Éditer Équipement</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateEquipementForm">
                    <div class="modal-body">
                        <input type="hidden" id="equipementId" name="equipementId">
                        <div class="form-group">
                            <label>Dénomination</label>
                            <input type="text" id="denomination" name="Denomination" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Ferme</label>
                            <select id="ferme_id" name="ferme_id" class="form-control" required>
                                <!-- Remplir les options avec les fermes disponibles -->
                                @foreach($fermes as $ferme)
                                    <option value="{{ $ferme->id }}">{{ $ferme->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Emplacement</label>
                            <input type="text" id="emplacement" name="Emplacement" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Prix d'Achat</label>
                            <input type="number" id="prix_achat" name="PrixAchat" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Responsable</label>
                            <input type="text" id="responsable" name="responsable" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
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
    <script src="{{ asset('js/backend/listequipement.js')}}"></script>
</body>
