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
                        <h4>Enregistrer un Abattage</h4>
                        <h6>Formulaire d'ajout de nouveau Abattage</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-sujet-abbatu-form" action="{{ route('sujetsAbbatus.store') }}">
                            @csrf
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
