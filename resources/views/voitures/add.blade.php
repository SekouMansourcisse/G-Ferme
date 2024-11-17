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
                        <h4>Enregistrer une Voiture de Service</h4>
                        <h6>Formulaire d'ajout d'une nouvelle Voiture de service</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-form" action="{{ route('voitures.store') }}">
                            @csrf
                            <div class="row">

                                <!-- Champ pour le numéro de plaque -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="numero_plaque">Numéro de Plaque <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="plaque_immatriculation" id="numero_plaque"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <!-- Champ pour le modèle -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="modele">Modèle <span class="text-danger">*</span></label>
                                        <input type="text" name="modele" id="modele" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <!-- Champ pour la marque -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="marque">Marque <span class="text-danger">*</span></label>
                                        <input type="text" name="marque" id="marque" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <!-- Champ pour l'année -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="annee">Année <span class="text-danger">*</span></label>
                                        <input type="text" name="annee_fabrication" id="annee_fabrication" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <!-- Champ pour la kilometrage -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="kilometrage">Kilometrage <span class="text-danger">*</span></label>
                                        <input type="text" name="kilometrage" id="kilometrage" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <!-- Champ pour l'etat -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="type_elevage">Etat de la voiture <span class="text-danger">*</span></label>
                                        <select name="etat" id="etat" class="form-control" required>
                                            <option value="" selected disabled>Selectionner l'etat de la voiture</option>
                                            <option value="En service">En Service</option>
                                            <option value="En panne">En panne</option>

                                        </select>
                                    </div>
                                </div>

                                <!-- Champ pour les commentaires -->
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="commentaire">Commentaire</label>
                                        <textarea name="commentaire" id="commentaire" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>

                                <!-- Boutons pour soumettre ou annuler -->
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                                    <button type="button" class="btn btn-cancel"
                                        onclick="window.history.back();">Annuler</button>
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
