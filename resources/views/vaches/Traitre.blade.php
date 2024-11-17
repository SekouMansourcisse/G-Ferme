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
                        <h4>Liste de traite des vaches</h4>
                        <h6>Traite</h6>
                    </div>
                    @can('create alimentation_betail')
                    <div class="page-btn">
                        <a href="{{url('betail/create')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img">Démarrer
                            Traite</a>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" href="{{url('listVachepdf')}}"><img
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
                                        <th>traite</th>
                                        <th>Production Matin</th>
                                        <th>Production Soir</th>
                                        <th>Quantité de Lait produite</th>
                                        <th>Date de Traite</th>
                                        @can('edit operation sur les vaches')
                                        <th>Actions</th>
                                        @endcan

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($traites as $traite)
                                    <tr data-id="{{$traite->id}}"  data-prodM="{{$traite->production_matin}}"
                                        data-prodS="{{$traite->production_soir}}" data-date="{{ $traite->date_production }}">
                                        <td>{{ $traite->vache->nom}}</td>
                                        <td>{{ $traite->production_matin }} (Litres)</td>
                                        <td>{{ $traite->production_soir}} (Litres) </td>
                                        <td>{{ $traite->production_matin + $traite->production_soir }} (Litres)</td>

                                        <td>{{ $traite->date_production }}</td>
                                        @can('edit operation sur les vaches')
                                            <td>

                                                <a href="javascript:void(0);" class="me-3 edit-traite-btn"
                                                data-bs-toggle="modal" data-bs-target="#editTraiteModal">
                                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                            </a>

                                                @can('delete operation sur les vaches')
                                                <a class="me-3 delete-traite-btn" href="javascript:void(0);" onclick="confirmDelete2({{ $traite->id }})">
                                                    <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="Delete">
                                                </a>
                                                @endcan
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
</body>
