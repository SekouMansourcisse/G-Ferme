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
                        <h4>Ajout de Compte</h4>
                        <h6>Creer un nouveau Compte</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Denomination</label>
                                        <input type="text" name="Denomination" id="Denomination" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Solde Actuel(FCFA)</label>
                                        <input type="text" name="solde_actuel" id="solde_actuel" class="form-control">
                                    </div>
                                </div>
                                <!-- Ajoutez les champs supplémentaires au besoin -->

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Informations Supplémentaires</label>
                                        <textarea name="infos_supp" id="infos_supp" rows="4" class="form-control"></textarea>
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
    // Soumission du formulaire
    $('#add-form').submit(function(e) {
        e.preventDefault();

        // Récupération des données du formulaire
        var formData = new FormData(this);

        // Envoi de la requête AJAX pour ajouter un poulailler
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/addcompte',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                // Affichage d'un message de succès
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Le Compte a été ajouté avec succès.',
                    confirmButtonText: 'OK', // Texte du bouton "OK"
                    customClass: {
                        confirmButton: 'btn btn-danger' // Classe CSS personnalisée pour le bouton "OK"
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
                    text: 'Une erreur est survenue lors de l\'ajout du Compte.',
                    confirmButtonText: 'OK', // Texte du bouton "OK"
                    customClass: {
                        confirmButton: 'btn btn-danger' // Classe CSS personnalisée pour le bouton "OK"
                    }
                });
            }
        });
    });
});

    </script>
</body>
