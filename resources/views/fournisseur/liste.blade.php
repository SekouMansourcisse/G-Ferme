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
                        <h4>Liste Fournisseur</h4>
                        <h6>Gerer vos Fournisseur</h6>
                    </div>
                    @can('create Fournisseurs')
                    <div class="page-btn">
                        <a href="{{url('fournisseur')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            Fournisseur</a>
                    </div>
                    @endcan

                </div>

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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="{{ url('fournisseurs/export-pdf')}}" title="pdf"><img
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

                                        <th>Nom </th>
                                        <th>Prenom</th>
                                        <th>N°Telephone</th>
                                        <th>N°whatsapp</th>
                                        <th>Redevance initiale</th>
                                        <th>Adresse</th>
                                        <th>Produit</th>
                                        <th>Infos supp</th>
                                        @can('edit Fournisseurs')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fournisseurs as $fournisseur)
                                    <tr data-id="{{ $fournisseur->id }}">
                                        <td>{{ $fournisseur->nom }}</td>
                                        <td>{{ $fournisseur->prenom }}</td>
                                        <td>{{ $fournisseur->phone }}</td>
                                        <td>{{ $fournisseur->num_whatsapp }}</td>
                                        <td>{{ $fournisseur->redevance_initiale }}</td>
                                        <td>{{ $fournisseur->adresse_physique }}</td>
                                        <td>{{ $fournisseur->produit->Denomination }}</td>
                                        <td>{{ $fournisseur->infos_supp }}</td>
                                        @can('edit Fournisseurs')
                                        <td>

                                            <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>

                                            @can('delete Fournisseurs')
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
  <!-- Modal -->
  <div class="modal fade" id="editFournisseurModal" tabindex="-1" aria-labelledby="editFournisseurModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFournisseurModalLabel">Modifier le fournisseur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for editing fournisseur -->
                <form method="POST" id="edit-fournisseur-form">
                    @csrf
                    <div class="row">
                        <input type="hidden" class="form-control" name="edit-id" id="edit-id">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" class="form-control" name="edit-prenom" id="edit-prenom">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" class="form-control" name="edit-nom" id="edit-nom">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" class="form-control" name="edit-telephone" id="edit-telephone">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Adresse</label>
                                <input type="text" class="form-control" name="edit-adresse" id="edit-adresse">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Numéro WhatsApp</label>
                                <input type="text" class="form-control" name="edit-num-whatsapp" id="edit-num-whatsapp">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Redevance Initiale</label>
                                <input type="text" class="form-control" name="edit-redevance-initiale" id="edit-redevance-initiale">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Informations supplémentaires</label>
                                <textarea class="form-control" name="edit-infos-supplementaires" id="edit-infos-supplementaires" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{ asset('js/backend/listfournisseur.js')}}"></script>
</body>
