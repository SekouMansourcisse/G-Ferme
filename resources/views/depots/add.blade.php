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
                        <h4>Ajout de Dépôt</h4>
                        <h6>Créer un nouveau dépôt</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-depot-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Client</label>
                                        <select name="client_id" id="client_id" class="form-control">
                                            <!-- Remplir cette liste déroulante avec les clients disponibles -->
                                            <option value="0">Choisissez un client</option>
                                            @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->nom }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Solde Dépôt Actuel</label>
                                        <input type="text" name="solde_depot_actuel" id="solde_depot_actuel" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Montant Dépôt</label>
                                        <input type="text" name="montant_depot" id="montant_depot" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Versement Fait</label>
                                        <input type="text" name="versement_fait" id="versement_fait" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Compte</label>
                                        <select name="compte_id" id="compte_id" class="form-control">
                                            <!-- Remplir cette liste déroulante avec les comptes disponibles -->
                                            @foreach ($comptes as $compte)
                                            <option value="{{ $compte->id }}">{{ $compte->Denomination}}</option>
                                        @endforeach
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
    <script>
        $(document).ready(function() {
            $('#client_id').change(function() {
            var clientId = $(this).val();

            $.ajax({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url: '/client-depot-solde/' + clientId,
                method: 'GET',
                success: function(data) {
                    $('#solde_depot_actuel').val(data.solde_depot_actuel);
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });


            // Soumission du formulaire
            $('#add-depot-form').submit(function(e) {
                e.preventDefault();

                // Récupération des données du formulaire
                var formData = new FormData(this);

                // Envoi de la requête AJAX pour ajouter un dépôt
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '/add-depot',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        // Affichage d'un message de succès
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Le dépôt a été ajouté avec succès.',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        }).then(() => {
                            // Actualisation de la page
                            location.reload();
                        });
                    },
                    error: function(error) {
                        // Affichage d'un message d'erreur
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de l\'ajout du dépôt.',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
