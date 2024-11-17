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
                        <h4>Opérations sur la Bande</h4>
                        <h6>Gérez les différentes opérations sur une bande</h6>
                    </div>
                </div>
                <!-- Ajout des onglets justifiés -->
                <div class="row">
                    <div class="page-btn">
                        <a href="{{url('listbande')}}" class="btn btn-primary"> <img src="{{ asset('assets/img/icons/return1.svg')}}" alt="img">
                         Retour a la liste    </a>
                    </div>
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
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="nav-item"><a
                                            class="nav-link {{ session('active_tab', 'soin') == 'soin' ? 'active' : '' }}"
                                            href="#eau-traitement" data-bs-toggle="tab">Traitement</a></li>
                                    <li class="nav-item"><a
                                            class="nav-link {{ session('active_tab') == 'alimentation' ? 'active' : '' }}"
                                            href="#alimentation" data-bs-toggle="tab">Alimentation</a></li>
                                    <li class="nav-item"><a
                                            class="nav-link {{ session('active_tab') == 'journalisation' ? 'active' : '' }}"
                                            href="#journalisation" data-bs-toggle="tab">Journalisation</a></li>
                                    @if ($bande->type == 2)
                                        <li class="nav-item"><a
                                                class="nav-link {{ session('active_tab') == 'ramassage-oeufs' ? 'active' : '' }}"
                                                href="#ramassage-oeufs" data-bs-toggle="tab">Ram.oeufs</a></li>
                                    @endif
                                    <li class="nav-item"><a
                                            class="nav-link {{ session('active_tab') == 'ramassage-litiere' ? 'active' : '' }}"
                                            href="#ramassage-litiere" data-bs-toggle="tab">Ram.litières</a></li>
                                    @if ($bande->type == 1)
                                        <li class="nav-item"><a
                                                class="nav-link {{ session('active_tab') == 'pesage' ? 'active' : '' }}"
                                                href="#pesage" data-bs-toggle="tab">Pesage</a></li>
                                    @endif
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane {{ session('active_tab', 'soin') == 'soin' ? 'show active' : '' }}"
                                        id="eau-traitement">
                                        <!-- Contenu de l'onglet Eau/Traitement -->
                                        @include('bande.soin')
                                    </div>
                                    <div class="tab-pane {{ session('active_tab') == 'alimentation' ? 'show active' : '' }}"
                                        id="alimentation">
                                        <!-- Contenu de l'onglet Alimentation -->
                                        @include('bande.alimentation')
                                    </div>
                                    <div class="tab-pane {{ session('active_tab') == 'journalisation' ? 'show active' : '' }}"
                                        id="journalisation">
                                        <!-- Contenu de l'onglet Journalisation -->
                                        @include('bande.journalisation')
                                    </div>
                                    @if ($bande->type == 2)
                                        <div class="tab-pane {{ session('active_tab') == 'ramassage-oeufs' ? 'show active' : '' }}"
                                            id="ramassage-oeufs">
                                            <!-- Contenu de l'onglet Ramassage d'oeufs -->
                                            @include('bande.ramassageOeuf')
                                        </div>
                                    @endif
                                    <div class="tab-pane {{ session('active_tab') == 'ramassage-litiere' ? 'show active' : '' }}"
                                        id="ramassage-litiere">
                                        <!-- Contenu de l'onglet Ramassage de litières -->
                                        @include('bande.ramassageLitiere')
                                    </div>
                                    <div class="tab-pane {{ session('active_tab') == 'pesage' ? 'show active' : '' }}"
                                        id="pesage">
                                        <!-- Contenu de l'onglet Pesage -->
                                        @include('bande.pesage')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light shadow-sm border-primary">
                            <div class="card-header btn-secondary text-white text-center" style="background-color: #FF9F43">
                                <h5 class="card-title">Informations sur la bande</h5>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="text-primary">Détails de la Bande</h5>
                                <br>
                                <span style="font-size: 1.2em;"><b>Denomination:</b> <span
                                        class="text-secondary">{{ $bande->nom_bande }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Type d'elevage:</b> <span
                                        class="text-secondary">{{ $bande->type_elevage }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Fournisseur:</b> <span
                                        class="text-secondary">{{ $bande->nomFournisseur }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Poulailler:</b> <span
                                        class="text-secondary">{!! app('App\Http\Controllers\BandeController')->getPoulaillerInfo2($bande->poulailler) !!}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Prix d'Achat:</b> <span
                                        class="text-secondary">{{ number_format($bande->cout_acquisition, 0, ',', ' ') }}
                                        XOF</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Prix de transport:</b> <span
                                        class="text-secondary">{{ number_format($bande->prix_transport, 0, ',', ' ') }}
                                        XOF</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Date Arrive:</b> <span
                                        class="text-secondary">{{ $bande->date_demarrage }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Date Prevue de fin:</b> <span
                                        class="text-secondary">{{ $bande->date_fin }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Âge actuel:</b>
                                    @if ($bande->ageDetails['months'] >= 1)
                                        <span class="text-secondary">{{ $bande->ageDetails['months'] }} mois
                                            @if ($bande->ageDetails['weeks'] >= 1)
                                                , {{ $bande->ageDetails['weeks'] }} semaines
                                            @endif
                                            @if ($bande->ageDetails['days'] >= 1)
                                                , {{ $bande->ageDetails['days'] }} jours
                                            @endif
                                        </span>
                                    @elseif ($bande->ageDetails['weeks'] >= 1)
                                        <span class="text-secondary">{{ $bande->ageDetails['weeks'] }} semaines
                                            @if ($bande->ageDetails['days'] >= 1)
                                                , {{ $bande->ageDetails['days'] }} jours
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-secondary">{{ $bande->ageDetails['days'] }} jours</span>
                                    @endif
                                </span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Nombre décès:</b> <span
                                        class="text-danger">{{ $bande->totalDeaths }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Nombre malades:</b> <span
                                        class="text-warning">{{ $bande->totalSick }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Nombre actuel:</b> <span
                                        class="text-success">{{ $bande->cheptel_actuel }}</span></span><br>
                                <br>
                                <span style="font-size: 1.2em;"><b>Sujets morts:</b> <span
                                        class="text-danger">{{ $bande->sujets_morts }}</span></span><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
</body>
