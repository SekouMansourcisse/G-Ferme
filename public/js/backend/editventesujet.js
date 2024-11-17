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
       $('#ajoutercategoriesBtn').click(function() {
           $('.categorie-checkbox:checked').each(function() {
               var bandeId = $(this).data('id');
               var bande = $(this).data('nom');
               var cheptel = $(this).data('quantite');
               var categorieNom = $(this).data('nom');

               var row = '<tr>' +
                   '<td>' + bande + '</td>' +

                   '<td><input type="number" class="form-control qte_vente" name="qte_vente[' + bandeId +
                   ']" required></td>' +
                   '<td><input type="number" class="form-control prix_unitaire" name="prixUnitaire[' +
                   bandeId + ']" required></td>' +
                   '<td><input type="number" class="form-control montant_total" name="Montant_total[' +
                   bandeId + ']" required readonly></td>' +
                   '<td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>' +
                   '</tr>';

               $('#categorieTableBody').append(row);
           });

           $('#categorieModal').modal('hide');
       });
