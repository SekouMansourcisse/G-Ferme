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
                        <form method="POST" action="{{ route('operation-retours.update', $operationRetour->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <input type="hidden" name="type" value="vente-autre">
                                <div class="col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date Vente <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date" value="{{ old('date', $operationRetour->date_op) }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_operation">Type de vente retourner <span class="text-danger">**</span></label>
                                        <select name="type_operation" id="type_operation" class="form-control" required readonly>
                                            <option value="" selected disabled>Selectionnez le type de operation</option>
                                            <option value="vente-oeuf" {{ $operationRetour->TypeVenteR == 'vente-oeuf' ? 'selected' : '' }}>Vente d'Oeuf</option>
                                            <option value="vente-sujet" {{ $operationRetour->TypeVenteR == 'vente-sujet' ? 'selected' : '' }}>Vente de sujet</option>
                                            <option value="vente-autre" {{ $operationRetour->TypeVenteR == 'vente-autre' ? 'selected' : '' }}>Autre vente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12" id="operation_fidele_div" >
                                    <div class="form-group">
                                        <label for="operation_id">Numero Operation <span class="text-danger">**</span></label>
                                        <select name="operation_id" id="operation_id" class="form-control" required>
                                            <option value="" selected disabled>Selectionnez un le numero de l'operation</option>
                                            @foreach($operations as $operationItem)
                                            <option value="{{ $operationItem->id }}" {{ $operationRetour->numero_vente == $operationItem->id ? 'selected' : '' }}>
                                                OPN-{{ $operationItem->id }}
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
                                                @foreach ($details as $index => $detail)
                                                <tr>
                                                    <td>{{ $detail['libelle'] }}</td>
                                                    <td>{{ $detail['qte_vendu'] }}</td>
                                                    <td>{{ $detail['prix_unitaire'] }}</td>
                                                    <td>
                                                        @php
                                                            // Récupérer la quantité retournée correspondante
                                                            $qteRetour = explode(';', $operationRetour->qteR);
                                                            $qteRetourActuelle = 0; // Valeur par défaut
                                                            foreach ($qteRetour as $item) {
                                                                list($elementId, $qte) = explode('*', $item);
                                                                if ($elementId == $detail['id']) {
                                                                    $qteRetourActuelle = $qte; // Met à jour la quantité retournée
                                                                    break;
                                                                }
                                                            }
                                                        @endphp
                                                        <input type="number" class="form-control qte_retour" name="qte_retour[{{ $detail['id'] }}]" min="0" value="{{ $qteRetourActuelle }}" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control montant_total" name="montant_total[{{ $detail['id'] }}]" value="{{ $detail['prix_unitaire'] * $qteRetourActuelle }}" readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button>
                                                    </td>
                                                </tr>
                                                @endforeach
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
                                        <input type="number" class="form-control" name="montant_retour" id="montant_retour" value="{{ $operationRetour->Montant_R }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="payer_par">Payé par</label>
                                        <select name="payer_par" id="payer_par" class="form-control" required>
                                            <option value="" selected disabled>Selectionner un compte</option>
                                            @foreach($comptes as $compte)
                                            <option value="{{ $compte->id }}" {{ $operationRetour->payer_par == $compte->id ? 'selected' : '' }}>{{ $compte->Denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                <button type="button" class="btn btn-cancel" onclick="window.history.back();">Annuler</button>
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

    const selectElement = document.getElementById('operation_id');
    const initialValue = selectElement.value;

    selectElement.addEventListener('change', function() {
        this.value = initialValue; // Réinitialise à la valeur initiale
    });
    const selectElement2 = document.getElementById('type_operation');
    const initialValue2 = selectElement2.value;

    selectElement2.addEventListener('change', function() {
        this.value = initialValue2; // Réinitialise à la valeur initiale
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
