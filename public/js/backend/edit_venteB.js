$(document).ready(function() {
    // Fonction pour calculer le total du ravitaillement
    function calculerPrixTotal() {
        let totalRavitaillement = 0;
        $('#montant_paye').val(0);
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
