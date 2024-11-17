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
                        <h4>Enregistrer un Abattoire</h4>
                        <h6>Formulaire d'ajout de nouveau Abattoire</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-abbatoire-form" action="{{ route('abbatoires.store') }}">
                            @csrf
                            <div class="row">
                                <!-- Champ pour la dénomination de l'abattoir -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="denomination">Dénomination de l'Abattoir <span class="text-danger">*</span></label>
                                        <input type="text" name="denomination" id="denomination" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Champ pour la quantité de sujets -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="quantite_sujet">Quantité de Sujets <span class="text-danger">*</span></label>
                                        <input type="number" name="quantite_sujet" id="quantite_sujet" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Champ pour l'adresse de l'abattoir -->
                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="adresse">Adresse de l'Abattoir <span class="text-danger">*</span></label>
                                        <textarea name="adresse" id="adresse" class="form-control" rows="3" required></textarea>
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
