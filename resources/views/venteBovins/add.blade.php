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
                        <h4>Interface de vente de vache</h4>
                        <h6>Enregistrer une vente</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('ventes_bovins.store') }}">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="type" value="vente-oeuf">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date Vente <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_client">Type de client<span
                                                class="text-danger">**</span></label>
                                        <select name="type_client" id="type_client" class="form-control" required>
                                            <option value="" selected disabled>Selectionnez le type de client
                                            </option>
                                            <option value="Client Comptoir">Client Comptoir</option>
                                            <option value="Client Fidèle">Client Fidèle</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12" id="client_comptoir_div" style="display: none;">
                                    <div class="form-group">
                                        <label for="client_comptoir">Nom & Prénom du client<span
                                                class="text-danger">**</span></label>
                                        <input type="text" name="client_comptoir" id="client_comptoir"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="client_fidele_div" style="display: none;">
                                    <div class="form-group">
                                        <label for="client_id">Nom & Prénom du client<span
                                                class="text-danger">**</span></label>
                                        <select name="client_id" id="client_id" class="form-control">
                                            <option value="" selected disabled>Selectionnez un client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" data-phone="{{ $client->phone }}"
                                                    data-dette="{{ $client->dette_initiale }}"
                                                    data-addresse="{{ $client->adresse_physique }}">
                                                    {{ $client->nom }} {{ $client->prenom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal"
                                            data-bs-target="#addFournisseurModal">Nouveau Client</button>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="phone_div">
                                    <div class="form-group">
                                        <label>Numéro de Téléphone <b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="phone" id="phone" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="dette_div" style="display: none;">
                                    <div class="form-group">
                                        <label>Dette Actuelle<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="dette_initiale" style="color: red ; text:bold;"
                                            id="dette_initiale" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="adresse_div" style="display: none;">
                                    <div class="form-group">
                                        <label>Adresse<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="addresse" style="color: red ; text:bold;"
                                            id="addresse" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-4">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nom ou N° de la vache</th>
                                            <th>Prix de Vente</th>
                                            <th>Action</th> <!-- Nouvelle colonne pour le bouton de suppression -->
                                        </tr>
                                    </thead>
                                    <tbody id="vaches-table-body">
                                        <tr>
                                            <td>

                                                <div class="form-group">
                                                    <!-- Menu déroulant avec recherche Select2 -->
                                                    <select name="operation_id[]" id="operation_id"
                                                        class="form-control select2">
                                                        <option value="" selected disabled>Selectionnez la vache
                                                        </option>
                                                        @foreach ($vaches as $vache)
                                                            <option value="{{ $vache->id }}">{{ $vache->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td><input type="numeric" name="prix_vente[]"
                                                    class="form-control prix-vente" required></td>
                                            <td></td> <!-- Colonne vide pour la première ligne -->
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-secondary" id="add-product-row">Ajouter une
                                    vache</button>
                            </div>
                            <br>
                            <div class="row mt-4">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_ravitaillement">Montant Vente</label>
                                        <input type="number" class="form-control" name="total_ravitaillement"
                                            id="total_ravitaillement" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_remise">Total remise <span
                                                class="text-danger">**</span></label>
                                        <input type="number" class="form-control" name="total_remise"
                                            id="total_remise" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="net_payer">Net à payer</label>
                                        <input type="number" class="form-control" name="net_payer" id="net_payer"
                                            required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_paye">Montant payé <span
                                                class="text-danger">**</span></label>
                                        <input type="number" class="form-control" name="montant_paye"
                                            id="montant_paye" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dette_a_paye">Dette à payer</label>
                                        <input type="number" class="form-control" name="dette_a_paye"
                                            id="dette_a_paye" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="payer_par">Payé par</label>
                                        <select name="payer_par" id="payer_par" class="form-control" required>
                                            <option value="" selected disabled>Selectionner un compte</option>
                                            @foreach ($comptes as $compte)
                                                <option value="{{ $compte->id }}">{{ $compte->Denomination }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <button type="button" class="btn btn-secondary"
                                    onclick="window.history.back();">Annuler</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal pour l'ajout de nouveau client -->
    <div class="modal fade" id="addFournisseurModal" tabindex="-1" aria-labelledby="addFournisseurModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFournisseurModalLabel">Ajouter un nouveau fournisseur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="add-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nom <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="nom" id="nom" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Prénom <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="prenom" id="prenom" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Dette Initiale <b><span class="text-danger">*</span></b> </label>
                                    <input type="text" name="dette_initiale" id="redevance_initiale"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Téléphone <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" id="email" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Numéro WhatsApp <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="num_whatsapp" id="num_whatsapp"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Adresse Physique <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="adresse_physique" id="adresse_physique"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Informations Supplémentaires <b><span
                                                class="text-danger">*</span></b></label>
                                    <textarea name="infos_supp" id="infos_supp" class="form-control" rows="4"></textarea>
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
    @include('partials.script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('assets/version/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/backend/vente-bovins.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Activer Select2 pour l'élément select avec recherche
            $('#operation_id').select2({
                placeholder: 'Selectionnez le numéro de l\'opération',
                allowClear: true,
                width: '100%' // Pour occuper toute la largeur
            });
            $('#operation_search').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                // Filtrer les options du select en fonction du texte entré dans l'input
                $('#operation_id option').each(function() {
                    var optionText = $(this).text().toLowerCase();
                    var optionValue = $(this).val().toLowerCase();

                    if (optionText.includes(searchTerm) || optionValue.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Fonction pour calculer le total du ravitaillement
            function calculerPrixTotal() {
                let totalRavitaillement = 0;

                $('#vaches-table-body tr').each(function() {
                    const prixUnitaire = parseFloat($(this).find('.prix-vente').val()) || 0;
                    const prixTotal = prixUnitaire;

                    totalRavitaillement += prixTotal;
                });

                $('#total_ravitaillement').val(totalRavitaillement.toFixed(2));
                calculerNetPayerEtDette(); // Appeler cette fonction pour mettre à jour le net à payer et la dette
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

            // Écouteurs d'événements pour le champ prix unitaire dans le tableau
            $('#vaches-table-body').on('input', '.prix-vente', function() {
                calculerPrixTotal();
            });

            // Écouteurs d'événements pour les champs total remise et montant payé
            $('#total_remise, #montant_paye').on('input', function() {
                calculerNetPayerEtDette();
            });


            // Ajouter une nouvelle ligne de produit
            $('#add-product-row').click(function() {
                var newRow = `<tr>
                                <td>
                                            <div class="form-group">
                                                <!-- Menu déroulant avec recherche Select2 -->
                                                <select name="operation_id" id="operation_id"
                                                    class="form-control select2">
                                                    <option value="" selected disabled>Selectionnez le numéro
                                                        de l'opération</option>
                                                    @foreach ($vaches as $vache)
                                                        <option value="{{ $vache->id }}">{{ $vache->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                </td>
                                <td><input type="numeric" name="prix_vente[]" class="form-control prix-vente" required></td>
                                <td><button type="button" class="btn btn-danger remove-product-row">X</button></td> <!-- Bouton de suppression -->
                            </tr>`;
                $('#vaches-table-body').append(newRow);
            });

            // Supprimer une ligne de produit
            $(document).on('click', '.remove-product-row', function() {
                $(this).closest('tr').remove();
            });

        });
    </script>

</body>
