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
                        <h4>Enregistrer une Vignette</h4>
                        <h6>Formulaire d'ajout de nouveau Vignette</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-form" action="{{ route('vignettes.store') }}">
                            @csrf
                            <div class="row">

                                <!-- Champ pour la date d'obtention -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="date_obtention">Date d'Obtention <span class="text-danger">*</span></label>
                                        <input type="date" name="date_obtention" id="date_obtention" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Champ pour la date d'expiration -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="date_expiration">Date d'Expiration <span class="text-danger">*</span></label>
                                        <input type="date" name="date_expiration" id="date_expiration" class="form-control" required>
                                    </div>
                                </div>
                                <!-- Champ pour la voiture associée -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="voiture_id">Voiture Associée <span
                                                class="text-danger">*</span></label>
                                        <select name="voiture_id" id="operation_id" class="form-control" required>
                                            <option value="" selected disabled>Selectionner la voiture</option>
                                            @foreach ($voitures as $voiture)
                                                <option value="{{ $voiture->id }}">{{ $voiture->plaque_immatriculation }} -
                                                    {{ $voiture->modele }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Champ pour le montant -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant">Montant <span class="text-danger">*</span></label>
                                        <input type="text" name="montant" id="montant" class="form-control" required>
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
    <script>
        $(document).ready(function() {
            // Activer Select2 pour l'élément select avec recherche
            $('#operation_id').select2({
                placeholder: 'Selectionnez le produit',
                allowClear: true,
                width: '100%' // Pour occuper toute la largeur
            });
        });

        $('#operation_search').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();

            // Filtrer les options du select en fonction de l'id du produit
            $('#operation_id option').each(function() {
                var optionValue = $(this).val().toLowerCase(); // Utiliser l'id du produit

                if (optionValue.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    </script>
</body>
