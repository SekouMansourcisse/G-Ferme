@include('partials.head')
<head>
    @include('partials.head')
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
</head>
<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        @include('partials.topbar')
        @include('partials.sidebar')
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Liste des Bandes</h4>
                        <h6>Gerer vos Bandes</h6>
                    </div>
                    <div class="page-btn">
                        <a href="{{url('bande')}}" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img">Ajouter
                            une bande</a>
                    </div>

                </div>
                <div class="d-flex justify-content-between mb-6">
                    <div>

                        <button class="btn btn-success">Cycle en cours</button>
                        <button class="btn btn-danger">Cycle clôturé</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="assets/img/icons/filter.svg" alt="img">
                                        <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                src="assets/img/icons/pdf.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="assets/img/icons/excel.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="assets/img/icons/printer.svg" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card" id="filter_inputs">
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter User Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Phone">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Email">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" class="datetimepicker cal-icon"
                                                placeholder="Choose Date">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select">
                                                <option>Disable</option>
                                                <option>Enable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                                        <div class="form-group">
                                            <a class="btn btn-filters ms-auto"><img
                                                    src="assets/img/icons/search-whites.svg" alt="img"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Informations sur la bande</th>
                                        <th>Nombre de sujet actuel</th>
                                        <th>Info poulailler</th>
                                        <th>Total oeuf</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="bandesTable">
                                    @foreach($bandes as $bande)
                                    <tr>
                                        <td>
                                            <p><b>Date arrivée</b>: {{ $bande->date_demarrage }}</p>
                                            <p><b>Âge arrivée</b>: {{ $bande->age_arrive }}</p>
                                            <p><b>Effectif arrivée</b>: {{ $bande->cheptel_depart }}</p>
                                            <p><b>Nom</b>: {{ $bande->nom_bande }}</p>
                                            <p><b>Résumé</b>: {{ $bande->observation }}</p>
                                        </td>
                                        <td>
                                            <p><b>Âge actuel</b>:
                                                @if ($bande->ageDetails['months'] >= 1)
                                                    {{ $bande->ageDetails['months'] }} mois
                                                    @if ($bande->ageDetails['weeks'] >= 1)
                                                        , {{ $bande->ageDetails['weeks'] }} semaines
                                                    @endif
                                                    @if ($bande->ageDetails['days'] >= 1)
                                                        , {{ $bande->ageDetails['days'] }} jours
                                                    @endif
                                                @elseif ($bande->ageDetails['weeks'] >= 1)
                                                    {{ $bande->ageDetails['weeks'] }} semaines
                                                    @if ($bande->ageDetails['days'] >= 1)
                                                        , {{ $bande->ageDetails['days'] }} jours
                                                    @endif
                                                @else
                                                    {{ $bande->ageDetails['days'] }} jours
                                                @endif
                                            </p>
                                            <p><b>Nombre décès</b>: {{ $bande->totalDeaths }}</p>
                                            <p><b>Nombre malades</b>: {{ $bande->totalSick }}</p>
                                            <p><b>Nombre vendus</b>:{{ $bande->qte_vendu }} </p>
                                            <p><b>Nombre actuel</b>: {{ $bande->cheptel_actuel }}</p>
                                            <p><b>Traitement imminent</b>: </p>
                                        </td>
                                        <td>
                                            <p>{!! app('App\Http\Controllers\BandeController')->getPoulaillerInfo2($bande->poulailler) !!}</p>
                                        </td>
                                        <td></td>
                                        <td>
                                            <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                <img src="assets/img/icons/edit.svg" alt="edit" >
                                            </a>
                                            <a class="me-3 delete-item-btn" href="javascript:void(0);" id="delete-item-btn">
                                                <img src="assets/img/icons/delete.svg" alt="delete" >
                                            </a>
                                            <a class="me-3 custom-icon-2" href="javascript:void(0);" id="custom-icon-2">
                                                <img src="assets/img/icons/close-circle1.svg" alt="custom2" >
                                            </a>
                                            <a class="me-3 custom-icon-1" href="{{ url('bandeOperation/'.$bande->id) }}" id="custom-icon-1">
                                                <img src="assets/img/icons/settings.svg" alt="custom1">
                                            </a>
                                            <a class="me-3 custom-icon-2" href="{{ url('bandeStat/'.$bande->id) }}" id="custom-icon-2">
                                                <i data-feather="bar-chart-2"></i>
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
  <!-- Modal -->


    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{ asset('js/backend/listcompte.js')}}"></script>

</body>
