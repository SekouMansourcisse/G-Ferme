@include('partials._head')
<style>
    .red-text {
    color: red;
}

</style>
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
                        <h4>Interface de Retour vente </h4>
                        <h6>Enregistrer une retour vente</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('operation-retours.store') }}">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="type" value="vente-autre">
                                <div class="col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date Vente <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-12 col-sm-12" id="operation_fidele_div">
                                    <div class="form-group">
                                        <label for="operation_id">Numéro d'Opération<span class="text-danger">**</span></label>
                                        <!-- Menu déroulant avec recherche Select2 -->
                                        <select name="operation_id" id="operation_id" class="form-control select2">
                                            <option value="" selected disabled>Selectionnez le numéro de l'opération</option>
                                            @foreach($operations as $operation)
                                            <option value="{{ $operation->commande_id }}">
                                                CommandeN°-{{ $operation->commande_id }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            </div>
                            <div class="row mt-4" id="montant_details" style="display: none;">

                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_retour">Montant initiale payé pour la commande</label>
                                        <input type="number" style="color: green" class="form-control" name="montant_paye" id="montant_paye" required readonly>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_retour">Montant Dette initiale pour la commande</label>
                                        <input type="number" style="color: red" class="form-control" name="montant_dette" id="montant_dette" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Libellé</th>
                                                    <th>Qte Vendu</th>
                                                    <th>Prix unitaire</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th id="qte">Qte retour ou remplacer</th>

                                                    <th>Prix total</th>
                                                    <th>Type Retour</th>

                                                </tr>
                                            </thead>
                                            <tbody id="categorieTableBody">
                                                <!-- Les produits sélectionnés seront ajoutés ici -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="row mt-4" id="payment_details">

                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_retour">Montant à Rembourser</label>
                                        <input type="number" class="form-control" name="montant_retour" id="montant_retour" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_retour">Montant perte</label>
                                        <input type="number" class="form-control" name="montant_perte" id="montant_perte" required readonly>
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

    @include('partials.script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('assets/version/bootstrap.min.js')}}"></script>
    <script>
        $(document).ready(function() {
    // Activer Select2 pour l'élément select avec recherche
    $('#operation_id').select2({
        placeholder: 'Selectionnez le numéro de l\'opération',
        allowClear: true,
        width: '100%' // Pour occuper toute la largeur
    });
});

document.addEventListener('DOMContentLoaded', function () {

    $('#operation_search').on('input', function () {
        var searchTerm = $(this).val().toLowerCase();

        // Filtrer les options du select en fonction du texte entré dans l'input
        $('#operation_id option').each(function () {
            var optionText = $(this).text().toLowerCase();
            var optionValue = $(this).val().toLowerCase();

            if (optionText.includes(searchTerm) || optionValue.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });


$('#operation_id').on('change', function() {
    var operationId = $(this).val();
    var type=$('#type_operation').val();
    $.ajax({
        url: 'fetch/operation/details',
        type: 'GET',
        data: { commande_id: operationId },
        success: function(response) {
            var rows = '';
            // Insérer les détails de l'opération
            $.each(response.details, function(index, detail) {
                rows += '<tr>' +
                    '<td>' + detail.libelle + '</td>' +
                    '<td>' + detail.qte_vendu + '</td>' +
                    '<td>' + detail.prix_unitaire + '</td>' +
                    '<td><input type="hidden" class="form-control operationId" name="operationId[' + detail.id + ']" value="'+detail.operationId+'" required></td>' +
                    '<td><input type="hidden" class="form-control typeVente" name="typeVente[' + detail.id + ']" value="'+detail.type_vente+'" required></td>' +
                    '<td><input type="number" class="form-control qte_retour" name="qte_retour[' + detail.id + ']" ></td>' +
                    '<td><input type="number" class="form-control montant_total" name="montant_total[' + detail.id + ']"  readonly></td>' +
                    '<td><select name="type_operation[' + detail.id + ']" id="type_operation_' + detail.id + '" class="form-control" >' +
                        '<option value="" selected disabled>Selectionnez le type d\'opération</option>' +
                        '<option value="Remboursement">Remboursement</option>' +
                        '<option value="Remplacement">Remplacement</option>' +
                    '</select></td>' +
                '</tr>';
            });
            $('#categorieTableBody').html(rows);


            // Afficher les montants payés et dette
            $('#montant_paye').val(response.montant_paye);
            $('#montant_dette').val(response.montant_dette);

            // Afficher la section des montants
            $('#montant_details').show();
        }
    });
});


$(document).on('input', '.qte_retour', function() {
    var $row = $(this).closest('tr');
    var qte_vendu = parseFloat($row.find('td').eq(1).text());
    var prix_unitaire = parseFloat($row.find('td').eq(2).text());
    var qte_retour = parseFloat($(this).val());
    var montant_total = qte_retour * prix_unitaire;

    $row.find('.montant_total').val(montant_total);

    calculateTotalRetour();
});

$(document).on('change', 'select[name^="type_operation"]', function() {
    calculateTotalRetour();
});

function calculateTotalRetour() {
    var totalRetour = 0;
    var totalPerte = 0;

    // Parcours chaque ligne du tableau pour calculer les montants
    $('#categorieTableBody tr').each(function() {
        var $row = $(this);
        var montant_total = parseFloat($row.find('.montant_total').val()) || 0;
        var typeOperation = $row.find('select[name^="type_operation"]').val();

        // Vérifie si le type d'opération est "Remboursement" ou "Remplacement"
        if (typeOperation === 'Remboursement') {
            totalRetour += montant_total; // Ajoute au montant à rembourser
        } else if (typeOperation === 'Remplacement') {
            totalPerte += montant_total; // Ajoute au montant de la perte
        }
    });

    // Mets à jour les inputs avec les montants calculés
    $('#montant_retour').val(totalRetour);
    $('#montant_perte').val(totalPerte);
}

});

    </script>
</body>
