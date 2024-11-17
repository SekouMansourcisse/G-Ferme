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
                        <h4>Liste des produits</h4>
                        <h6>Gerer vos produits</h6>
                    </div>
                    <div class="page-btn">
                        <a href="{{url('provenderies/create')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Fabriquer
                            Provenderie</a>
                    </div>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="{{ url('provenderie/export-pdf')}}" title="pdf"><img
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
                                        <th>Date de Fabrication</th>
                                        <th>Produits Obtenus</th>
                                        <th>Ingrédients Consommés</th>
                                        <th>Commentaire</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($provenderies as $provenderie)
                                    <tr>
                                        <td>{{ $provenderie->date_f }}</td>
                                        <td>
                                            @foreach(explode(',', $provenderie->Provend_produit) as $produit)
                                                @php
                                                    list($id, $qte) = explode('*', $produit);
                                                    $produitNom = App\Models\Produit::find($id)->Denomination;
                                                @endphp
                                                {{ $produitNom }} ({{ $qte }} Kg)<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach(explode(',', $provenderie->Ingredient) as $ingredient)
                                                @php
                                                    list($id, $qte) = explode('*', $ingredient);
                                                    $ingredientNom = App\Models\Produit::find($id)->Denomination;
                                                @endphp
                                                {{ $ingredientNom }} ({{ $qte }} Kg)<br>
                                            @endforeach
                                        </td>
                                        <td>{{ $provenderie->commentaire }}</td>
                                        <td>
                                            <a class="me-3 edit-item-btn"  href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>
                                            <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                                                <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="img">
                                            </a>
                                        </td>
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


    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{ asset('js/backend/listproduit.js')}}"></script>
</body>
