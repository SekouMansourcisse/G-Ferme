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
                        <h4>Interface de Retour Vente</h4>
                        <h6>Enregistrer une retour vente</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('operation-retours.store2') }}">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="type" value="vente-autre">
                                <div class="col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date Vente <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_operation">Type de vente retourner<span class="text-danger">**</span></label>
                                        <select name="type_operation" id="type_operation" class="form-control" required>
                                            <option value="" selected disabled>Selectionnez le type de operation</option>
                                            <option value="vente-oeuf">Vente d'Oeuf</option>
                                            <option value="vente-sujet">Vente de sujet</option>
                                            <option value="vente-autre">Autre vente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-12 col-sm-12" id="operation_fidele_div" style="display: none;">
                                    <div class="form-group">
                                        <label for="operation_id">Numero Operation<span class="text-danger">**</span></label>
                                        <select name="operation_id" id="operation_id" class="form-control">
                                            <option value="" selected disabled>Selectionnez un le numero de l'operation</option>
                                            @foreach($operations as $operation)
                                            <option value="{{ $operation->id }}">
                                                OPN-{{ $operation->id}}
                                            </option>
                                            @endforeach
                                        </select>

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
                                                    <th>Qte retour</th>
                                                    <th>Prix total</th>
                                                    <th>Action</th> <!-- Nouvelle colonne pour le bouton de suppression -->
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
                            <div class="row mt-4">

                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_retour">Total retour</label>
                                        <input type="number" class="form-control" name="montant_retour" id="montant_retour" required readonly>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-6 col-12">
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

    @include('partials.script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('assets/version/bootstrap.min.js')}}"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    $('#type_operation').on('change', function() {
        var typeOperation = $(this).val();
        $.ajax({
            url: '{{ route('fetch.operations1') }}',
            type: 'GET',
            data: { type: typeOperation },
            success: function(response) {
                var options = '<option value="" selected disabled>Selectionnez un le numero de l\'operation</option>';
                $.each(response.operations, function(index, operation) {
                    options += '<option value="' + operation.id + '">OPN-' + operation.id + '</option>';
                });
                $('#operation_id').html(options);
                $('#operation_fidele_div').show();
            }
        });
    });

    $('#operation_id').on('change', function() {
        var operationId = $(this).val();
        var typeOperation = $('#type_operation').val();
        $.ajax({
            url: '{{ route('fetch.operation.details1') }}',
            type: 'GET',
            data: { id: operationId, type: typeOperation },
            success: function(response) {
                var rows = '';
                $.each(response.details, function(index, detail) {
                    rows += '<tr>' +
                        '<td>' + detail.libelle + '</td>' +
                        '<td>' + detail.qte_vendu + '</td>' +
                        '<td>' + detail.prix_unitaire + '</td>' +
                        '<td><input type="number" class="form-control qte_retour" name="qte_retour[' + detail.id + ']" required></td>' +
                        '<td><input type="number" class="form-control montant_total" name="montant_total[' + detail.id + ']" required readonly></td>' +
                        '<td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>' +
                        '</tr>';
                });
                $('#categorieTableBody').html(rows);
            }
        });
    });

    $(document).on('input', '.qte_retour', function() {
        var $row = $(this).closest('tr');
        var qte_vendu = $row.find('td').eq(1).text();
        var prix_unitaire = $row.find('td').eq(2).text();
        var qte_retour = $(this).val();
        var montant_total = qte_retour * prix_unitaire;

        $row.find('.montant_total').val(montant_total);

        calculateTotalRetour();
    });

    function calculateTotalRetour() {
        var totalRetour = 0;
        $('.montant_total').each(function() {
            totalRetour += parseFloat($(this).val()) || 0;
        });
        $('#montant_retour').val(totalRetour);
    }
});

    </script>
</body>
