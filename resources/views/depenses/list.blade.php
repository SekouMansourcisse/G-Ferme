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
                        <h4>Liste de Depenses</h4>
                        <h6>Gerer vos Depenses</h6>
                    </div>
                    @can('create Dépenses')
                    <div class="page-btn">
                        <a href="{{url('depenses/create')}}" class="btn btn-added"><img src="{{asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            Une Depense</a>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="{{ url('depense_pdf/export-pdf')}}" title="pdf"><img
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

                                        <th>Date de la Dépense</th>
                                        <th>Bénéficiaire</th>
                                        <th>Catégorie de Dépense</th>
                                        <th>Type de Dépense</th>
                                        <th>Objet</th>
                                        <th>Montant Dépense</th>
                                        <th>Montant Payé</th>
                                        @can('edit Dépenses')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($depenses as $depense)
                                    <tr data-id="{{$depense->id}}" data-fournisseur="{{$depense->Fournisseur_id}}"
                                        data-type-depense-id="{{$depense->TypeDepense_id}}" data-compte="{{$depense->payer_par}}">

                                        <td>{{ $depense->Date_depense }}</td>
                                        <td>{{ $depense->Beneficiaire }}</td>
                                        <td>{{ $depense->Categorie_depense }}</td>
                                        <td>{{ $depense->typeDepense->Denomination }}</td>
                                        <td>{{ $depense->Objet }}</td>
                                        <td>{{ $depense->Montant_d }}</td>
                                        <td>{{ $depense->Montant_paye }}</td>
                                        @can('edit Dépenses')
                                        <td>

                                            <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>

                                            @can('delete Dépenses')
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
    <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editExpenseForm">
                    @csrf
                    <input type="hidden" id="depense_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editExpenseModalLabel">Modifier la Dépense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="Date_depense">Date de la Dépense <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="Date_depense" name="Date_depense" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="Beneficiaire">Bénéficiaire <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="Beneficiaire" name="Beneficiaire" required>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="Categorie_depense">Catégorie de Dépense <span class="text-danger">*</span></label>

                                    <select name="Categorie_depense" id="Categorie_depense" class="form-control" required>

                                        <option value="D'ordre générale">D'ordre générale</option>
                                        <option value="Transport">Transport</option>
                                        <option value="Commission">Commission</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="TypeDepense_id">Type de Dépense <span class="text-danger">*</span></label>
                                    <select class="form-control" id="TypeDepense_id" name="TypeDepense_id" required>
                                        @foreach($type_depenses as $type_depense)
                                            <option value="{{ $type_depense->id }}">{{ $type_depense->Denomination }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="Objet">Objet <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="Objet" name="Objet" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="Montant_d">Montant De la dépense <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="Montant_d" name="Montant_d" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="Montant_paye">Montant Payé <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="Montant_paye" name="Montant_paye" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="payer_par">Payé par <span class="text-danger">*</span></label>
                                    <select name="payer_par" id="payer_par" class="form-control">
                                        @foreach ($comptes as $compte)
                                        <option value="{{ $compte->id }}">{{ $compte->Denomination }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="Fournisseur_id">Fournisseur <span class="text-danger"></span></label>
                                    <select name="Fournisseur_id" id="fournisseur_id" class="form-control">
                                        <option value="" selected disabled>Selectionnez un fournisseur</option>
                                        @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{ asset('js/backend/depense.js')}}"></script>
</body>
