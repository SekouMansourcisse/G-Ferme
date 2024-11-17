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
                        <h4>Liste des Abattages</h4>
                        <h6>Gerer vos Abattages</h6>
                    </div>
                    @can('create abbatu')
                    <div class="page-btn">
                        <a href="{{url('sujetsAbbatus/create')}}" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img">Enregistrer
                            Abattage</a>
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
                                        <th>Abattoir Associé</th>
                                        <th>Nombre de Sujets Abattus</th>
                                        <th>Poids Abattu (kg)</th>
                                        <th>Date d'Abattage</th>
                                        @can('edit abbatu')
                                        <th>Actions</th>
                                        @endcan

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sujetsAbbatus as $sujetAbbatu)
                                        <tr data-id="{{$sujetAbbatu->id}}" data-ab_id="{{ $sujetAbbatu->abbatoire_id}}">
                                            <td>{{ $sujetAbbatu->abbatoire->denomination }}</td>
                                            <td>{{ $sujetAbbatu->nombre_sujet }}</td>
                                            <td>{{ $sujetAbbatu->poids_abbatu }}</td>
                                            <td>{{ $sujetAbbatu->date_abbatage }}</td>
                                            @can('edit abbatu')
                                                <td>

                                                    <a class="me-3 edit-item-btn" href="javascript:void(0);"
                                                    id="edit-item-btn">
                                                        <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="img">
                                                    </a>

                                                    @can('delete abbatu')
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
                    <input type="hidden" name="abattu_id" id="abattu_id">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Champ pour sélectionner l'abattoir associé -->
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="abbatoire_id">Abattoir Associé <span class="text-danger">*</span></label>
                                    <select name="abbatoire_id" id="abbatoire_id" class="form-control" required>
                                        <option value="" selected disabled>Selectionner l'abattoir</option>
                                        @foreach ($abbatoires as $abbatoire)
                                            <option value="{{ $abbatoire->id }}">{{ $abbatoire->denomination }}({{ $abbatoire->quantite_sujet}} sujets)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Champ pour le nombre de sujets abattus -->
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="nombre_sujet">Nombre de Sujets Abattus <span class="text-danger">*</span></label>
                                    <input type="number" name="nombre_sujet" id="nombre_sujet" class="form-control" required>
                                </div>
                            </div>

                            <!-- Champ pour le poids abattu -->
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="poids_abbatu">Poids Abattu (kg) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="poids_abbatu" id="poids_abbatu" class="form-control" required>
                                </div>
                            </div>

                            <!-- Champ pour la date d'abattage -->
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="date_abbatage">Date d'Abattage <span class="text-danger">*</span></label>
                                    <input type="date" name="date_abbatage" id="date_abbatage" class="form-control" required>
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
    <script src="{{ asset('js/backend/abbatu.js')}}"></script>
</body>
