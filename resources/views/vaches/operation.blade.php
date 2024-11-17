@include('partials._head')
<style>
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table i,
    .table img {
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
                        <h4>Opérations sur la vache</h4>
                        <h6>Gérez les différentes opérations sur une vache</h6>
                    </div>
                </div>
                <!-- Ajout des onglets justifiés -->
                <div class="row">

                    <div class="page-btn">
                        @if ($vache->type_elevage=="lait")
                        <a href="{{ url('laitieres') }}" class="btn btn-secondary"> <i class="fa fa-arrow-circle-left"></i>
                            Retour a la liste </a>
                        @endif

                            @if ($vache->type_elevage=="viande")
                            <a href="{{ url('bovins') }}" class="btn btn-secondary"> <i class="fa fa-arrow-circle-left"></i>
                                Retour a la liste </a>
                            @endif
                    </div>
                    <br>
                    <br>
                    <div class="col-md-9">
                        <div class="card bg-white">
                            <div class="card-header">
                                <h5 class="card-title">Opérations</h5>
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
                            @if ($vache->type_elevage == 'lait')
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="nav-item"><a
                                            class="nav-link {{ session('active_tab', 'soin') == 'soin' ? 'active' : '' }}"
                                            href="#soin" data-bs-toggle="tab">soin</a></li>

                                    @if ($vache->type_elevage == 'lait')
                                        <li class="nav-item"><a
                                                class="nav-link {{ session('active_tab') == 'Traite' ? 'active' : '' }}"
                                                href="#Traite" data-bs-toggle="tab">Traite</a>
                                        </li>
                                    @endif

                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane {{ session('active_tab', 'soin') == 'soin' ? 'show active' : '' }}"
                                        id="soin">
                                        <!-- Contenu de l'onglet Eau/soin -->
                                        @include('vaches.soin')
                                    </div>

                                        <div class="tab-pane {{ session('active_tab') == 'Traite' ? 'show active' : '' }}"
                                            id="Traite">
                                            <!-- Contenu de l'onglet Traite -->
                                            @include('vaches.Traite')
                                        </div>


                                </div>
                            </div>
                            @endif
                            @if ($vache->type_elevage != 'lait')
                            <div class="card-body">
                                @include('vaches.soin')
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light shadow-sm border-primary">
                            <div class="card-header btn-secondary text-white text-center" style="background-color: #FF9F43">
                                <h5 class="card-title">Informations sur la vache</h5>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="text-primary">Détails de la vache</h5>
                                <br>
                                <span style="font-size: 1.2em;"><b>Denomination:</b> <span
                                        class="text-secondary">{{ $vache->nom }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Type d'elevage:</b> <span
                                        class="text-secondary">{{ $vache->type_elevage }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Fournisseur:</b> <span
                                        class="text-secondary"></span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Prix d'Achat:</b> <span class="text-secondary">
                                        XOF</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Prix de transport:</b> <span class="text-secondary">
                                        XOF</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Date Arrive:</b> <span
                                        class="text-secondary"></span>{{ $vache->date_naissance}}</span><br>
                                <br>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>

</body>
