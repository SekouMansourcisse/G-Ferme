@include('partials._head')

<body>
    <style>
        .red-text {
        color: red;
    }

    </style>
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>

    <div class="main-wrapper">
        @include('partials._topbar')
        @include('partials._sidebar_collapsed')
        @include('partials._sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Enregistrer une redevance fournisseur</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form id="ajoutRemboursementForm" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="fournisseur">
                            <div class="form-group">
                                <label for="date_reglement">Date règlement <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_reglement" name="date_reglement" required>
                            </div>

                            <div class="form-group">
                                <label for="fournisseur_id">Nom & Prénoms fournisseur <span class="text-danger">*</span></label>
                                <select class="form-control" id="fournisseur_id" name="fournisseur_id" required>
                                    <option value="">Sélectionnez un fournisseur</option>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="total_dette">Redevance actuelle</label>
                                <b><input type="text" style="color: red" class="form-control" id="total_dette" readonly></b>

                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>N° opération</th>
                                            <th>Type opération</th>
                                            <th>Montant redevance</th>
                                            <th>Montant payé</th>
                                        </tr>
                                    </thead>
                                    <tbody id="operations-table">
                                        <!-- Les opérations seront chargées ici par JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                                <label for="montant_paye">Montant payé <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="montant_p" name="montant_p" required>
                            </div>
                            <div class="form-group">
                                <label for="montant_paye">Virement fait par <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="virement" name="virement" required>
                            </div>
                            <div class="form-group">
                                <label for="payer_par">Payé par</label>

                                <select name="payer_par" id="payer_par" class="form-control" required>
                                    <option value="" selected disabled>Selectionner un compte</option>
                                    @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}">{{ $compte->Denomination }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <button type="button" class="btn btn-secondary" onclick="window.history.back();">Annuler</button>
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
            $('#fournisseur_id').change(function() {
                var fournisseurId = $(this).val();
                if (fournisseurId) {
                    $.ajax({
                        url: '/fournisseur/' + fournisseurId + '/operations',
                        type: 'GET',
                        success: function(response) {
                            $('#total_dette').val(response.totalDette);
                            $('#total_dette').addClass('red-text');
                            var operationsTable = $('#operations-table');
                            operationsTable.empty();

                            response.operations.forEach(function(operation) {
                                operationsTable.append(
                                    '<tr>' +
                                        '<td> FactureN°' + operation.id + '</td>' +
                                        '<td>' + operation.typeOperation + '</td>' +
                                        '<td>' + operation.montantDette + '</td>' +
                                        '<td><input type="number" name="montant_paye[' + operation.id + ']" class="form-control" value="0"></td>' +
                                    '</tr>'
                                );
                            });
                        }
                    });
                } else {
                    $('#total_dette').val('').removeClass('red-text');
                    $('#operations-table').empty();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#ajoutRemboursementForm').on('submit', function(event) {
                event.preventDefault(); // Empêche la soumission par défaut du formulaire

                let formData = $(this).serialize(); // Sérialise les données du formulaire

                $.ajax({
                    url: '/ajout-remboursement', // Remplacez par l'URL de votre route de traitement du formulaire
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        // Vérifiez la réponse du serveur et affichez un message de succès
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès',
                                text: 'Le remboursement a été ajouté avec succès',
                                icon: 'success'
                            }).then(() => {
                        $('#ajoutRemboursementForm').trigger("reset");
                    });
                        } else {
                            Swal.fire({
                                title: 'Erreur',
                                text: response.message || 'Une erreur est survenue',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Affichez un message d'erreur en cas de problème avec la requête AJAX
                        Swal.fire({
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de l\'ajout du remboursement',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>

</body>
