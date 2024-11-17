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


    $(document).ready(function () {

        $('#ajoutercategoriesBtn2').click(function () {
            $('.categorie-checkbox:checked').each(function () {
                var categorieId = $(this).data('id');
                var categorieNom = $(this).data('nom');
                var prixPlateaux = $(this).data('prix');
                var row = '<tr>' +
                    '<td>' + categorieNom + '</td>' +
                    '<td><input type="checkbox" class="categorie-checkbox" name="alv"></td>' +
                    '<td><input type="text" class="form-control qte_plateaux" name="qte_plateaux[' + categorieId + ']" required></td>' +
                    '<td class="prix_plateaux">' + prixPlateaux + '</td>' +
                    '<td><input type="text" class="form-control montant_total2" name="Montant_total" required readonly></td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>' +
                    '</tr>';

                $('#categorieTableBody').append(row);
            });

            $('#categorieModal').modal('hide');

        });
        $('#ajoutercategoriesBtn3').click(function () {
            $('.produit-checkbox:checked').each(function () {
                var produitId = $(this).data('id');
                var produit = $(this).data('nom');
                var cheptel = $(this).data('stock');
                var prix=$(this).data('prix');


                var row = '<tr>' +
                    '<td>' + produit + '</td>' +
                    '<td>' + cheptel + '</td>' +
                    '<td><input type="number" class="form-control qte_vente" name="qte_vente[' + produitId + ']" required></td>' +
                    '<td class="prix">'+ prix +'</td>'+
                    '<td><input type="number" class="form-control Montant_total" name="Montant_total[' + produitId + ']" required readonly></td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>' +
                    '</tr>';

                $('#categorieTableBody2').append(row);
            });

            $('#produitModal').modal('hide');
        });
        $('#ajoutercategoriesBtn1').click(function () {
            $('.categorie1-checkbox:checked').each(function () {
                var bandeId = $(this).data('id');
                var bande = $(this).data('nom');
                var cheptel = $(this).data('quantite');
                var categorieNom = $(this).data('nom');

                var row = '<tr>' +
                    '<td>' + bande + '</td>' +
                    '<td>' + cheptel + '</td>' +
                    '<td><input type="number" class="form-control qte_vente1" name="qte_vente1[' + bandeId + ']" required></td>' +
                    '<td><input type="number" class="form-control prix_unitaire1" name="prixUnitaire1[' + bandeId + ']" required></td>' +
                    '<td><input type="number" class="form-control montant_total1" name="Montant_total[' + bandeId + ']" required readonly></td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>' +
                    '</tr>';

                $('#categorieTableBody1').append(row);
            });

            $('#categorieModal1').modal('hide');
        });

        $(document).on('click', '.remove-product-btn', function () {
            $(this).closest('tr').remove();
        });

        // Sélection des éléments nécessaires
        const totalRavitaillementInput = document.getElementById('total_ravitaillement');
        const totalRemiseInput = document.getElementById('total_remise');
        const netPayerInput = document.getElementById('net_payer');


        // Fonction pour calculer le Montant Total pour les œufs
        function calculerMontantTotalOeufs() {
            let totalOeufs = 0;
            document.querySelectorAll('.qte_plateaux').forEach((input, index) => {
                const nbPlateaux = parseFloat(input.value) || 0;
                const prixPlateaux = parseFloat(document.querySelectorAll('.prix_plateaux')[index].textContent) || 0;
                totalOeufs += nbPlateaux * prixPlateaux;
            });
            return totalOeufs;
        }

        // Fonction pour calculer le Prix Total pour les bandes
        function calculerPrixTotalBandes() {
            let totalBandes = 0;
            document.querySelectorAll('.qte_vente1').forEach((input, index) => {
                const quantiteVendue = parseFloat(input.value) || 0;
                const prixUnitaire = parseFloat(document.querySelectorAll('.prix_unitaire1')[index].value) || 0;
                totalBandes += quantiteVendue * prixUnitaire;
            });
            return totalBandes;
        }

        // Fonction pour calculer le Prix Total pour les produits
        function calculerPrixTotalProduits() {
            let totalProduits = 0;
            document.querySelectorAll('.qte_vente').forEach((input, index) => {
                const quantiteVendue = parseFloat(input.value) || 0;
                const prixUnitaire = parseFloat(document.querySelectorAll('.prix')[index].textContent) || 0;
                totalProduits += quantiteVendue * prixUnitaire;
            });
            return totalProduits;
        }

        // Fonction pour calculer le Total Ravitaillement
        function calculerTotalRavitaillement() {
            const totalOeufs = calculerMontantTotalOeufs();
            const totalBandes = calculerPrixTotalBandes();
            const totalProduits = calculerPrixTotalProduits();
            const total = totalOeufs + totalBandes + totalProduits;
            totalRavitaillementInput.value = total.toFixed(2);
            calculerNetAPayer();
        }

        // Fonction pour calculer le Net à payer
        function calculerNetAPayer() {
            const totalRavitaillement = parseFloat(totalRavitaillementInput.value) || 0;
            const totalRemise = parseFloat(totalRemiseInput.value) || 0;
            const netAPayer = totalRavitaillement - totalRemise;
            netPayerInput.value = netAPayer.toFixed(2);

        }



        // Écouteurs d'événements pour recalculer lorsque les champs sont modifiés
        totalRemiseInput.addEventListener('input', calculerNetAPayer);


        $(document).on('input', '.qte_vente, .prix', function () {
            var $row = $(this).closest('tr');
            var qte_vente = $row.find('.qte_vente').val();
            var prix_unitaire = $row.find('.prix').text();
            var montant_total = qte_vente * prix_unitaire;

            $row.find('.Montant_total').val(montant_total);
            calculerTotalRavitaillement();

        });
        $(document).on('input', '.qte_vente1, .prix_unitaire1', function () {
            var $row = $(this).closest('tr');
            var qte_vente = $row.find('.qte_vente1').val();
            var prix_unitaire = $row.find('.prix_unitaire1').val();
            var montant_total = qte_vente * prix_unitaire;

            $row.find('.montant_total1').val(montant_total);
            calculerTotalRavitaillement();

        });
        $(document).on('input', '.qte_plateaux, .prix_plateaux', function () {
            $('#categorieTableBody tr').each(function () {
                var $row = $(this);
                var prixPlateaux = parseFloat($row.find('.prix_plateaux').text()) || 0;
                var qtePlateaux = parseFloat($row.find('.qte_plateaux').val()) || 0;
                var montantTotal = prixPlateaux * qtePlateaux;

                $row.find('.montant_total2').val(montantTotal.toFixed(2));
                calculerTotalRavitaillement();
            });

        });
    });
});
