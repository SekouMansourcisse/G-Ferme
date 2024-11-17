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
                        <h4>Ajout Un Equipement</h4>
                        <h6>Creer un nouveau Equipement</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('equipements.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Denomination">Dénomination</label>
                                        <input type="text" class="form-control" id="Denomination" name="Denomination" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
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
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Emplacement">Emplacement</label>
                                        <input type="text" class="form-control" id="Emplacement" name="Emplacement" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="PrixAchat">Prix d'Achat</label>
                                        <input type="text" class="form-control" id="PrixAchat" name="PrixAchat" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Fournisseur">Responsable</label>
                                        <input type="text" class="form-control" id="reponsable" name="responsable" required>
                                    </div>
                                </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="Resume" class="form-label">Commentaire</label>
                                            <textarea class="form-control" id="Resume" name="commentaire" required></textarea>
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
