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
                        <h4>Enregistrer une Alimentation</h4>
                        <h6>Formulaire d'ajout d'alimentation</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('betail.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="date_alimentation">Date d'Alimentation</label>
                                        <input type="date" name="date_alimentation"
                                            class="form-control"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="operation_id">Nom du produit<span
                                                class="text-danger">**</span></label>
                                        <!-- Menu déroulant avec recherche Select2 -->
                                        <select name="produit_id" id="operation_id"
                                            class="form-control select2">
                                            <option value="" selected disabled>Selectionnez le numéro
                                                de l'opération
                                            </option>
                                            @foreach ($produits as $produit)
                                                <option value="{{ $produit->id }}">
                                                    {{ $produit->Denomination }}(Stock
                                                    actuelle:{{ $produit->qte_stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="Age" class="form-label">Quantité(Kg)</label>
                                        <input type="text" class="form-control" id="quantite"
                                            name="quantite" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="type_elevage">Periode <span
                                                class="text-danger">*</span></label>
                                        <select name="periode" id="periode" class="form-control"
                                            required>
                                            <option value="" selected disabled>Selectionner la
                                                periode</option>
                                            <option value="Matin">Matin</option>
                                            <option value="Soir">Soir</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="Resume" class="form-label">Commentaire</label>
                                        <textarea class="form-control" id="commentaire" name="commentaire" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <button type="submit"
                                        class="btn btn-primary me-2">Enregistrer</button>
                                    <button type="button" id="cancelAddalimentationBtn"
                                        class="btn btn-secondary"
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
