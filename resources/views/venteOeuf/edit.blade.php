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
                        <h4>Interface d'edition de vente d'oeufs</h4>
                        <h6>Modification d'une vente</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('vente-oeufs.update', $venteOeuf->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <input type="hidden" name="type" value="vente-oeuf">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date Vente <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date" value="{{ $venteOeuf->Date_op }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_client">Type de client<span class="text-danger">**</span></label>
                                        <select name="type_client" id="type_client" class="form-control" required>
                                            <option value="" disabled>Selectionnez le type de client</option>
                                            <option value="Client Comptoir" {{ $venteOeuf->client == null ? 'selected' : '' }}>Client Comptoir</option>
                                            <option value="Client Fidèle" {{ $venteOeuf->client != null ? 'selected' : '' }}>Client Fidèle</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12" id="client_comptoir_div" style="{{ $venteOeuf->type_client == 'Client Comptoir' ? 'display: block;' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="client_comptoir">Nom & Prénom du client<span class="text-danger">**</span></label>
                                        <input type="text" name="client_comptoir" id="client_comptoir" class="form-control" value="{{ $venteOeuf->client_comptoir }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="client_fidele_div" style="{{ $venteOeuf->type_client == 'Client Fidèle' ? 'display: block;' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="client_id">Nom & Prénom du client<span class="text-danger">**</span></label>
                                        <select name="client_id" id="client_id" class="form-control">
                                            <option value="" disabled>Selectionnez un client</option>
                                            @foreach($clients as $client)
                                            <option value="{{ $client->id }}" data-phone="{{ $client->phone }}" data-dette="{{ $client->dette_initiale }}" data-addresse="{{ $client->adresse_physique }}" {{ $venteOeuf->client_id == $client->id ? 'selected' : '' }}>
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
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ $venteOeuf->phone }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="dette_div" style="{{ $venteOeuf->client_id ? 'display: block;' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label>Dette Actuelle<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="dette_initiale" style="color: red; font-weight: bold;" id="dette_initiale" class="form-control" value="{{ $venteOeuf->dette_initiale }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="adresse_div" style="{{ $venteOeuf->client_id ? 'display: block;' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label>Adresse<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="addresse" style="color: red; font-weight: bold;" id="addresse" class="form-control" value="{{ $venteOeuf->addresse }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#categorieModal">
                                        Ajouter la liste des categories
                                    </button>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Type d'Oeufs</th>
                                                    <th>Avec Alvéoles</th>
                                                    <th>Nombre de plateaux</th>
                                                    <th>Prix plateaux</th>
                                                    <th>Montant Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="categorieTableBody">
                                                @foreach($infosOeufArray as $info)
                                                <tr>
                                                    <td>{{ $info['categorie']->Denomination ?? 'Non spécifié' }}</td>
                                                    <td><input type="checkbox" class="categorie-checkbox" name="alv"></td>
                                                    <td>
                                                        <input type="text" class="form-control qte_plateaux" name="qte_plateaux[{{ $info['categorie']->id }}]" value="{{ $info['quantite'] }}" required>
                                                    </td>
                                                    <td class="prix_plateaux">{{ $info['prixplateaux'] ?? 'Non spécifié' }}</td>
                                                    <td>
                                                        <input type="text" class="form-control montant_total" name="Montant_total" value="{{ $info['prixplateaux'] * $info['quantite'] }}" required readonly>
                                                    </td>
                                                    <td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>
                                                </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row mt-4">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_ravitaillement">Montant Vente</label>
                                        <input type="number" class="form-control" name="total_ravitaillement" id="total_ravitaillement" value="{{ $venteOeuf->TotalRavitaillement }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_remise">Total remise <span class="text-danger">**</span></label>
                                        <input type="number" class="form-control" name="total_remise" id="total_remise" value="{{$venteOeuf->totalRemise}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="net_payer">Net à payer</label>
                                        <input type="number" class="form-control" name="net_payer" id="net_payer" value="{{ $venteOeuf->TotalRavitaillement-$venteOeuf->totalRemise }}" required readonly>
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
                                            <option value="{{ $compte->id }}" {{ $venteOeuf->payer_par == $compte->id ? 'selected' : '' }}>{{ $compte->Denomination }}</option>
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

    <!-- Modal pour la liste des categories -->
    <div class="modal fade" id="categorieModal" tabindex="-1" aria-labelledby="categorieModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categorieModalLabel">Liste des categories</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Type Oeufs</th>

                                <th>Quantité stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $categorie)

                                <tr>
                                    <td>
                                        <input type="checkbox" class="categorie-checkbox" data-id="{{ $categorie->id }}"  data-nom="{{ $categorie->Denomination }}" data-stock="{{ $categorie->qteEnplateaux }}" data-prix="{{ $categorie->PrixPlateaux }}">
                                    </td>

                                    <td>{{ $categorie->Denomination }}</td>
                                    <td>{{ $categorie->qteEnplateaux }}plateaux</td>
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
clientFideleSelect.addEventListener('change', function () {
    var selectedOption = clientFideleSelect.options[clientFideleSelect.selectedIndex];
    phoneInput.value = selectedOption.getAttribute('data-phone');
    detteInput.value = selectedOption.getAttribute('data-dette');
    addresseInput.value = selectedOption.getAttribute('data-addresse');
});

// Calcul automatique des totaux
function calculateTotals() {
    var totalRavitaillement = 0;
    var totalRemise = parseFloat(totalRemiseInput.value) || 0;

    $('#categorieTableBody tr').each(function() {
        var $row = $(this);
        var prixPlateaux = parseFloat($row.find('.prix_plateaux').text()) || 0;
        var qtePlateaux = parseFloat($row.find('.qte_plateaux').val()) || 0;
        var montantTotal = prixPlateaux * qtePlateaux;

        totalRavitaillement += montantTotal;
        $row.find('.montant_total').val(montantTotal.toFixed(2));
    });

    totalRavitaillementInput.value = totalRavitaillement.toFixed(2);
    var netPayer = totalRavitaillement - totalRemise;
    netPayerInput.value = netPayer.toFixed(2);

    var montantPaye = parseFloat(montantPayeInput.value) || 0;
    var detteAPayer = netPayer - montantPaye;
    detteAPayerInput.value = detteAPayer.toFixed(2);
}

// Écouteurs pour recalculer les totaux lors des modifications des champs de remise, paiement ou quantité
$(document).on('input', '#total_remise, #montant_paye, .qte_plateaux', calculateTotals);

// Calcul initial lors du chargement de la page
calculateTotals();
$(document).ready(function() {
        $('#ajoutercategoriesBtn').click(function() {
            $('.categorie-checkbox:checked').each(function() {
                var categorieId = $(this).data('id');
                var categorieNom = $(this).data('nom');
                var prixPlateaux = $(this).data('prix');
                var row = '<tr>' +
                    '<td>' + categorieNom + '</td>' +
                    '<td><input type="checkbox" class="categorie-checkbox" name="alv"></td>'+
                    '<td><input type="text" class="form-control qte_plateaux" name="qte_plateaux[' + categorieId + ']" required></td>'+
                    '<td class="prix_plateaux">' + prixPlateaux + '</td>' +
                    '<td><input type="text" class="form-control montant_total" name="Montant_total" required readonly></td>'+
                    '<td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>' +
                    '</tr>';

                $('#categorieTableBody').append(row);
            });

            $('#categorieModal').modal('hide');
            calculateTotals();
        });

        $(document).on('click', '.remove-product-btn', function() {
            $(this).closest('tr').remove();
            calculateTotals();
        });
    });
    </script>

</body>
