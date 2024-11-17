
document.addEventListener('DOMContentLoaded', function () {
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

    typeClientSelect.addEventListener('change', function () {
        if (typeClientSelect.value === 'Client Comptoir') {
            clientComptoirDiv.style.display = 'block';
            clientFideleDiv.style.display = 'none';
            phoneDiv.style.display = 'block';
            phoneInput.readOnly = false; // Make phone input editable
            phoneInput.value = ''; // Clear phone input value
            detteDiv.style.display = 'none';
            adressediv.style.display = 'none';
        } else if (typeClientSelect.value === 'Client Fidèle') {
            clientComptoirDiv.style.display = 'none';
            clientFideleDiv.style.display = 'block';
            phoneDiv.style.display = 'block';
            phoneInput.readOnly = true; // Make phone input readonly
            detteDiv.style.display = 'block';
            adressediv.style.display = 'block';
        } else {
            clientComptoirDiv.style.display = 'none';
            clientFideleDiv.style.display = 'none';
            phoneDiv.style.display = 'none';
            detteDiv.style.display = 'none';
            adressediv.style.display = 'none';
        }
    });

    clientFideleSelect.addEventListener('change', function () {
        var selectedOption = clientFideleSelect.options[clientFideleSelect.selectedIndex];
        phoneInput.value = selectedOption.getAttribute('data-phone');
        detteInput.value = selectedOption.getAttribute('data-dette');
        addresseInput.value = selectedOption.getAttribute('data-addresse');
    });

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

        $(document).ready(function() {

            // Soumission du formulaire d'ajout de fournisseur
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
                    url: '/addclient',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        // Affichage d'un message de succès
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Le Client a été ajouté avec succès.',

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
                            text: 'Une erreur est survenue lors de l\'ajout du client.',

                            confirmButtonText: 'OK', // Texte du bouton "OK"
                            customClass: {
                                confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
                            }
                        });
                    }
            });
    });
        });

