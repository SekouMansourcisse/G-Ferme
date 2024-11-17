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
                        <h4>Interface de vente de produit</h4>
                        <h6>Enregistrer une vente</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('vente-autres.update', $operation->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="vente-autre">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date Vente <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date"
                                            value="{{ old('date', $operation->Date_op) }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_client">Type de client<span
                                                class="text-danger">**</span></label>
                                        <select name="type_client" id="type_client" class="form-control" required>
                                            <option value="" disabled>Selectionnez le type de client</option>
                                            <option value="Client Comptoir"
                                                {{ $operation->client == null ? 'selected' : '' }}>Client Comptoir
                                            </option>
                                            <option value="Client Fidèle"
                                                {{ $operation->client != null ? 'selected' : '' }}>Client Fidèle
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12" id="client_comptoir_div" style="{{ $operation->client ? 'display: none;' : '' }}">
                                    <div class="form-group">
                                        <label for="client_comptoir">Nom & Prénom du client<span class="text-danger">**</span></label>
                                        <input type="text" name="client_comptoir" id="client_comptoir" class="form-control"
                                            value="{{ old('client_comptoir', $operation->NomPrenomClient) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="client_fidele_div" style="{{ $operation->client ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="client_id">Nom & Prénom du client<span class="text-danger">**</span></label>
                                        <select name="client_id" id="client_id" class="form-control">
                                            <option value="" disabled>Selectionnez un client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}"
                                                    {{ $operation->client == $client->id ? 'selected' : '' }}
                                                    data-phone="{{ $client->phone }}" data-dette="{{ $client->dette_initiale }}" data-addresse="{{ $client->adresse_physique}}">
                                                    {{ $client->nom }} {{ $client->prenom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#addFournisseurModal">Nouveau Client</button>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="phone_div">
                                    <div class="form-group">
                                        <label>Numéro de Téléphone <b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            value="{{ old('phone', $operation->phone) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="dette_div" style="{{ $operation->client ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label>Dette Actuelle<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="dette_initiale" style="color: red ; text:bold;" id="dette_initiale" class="form-control"
                                            value="{{ old('dette_initiale', $operation->dette_initiale) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="adresse_div" style="{{ $operation->client ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label>Adresse<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="addresse" style="color: red ; text:bold;" id="addresse" class="form-control"
                                            value="{{ old('addresse', $operation->adresse_physique) }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#produitModal">
                                        Selectionnez les produits a vendre
                                    </button>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Dénomination du produit</th>
                                                    <th>Quantité stock</th>
                                                    <th>Qte Vendu</th>
                                                    <th>Prix unitaire</th>
                                                    <th>Prix total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="categorieTableBody">
                                                @foreach($produitsInfos as $produitInfo)
                                                    <tr>
                                                        <td>{{ $produitInfo['produit']->Denomination }}</td>
                                                        <td>{{ $produitInfo['produit']->qte_stock }}</td>
                                                        <td><input type="number" name="qte_vente[{{ $produitInfo['produit']->id }}]" value="{{ old("qte_vente.{$produitInfo['produit']->id}", $produitInfo['quantite']) }}" class="form-control qte_vente"></td>
                                                        <td><input type="number" name="prixUnitaire[{{ $produitInfo['produit']->id }}]" value="{{ old("prixUnitaire.{$produitInfo['produit']->id}", $produitInfo['prixUnitaire']) }}" class="form-control prix_unitaire"></td>
                                                        <td><input type="number" name="Montant_total[{{ $produitInfo['produit']->id }}]" value="{{ old("Montant_total.{$produitInfo['produit']->id}", $produitInfo['montantTotal']) }}" class="form-control montant_total" readonly></td>
                                                        <td><button type="button" class="btn btn-danger btn-sm remove-product">X</button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_ravitaillement">Montant Vente</label>
                                        <input type="number" class="form-control" name="total_ravitaillement" id="total_ravitaillement" value="{{ $operation->TotalRavitaillement }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_remise">Total remise <span class="text-danger">**</span></label>
                                        <input type="number" class="form-control" name="total_remise" id="total_remise" value="{{$operation->totalRemise}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="net_payer">Net à payer</label>
                                        <input type="number" class="form-control" name="net_payer" id="net_payer" value="{{ $operation->TotalRavitaillement-$operation->totalRemise }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_paye">Montant payé <span class="text-danger">**</span></label>
                                        <input type="number" class="form-control" name="montant_paye" id="montant_paye"  required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dette_a_paye">Dette à payer</label>
                                        <input type="number" class="form-control" name="dette_a_paye" id="dette_a_paye"  required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="payer_par">Payé par</label>
                                        <select name="payer_par" id="payer_par" class="form-control" required>
                                            <option value="" disabled>Selectionner un compte</option>
                                            @foreach($comptes as $compte)
                                            <option value="{{ $compte->id }}" {{ $operation->payer_par == $compte->id ? 'selected' : '' }}>{{ $compte->Denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <button type="button" class="btn btn-cancel" onclick="window.history.back();">Annuler</button>
                        </form>


                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal pour la liste des produits -->
    <div class="modal fade" id="produitModal" tabindex="-1" aria-labelledby="produitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="produitModalLabel">Liste des produits</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Denomination du produits</th>

                                <th>Quantité stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produits as $produit)

                                <tr>
                                    <td>
                                        <input type="checkbox" class="categorie-checkbox" data-id="{{ $produit->id }}"  data-nom="{{ $produit->Denomination }}" data-stock="{{ $produit->qte_stock }}">
                                    </td>

                                    <td>{{ $produit->Denomination }}</td>
                                    <td>{{ $produit->qte_stock }}Kg</td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="ajoutercategoriesBtn">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour l'ajout de nouveau client -->
    <div class="modal fade" id="addFournisseurModal" tabindex="-1" aria-labelledby="addFournisseurModalLabel" aria-hidden="true">
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
                                    <input type="text" name="dette_initiale" id="redevance_initiale" class="form-control">
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
                                    <input type="text" name="num_whatsapp" id="num_whatsapp" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Adresse Physique <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="adresse_physique" id="adresse_physique" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Informations Supplémentaires <b><span class="text-danger">*</span></b></label>
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
    <script src="{{ asset('assets/version/bootstrap.min.js')}}"></script>
    <script src="{{ asset('js/backend/autrevente.js')}}"></script>
    <script>
               // Récupération des éléments du formulaire
       var typeClientSelect = document.getElementById('type_client');
       var clientComptoirDiv = document.getElementById('client_comptoir_div');
       var clientFideleDiv = document.getElementById('client_fidele_div');
       var phoneDiv = document.getElementById('phone_div');
       var detteDiv = document.getElementById('dette_div');
       var adressediv = document.getElementById('adresse_div');
       var clientFideleSelect = document.getElementById('client_id');
       var phoneInput = document.getElementById('phone');
       var detteInput = document.getElementById('dette_initiale');
       var totalRavitaillementInput = document.getElementById('total_ravitaillement');
       var totalRemiseInput = document.getElementById('total_remise');
       var netPayerInput = document.getElementById('net_payer');
       var montantPayeInput = document.getElementById('montant_paye');
       var detteAPayerInput = document.getElementById('dette_a_paye');
       var addresseInput = document.getElementById('addresse');

       // Fonction pour mettre à jour l'affichage des champs en fonction du type de client
       function updateClientTypeFields() {
           if (typeClientSelect.value === 'Client Comptoir') {
               clientComptoirDiv.style.display = 'block';
               clientFideleDiv.style.display = 'none';
               phoneDiv.style.display = 'block';
               phoneInput.readOnly = false;
               phoneInput.value = ''; // Efface la valeur du téléphone pour Client Comptoir
               detteDiv.style.display = 'none';
               adressediv.style.display = 'none';
           } else if (typeClientSelect.value === 'Client Fidèle') {
               clientComptoirDiv.style.display = 'none';
               clientFideleDiv.style.display = 'block';
               phoneDiv.style.display = 'block';
               phoneInput.readOnly = true;
               detteDiv.style.display = 'block';
               adressediv.style.display = 'block';

               // Si un client est déjà sélectionné, mettez à jour les champs de téléphone, adresse et dette
               var selectedOption = clientFideleSelect.options[clientFideleSelect.selectedIndex];
               phoneInput.value = selectedOption.getAttribute('data-phone');
               detteInput.value = selectedOption.getAttribute('data-dette');
               addresseInput.value = selectedOption.getAttribute('data-addresse');
           } else {
               clientComptoirDiv.style.display = 'none';
               clientFideleDiv.style.display = 'none';
               phoneDiv.style.display = 'none';
               detteDiv.style.display = 'none';
               adressediv.style.display = 'none';
           }
       }

       // Appeler la fonction pour définir l'affichage initial lors du chargement de la page
       updateClientTypeFields();

       // Écouteur pour détecter les changements du type de client
       typeClientSelect.addEventListener('change', updateClientTypeFields);

       // Mettre à jour les informations de contact lorsque le client fidèle est sélectionné
       clientFideleSelect.addEventListener('change', function() {
           var selectedOption = clientFideleSelect.options[clientFideleSelect.selectedIndex];
           phoneInput.value = selectedOption.getAttribute('data-phone');
           detteInput.value = selectedOption.getAttribute('data-dette');
           addresseInput.value = selectedOption.getAttribute('data-addresse');
       });

       document.addEventListener('DOMContentLoaded', function () {
    $('#ajoutercategoriesBtn').click(function() {
        $('.categorie-checkbox:checked').each(function() {
            var produitId = $(this).data('id');
            var produit = $(this).data('nom');
            var cheptel = $(this).data('stock');


            var row = '<tr>' +
                '<td>' + produit + '</td>' +
                '<td>' + cheptel + '</td>' +
                '<td><input type="number" class="form-control qte_vente" name="qte_vente[' + produitId + ']" required></td>' +
                '<td><input type="number" class="form-control prix_unitaire" name="prixUnitaire[' + produitId + ']" required></td>' +
                '<td><input type="number" class="form-control montant_total" name="Montant_total[' + produitId + ']" required readonly></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>' +
                '</tr>';

            $('#categorieTableBody').append(row);
        });

        $('#categorieModal').modal('hide');
    });

    $(document).on('input', '.qte_vente, .prix_unitaire', function() {
        var $row = $(this).closest('tr');
        var qte_vente = $row.find('.qte_vente').val();
        var prix_unitaire = $row.find('.prix_unitaire').val();
        var montant_total = qte_vente * prix_unitaire;

        $row.find('.montant_total').val(montant_total);

        calculateTotals();
    });

    $(document).on('click', '.remove-product-btn', function() {
        $(this).closest('tr').remove();
        calculateTotals();
    });

    $('#total_remise, #montant_paye').on('input', function() {
        calculateNetAPayer();
        calculateDetteAPayer();
    });

    function calculateTotals() {
        var totalRavitaillement = 0;
        $('.montant_total').each(function() {
            totalRavitaillement += parseFloat($(this).val()) || 0;
        });
        $('#total_ravitaillement').val(totalRavitaillement);

        calculateNetAPayer();
        calculateDetteAPayer();
    }

    function calculateNetAPayer() {
        var totalRavitaillement = parseFloat($('#total_ravitaillement').val()) || 0;
        var totalRemise = parseFloat($('#total_remise').val()) || 0;
        var netAPayer = totalRavitaillement - totalRemise;
        $('#net_payer').val(netAPayer);
    }

    function calculateDetteAPayer() {
        var netAPayer = parseFloat($('#net_payer').val()) || 0;
        var montantPaye = parseFloat($('#montant_paye').val()) || 0;
        var detteAPayer = netAPayer - montantPaye;
        $('#dette_a_paye').val(detteAPayer);
    }
});
    </script>
</body>
