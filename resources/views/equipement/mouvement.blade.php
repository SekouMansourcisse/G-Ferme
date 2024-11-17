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
                        <h4>Mouvement Equipement</h4>
                        <h6>Enregistrer un Mouvement d'equipement</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('mouvements.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Equipement_id">Equipement</label>
                                        <select class="form-control" id="Equipement_id" name="Equipement_id" required>
                                            @foreach($equipements as $equipement)
                                                <option value="{{ $equipement->id }}">{{ $equipement->Denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Origine">Origine</label>
                                        <input type="text" class="form-control" id="Origine" name="Origine" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Destination">Destination</label>
                                        <input type="text" class="form-control" id="Destination" name="Destination" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Statut">Statut</label>
                                        <select name="Statut" id="Statut" class="form-control" required>

                                            <option value="Effectué">Effectué</option>
                                            <option value="Non Effectué">Non Effectué</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Date_mouvement">Date du Mouvement</label>
                                        <input type="date" class="form-control" id="Date_mouvement" name="Date_mouvement" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Resume" class="form-label">Commentaire</label>
                                        <textarea class="form-control" id="Resume" name="commentaire" ></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                                    <button type="button" class="btn btn-cancel">Annuler</button>
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
