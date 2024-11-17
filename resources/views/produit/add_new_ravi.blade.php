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
                        <h4>Ajout de ravitaillement</h4>
                        <h6>Ajoutez un nouveau ravitaillement de produit</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-ravitaillement-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date ravitaillement <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="fournisseur_id">Nom du fournisseur <span class="text-danger">**</span></label>
                                        <select name="fournisseur_id" id="fournisseur_id" class="form-control" required>
                                            <option value="" selected disabled>Selectionnez un fournisseur</option>
                                            @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#addFournisseurModal">Nouveau fournisseur</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Dénomination du produit</th>
                                            <th>Qte acheté</th>
                                            <th>Prix unitaire</th>
                                            <th>Prix total</th>
                                            <th>Action</th> <!-- Nouvelle colonne pour le bouton de suppression -->
                                        </tr>
                                    </thead>
                                    <tbody id="produits-table-body">
                                        <tr>
                                            <td>
                                                <select name="produit_id[]" class="form-control" required>
                                                    @foreach($produits as $produit)
                                                    <option value="{{ $produit->id }}">{{ $produit->Denomination }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="numeric" name="qte_produit[]" class="form-control qte-produit" required></td>
                                            <td><input type="numeric" name="prix_unitaire_p[]" class="form-control prix-unitaire" required></td>
                                            <td><input type="numeric" name="prix_total_p[]" class="form-control prix-total" required readonly></td>
                                            <td></td> <!-- Colonne vide pour la première ligne -->
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-secondary" id="add-product-row">Ajouter un produit</button>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_ravitaillement">Total ravitaillement</label>
                                        <input type="number"  class="form-control" name="total_ravitaillement" id="total_ravitaillement" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_remise">Total remise <span class="text-danger">**</span></label>
                                        <input type="number"  class="form-control" name="total_remise" id="total_remise" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="net_payer">Net à payer</label>
                                        <input type="number"  class="form-control" name="net_payer" id="net_payer" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_paye">Montant payé <span class="text-danger">**</span></label>
                                        <input type="number"  class="form-control" name="montant_paye" id="montant_paye" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dette_a_paye">Dette à payer</label>
                                        <input type="number"  class="form-control" name="dette_a_paye" id="dette_a_paye" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="payer_par">Payé par</label>

                                        <select name="payer_par" id="payer_par" class="form-control" required>
                                            <option value="" selected disabled>Selectionner un compte</option>
                                            @foreach($comptes as $compte)
                                            <option value="{{ $compte->id }}">{{ $compte->Denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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

    <!-- Modal pour ajouter un nouveau fournisseur -->
    <div class="modal fade" id="addFournisseurModal" tabindex="-1" aria-labelledby="addFournisseurModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFournisseurModalLabel">Ajouter un nouveau fournisseur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="add-fournisseur-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" name="nom" id="nom" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Prénom</label>
                                    <input type="text" name="prenom" id="prenom" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Redevance Initiale</label>
                                    <input type="text" name="redevance_initiale" id="redevance_initiale" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Téléphone</label>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Numéro WhatsApp</label>
                                    <input type="text" name="num_whatsapp" id="num_whatsapp" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Adresse Physique</label>
                                    <input type="text" name="adresse_physique" id="adresse_physique" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Produit</label>
                                    <select class="form-control" name="produit" id="produit">
                                        <option value="">Sélectionnez un produit</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}">{{ $produit->Denomination }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Informations Supplémentaires</label>
                                    <textarea name="infos_supp" id="infos_supp" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary me-2">Enregistrer</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            </div>
                        </div>
                    </form>
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
            // Fonction pour calculer le prix total d'un produit
            function calculerPrixTotal() {
                let totalRavitaillement = 0;

                $('#produits-table-body tr').each(function() {
                    const qteProduit = parseFloat($(this).find('.qte-produit').val()) || 0;
                    const prixUnitaire = parseFloat($(this).find('.prix-unitaire').val()) || 0;
                    const prixTotal = qteProduit * prixUnitaire;

                    $(this).find('.prix-total').val(prixTotal.toFixed(2));

                    totalRavitaillement += prixTotal;
                });

                $('#total_ravitaillement').val(totalRavitaillement.toFixed(2));
                calculerNetPayerEtDette();
            }

            // Fonction pour calculer le net à payer et la dette à payer
            function calculerNetPayerEtDette() {
                const totalRavitaillement = parseFloat($('#total_ravitaillement').val()) || 0;
                const totalRemise = parseFloat($('#total_remise').val()) || 0;
                const montantPaye = parseFloat($('#montant_paye').val()) || 0;

                const netPayer = totalRavitaillement - totalRemise;
                const detteAPayer = netPayer - montantPaye;

                $('#net_payer').val(netPayer.toFixed(2));
                $('#dette_a_paye').val(detteAPayer.toFixed(2));
            }

            // Ajouter une nouvelle ligne de produit
            $('#add-product-row').click(function() {
                var newRow = `<tr>
                                <td>
                                    <select name="produit_id[]" class="form-control" required>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}">{{ $produit->Denomination }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="numeric" name="qte_produit[]" class="form-control qte-produit" required></td>
                                <td><input type="numeric" name="prix_unitaire_p[]" class="form-control prix-unitaire" required></td>
                                <td><input type="numeric" name="prix_total_p[]" class="form-control prix-total" required readonly></td>
                                <td><button type="button" class="btn btn-danger remove-product-row">X</button></td> <!-- Bouton de suppression -->
                            </tr>`;
                $('#produits-table-body').append(newRow);
            });

            // Supprimer une ligne de produit
            $(document).on('click', '.remove-product-row', function() {
                $(this).closest('tr').remove();
            });

            // Écouteurs d'événements pour les champs quantité et prix unitaire
            $('#produits-table-body').on('input', '.qte-produit, .prix-unitaire', function() {
                calculerPrixTotal();
            });

            // Écouteur d'événement pour le champ total remise
            $('#total_remise').on('input', function() {
                calculerNetPayerEtDette();
            });

            // Écouteur d'événement pour le champ montant payé
            $('#montant_paye').on('input', function() {
                calculerNetPayerEtDette();
            });

            // Soumission du formulaire de ravitaillement
            $('#add-ravitaillement-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: "POST",
                    url: '/addravitaillement',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Le ravitaillement a été ajouté avec succès.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK',
                            customClass: { confirmButton: 'btn btn-primary' }
                        }).then(() => { location.reload(); });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de l\'ajout du ravitaillement.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK',
                            customClass: { confirmButton: 'btn btn-primary' }
                        });
                    }
                });
            });

            // Soumission du formulaire d'ajout de fournisseur
            $('#add-fournisseur-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: "POST",
                    url: '/addfournisseur',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Le fournisseur a été ajouté avec succès.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK',
                            customClass: { confirmButton: 'btn btn-primary' }
                        }).then(() => { location.reload(); });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de l\'ajout du fournisseur.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK',
                            customClass: { confirmButton: 'btn btn-primary' }
                        });
                    }
                });
            });
        });
    </script>
</body>
