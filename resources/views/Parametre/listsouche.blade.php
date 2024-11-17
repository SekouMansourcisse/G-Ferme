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
                        <h4>Liste des souches</h4>
                        <h6>Gerer vos souches</h6>
                    </div>
                    @can('create Paramétrages')
                    <div class="page-btn">
                        <a href="{{url('souches/create')}}" class="btn btn-added"><img src="{{asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            souche</a>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
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

                                        <th>Denomination de la souche</th>
                                        <th>Type d'elevage</th>

                                        <th>Infos supp</th>

                                        @can('edit Paramétrages')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($souches as $souche)
                                    <tr data-id="{{ $souche->id }}">
                                        <td>{{ $souche->Denomination }}</td>
                                        <td>{{ $souche->type }}</td>
                                        <td>{{ $souche->infos_supp }}</td>
                                        @can('edit Paramétrages')
                                        <td>

                                            <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>

                                            @can('delete Paramétrages')
                                            <a class="me-3 delete-traite-btn" href="javascript:void(0);" onclick="confirmDelete2({{ $souche->id }})">
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
  <!-- Modal -->
  <div class="modal fade" id="editSoucheModal" tabindex="-1" aria-labelledby="editSoucheModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSoucheModalLabel">Éditer Souche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSoucheForm">
                @csrf
                <input type="hidden" name="souche_id" id="souche_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Denomination Souche</label>
                                <input type="text" name="Denomination" id="Denomination" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="type">Type d'Élevage <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="" selected disabled>Sélectionner le type d'élevage</option>
                                    <option value="Poulet de chair">Poulet de chair</option>
                                    <option value="Poules pondeuses">Poules pondeuses</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Informations Supplémentaires</label>
                                <textarea name="infos_supp" id="infos_supp" rows="2" class="form-control"></textarea>
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
    <script src="{{ asset('js/backend/listsouche.js')}}"></script>
</body>
