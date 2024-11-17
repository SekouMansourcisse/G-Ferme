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
                        <h4>Enregistrer une Depense</h4>
                        <h6>Enregistrer un nouveau Depense</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('depenses.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Date_depense">Date de la Dépense <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="Date_depense" name="Date_depense" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Beneficiaire">Bénéficiaire <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Beneficiaire" name="Beneficiaire" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Categorie_depense">Catégorie de Dépense <span class="text-danger">*</span></label>

                                        <select name="Categorie_depense" id="Categorie_depense" class="form-control" required>

                                            <option value="D'ordre générale">D'ordre générale</option>
                                            <option value="Transport">Transport</option>
                                            <option value="Commission">Commission</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="TypeDepense_id">Type de Dépense <span class="text-danger">*</span></label>
                                        <select class="form-control" id="TypeDepense_id" name="TypeDepense_id" required>
                                            @foreach($type_depenses as $type_depense)
                                                <option value="{{ $type_depense->id }}">{{ $type_depense->Denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Objet">Objet <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Objet" name="Objet" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Montant_d">Montant De la dépense <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="Montant_d" name="Montant_d" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Montant_paye">Montant Payé <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="Montant_paye" name="Montant_paye" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="dette">Dette</label>
                                        <input type="number" class="form-control" id="dette" name="dette" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="payer_par">Payé par <span class="text-danger">*</span></label>
                                        <select name="payer_par" id="payer_par" class="form-control">
                                            @foreach ($comptes as $compte)
                                            <option value="{{ $compte->id }}">{{ $compte->Denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Fournisseur_id">Fournisseur <span class="text-danger"></span></label>
                                        <select name="Fournisseur_id" id="fournisseur_id" class="form-control">
                                            <option value="" selected disabled>Selectionnez un fournisseur</option>
                                            @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="Description">Description</label>
                                        <textarea class="form-control" id="Description" name="Description"></textarea>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const montantDepenseInput = document.getElementById('Montant_d');
            const montantPayeInput = document.getElementById('Montant_paye');
            const detteInput = document.getElementById('dette');

            function calculateDette() {
                const montantDepense = parseFloat(montantDepenseInput.value) || 0;
                const montantPaye = parseFloat(montantPayeInput.value) || 0;
                const dette = montantDepense - montantPaye;
                detteInput.value = dette;
            }

            montantDepenseInput.addEventListener('input', calculateDette);
            montantPayeInput.addEventListener('input', calculateDette);
        });
    </script>

</body>
