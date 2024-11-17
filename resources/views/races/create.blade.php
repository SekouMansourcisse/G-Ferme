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
                        <h4>Enregistrer une race de vache</h4>
                        <h6>Formulaire d'ajout de nouveaux races</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-form" action="{{ route('races.store')}}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Denomination Race</label>
                                        <input type="text" name="denomination" id="Denomination" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="Categorie_depense">Type d'elevation <span class="text-danger">*</span></label>

                                        <select name="elevage" id="elevage" class="form-control" required>
                                            <option value="" selected disabled>Selectionner le type d'elevage</option>
                                            <option value="elevage laitière">Elevage laitière</option>
                                            <option value="elevage bovin">Elevage bovin</option>
                                            <option value="elevage mixte"> Elevage mixte</option>

                                        </select>
                                    </div>
                                </div>
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
