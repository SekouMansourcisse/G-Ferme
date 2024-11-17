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
                        <h4>Ajout de produit</h4>
                        <h6>Ajoutez un nouveau produit</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="input-group">
                                        <span class="input-group-text">Type de Produit <span class="text-danger">*</span></span>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="Ingredients">Ingredients</option>
                                            <option value="Les produits de Traitement">Les produits de Traitement</option>
                                            <option value="Les produits de la provenderie">Les produits de la provenderie</option>
                                            <option value="Les produits commercialisable">Les produits commercialisable</option>
                                            <option value="Autres produits">Autres produits</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="input-group">
                                        <span class="input-group-text">Référence <span class="text-danger">*</span></span>
                                        <input type="text" name="reference_produit" class="form-control" placeholder="Référence Produit" required>
                                    </div>
                                    <br>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="input-group">
                                        <span class="input-group-text">Dénomination<span class="text-danger">*</span></span>
                                        <input type="text" name="Denomination" class="form-control" placeholder="Dénomination" required>
                                    </div>
                                    <br>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="input-group">
                                        <span class="input-group-text">Quantité<span class="text-danger">*</span></span>
                                        <input type="text" name="qte_stock" class="form-control" placeholder="Quantité de Stock" aria-describedby="inputGroupPrepend1">
                                        <br>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="input-group">
                                        <span class="input-group-text">Notifier par Seuil? <span class="text-danger">*</span></span>
                                        <select name="seuil" id="seuil" class="form-control" required>
                                            <option value="" selected disabled>Preciser ce produit dispose d'un Seuil d'alerte</option>
                                            <option value="Oui">OUI</option>
                                            <option value="Non">NON</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12" id="stock-seuil-container" style="display: none;">
                                    <div class="input-group">
                                        <span class="input-group-text">Stock<span class="text-danger">*</span></span>
                                        <input type="text" name="stock_seuil" class="form-control" placeholder="Seuil de Stock" aria-describedby="inputGroupPrepend2">
                                    </div>
                                    <br>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="input-group">
                                        <span class="input-group-text">FCFA<span class="text-danger">*</span></span>
                                        <input type="text" name="prix_unitaire" class="form-control" placeholder="Prix Unitaire" aria-describedby="inputGroupPrepend3" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Informations Supplémentaires</label>
                                        <textarea name="infos_supp" class="form-control" rows="4" placeholder="Informations Supplémentaires"></textarea>
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
            url: '/addproduit',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                // Affichage d'un message de succès
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Le produit a été ajouté avec succès.',
                    confirmButtonColor: '#d33', // Couleur du bouton "OK"
                    confirmButtonText: 'OK', // Texte du bouton "OK"
                    customClass: {
                        confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
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
                    text: 'Une erreur est survenue lors de l\'ajout du produit.',
                    confirmButtonColor: '#d33', // Couleur du bouton "OK"
                    confirmButtonText: 'OK', // Texte du bouton "OK"
                    customClass: {
                        confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
                    }
                });
            }
        });
    });
});

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const seuilSelect = document.getElementById('seuil');
            const stockSeuilContainer = document.getElementById('stock-seuil-container');

            seuilSelect.addEventListener('change', function () {
                if (seuilSelect.value === 'Oui') {
                    stockSeuilContainer.style.display = 'block';
                    stockSeuilContainer.querySelector('input').required = true;
                } else {
                    stockSeuilContainer.style.display = 'none';
                    stockSeuilContainer.querySelector('input').required = false;
                }
            });
        });
    </script>

</body>
