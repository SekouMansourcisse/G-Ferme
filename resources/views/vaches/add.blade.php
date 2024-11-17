@include('partials._head')
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
                        <h4>Enregistrer une vache</h4>
                        <h6>Formulaire d'ajout de nouveaux vaches</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-form" action="{{ route('vaches.store') }}">
                            @csrf
                            <div class="row">

                                <!-- Champ pour le type de ferme -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="race">Race</label>
                                        <select  name="race" id="race" class="form-control" required>
                                            <option value="" selected disabled>Selectionner la race</option>
                                            @foreach($races as $race)
                                                <option value="{{ $race->id }}">{{ $race->denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="type_elevage">Type de Vache <span class="text-danger">*</span></label>
                                        <select name="type_elevage" id="type_elevage" class="form-control" required>
                                            <option value="" selected disabled>Selectionner le type de vache</option>
                                            <option value="lait">Elevage Laitières</option>
                                            <option value="viande">Elevage Bovins</option>

                                        </select>
                                    </div>
                                </div>
                                <!-- Champ pour l'adresse de la ferme -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="date_naissance">Date d'arrivée</label>
                                        <input type="date" name="date_naissance" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Sélection de l'entreprise associée -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="etat_sante">État de Santé</label>
                                        <input type="text" name="etat_sante" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="ferme_id">Ferme Associée</label>
                                        <select name="ferme_id" id="ferme_id" class="form-control" required>
                                            <option value="" selected disabled>Selectionner la ferme</option>
                                            @foreach($fermes as $ferme)
                                                <option value="{{ $ferme->id }}">{{ $ferme->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Boutons pour soumettre ou annuler -->
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                                    <button type="button" class="btn btn-cancel" onclick="window.history.back();">Annuler</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('partials.script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>

</body>
