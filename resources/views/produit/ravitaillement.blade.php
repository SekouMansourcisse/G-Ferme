@include('partials._head')
<style>
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }
    .table i,.table img {
    width: 24px;
    height: 24px;
    cursor: pointer;
}

@media print {
    /* Masquer tout sauf le tableau */
    body * {
      visibility: hidden;
    }

    /* Afficher uniquement le tableau */
    table, table * {
      visibility: visible;
    }

    /* Optionnel: Centrer le tableau sur la page imprimée */
    table {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
    }

    /* Masquer les boutons et autres éléments interactifs */
    .no-print {
      display: none;
    }
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
                        <h4>Liste des ravitaillements</h4>
                        <h6>Gérer les ravitaillements des produits</h6>
                    </div>
                    @can('create Ravitaillements')
                    <div class="page-btn">
                        <a href="{{url('add_viewravi')}}" class="btn btn-added" >
                            <img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img"> Ajouter un ravitaillement
                        </a>
                    </div>
                    @endcan
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="{{ asset('assets/img/icons/filter.svg')}}" alt="img">
                                        <span><img src="{{ asset('assets/img/icons/closes.svg')}}" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg')}}"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="{{ url('ravitaillement/pdf')}}" title="pdf"><img
                                                src="{{ asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="{{ url('ravitaillement/excel')}}" title="excel"><img
                                                src="{{ asset('assets/img/icons/excel.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" onclick="window.print()" data-bs-placement="top" title="print"><img
                                                src="{{ asset('assets/img/icons/printer.svg')}}" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card" id="filter_inputs">
                            <div class="card-body pb-0">
                                <form action="#" class="dropdown">
                                    <div class="searchinputs" id="dropdownMenuClickable"
                                        data-bs-auto-close="false">
                                        <input type="text" placeholder="Search">
                                        <div class="search-addon">
                                            <span><i data-feather="x-circle" class="feather-14"></i></span>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th>Produit</th>
                                        <th>Montant Ravitaillement</th>
                                        <th>Remise</th>
                                        <th>Montant payé</th>
                                        <th>Reste à payer</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ravitaillements as $ravitaillement)
                                    <tr data-id="{{ $ravitaillement->id}}">
                                        <td>{{ $ravitaillement->Date_op }}</td>
                                        <td>{{ $ravitaillement->NomPrenomFournisseur }}</td>
                                        <td>
                                            @foreach(explode(',', $ravitaillement->Produit) as $produit)
                                                @php
                                                    $produitDetails = explode('*', $produit);
                                                    if(count($produitDetails) == 4) {
                                                        list($id, $quantite, $prix_unitaire, $prix_total) = $produitDetails;
                                                        $produitNom = App\Models\Produit::find($id)->Denomination;
                                                    } else {
                                                        $id = $quantite = $prix_unitaire = $prix_total = null;
                                                        $produitNom = 'Produit inconnu';
                                                    }
                                                @endphp
                                                {{ $produitNom }} ({{ $quantite }}Kg)
                                            @endforeach
                                        </td>
                                        <td>{{ $ravitaillement->TotalRavitaillement }}</td>
                                        <td>{{ $ravitaillement->totalRemise }}</td>
                                        <td>{{ $ravitaillement->montant_payé }}</td>
                                        <td>{{ $ravitaillement->montantDette }}</td>
                                        <td>
                                            <a class="me-3 info-item-btn" href="javascript:void(0);" data-id="{{ $ravitaillement->id }}">
                                                <img src="{{ asset('assets/img/icons/eye1.svg')}}" alt="info">
                                            </a>
                                            @can('edit Ravitaillements')
                                            <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>
                                            @endcan
                                            @can('delete Ravitaillements')
                                            <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                                                <img src="{{asset('assets/img/icons/delete.svg')}}" alt="img">
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<!-- Modal pour ajouter un ravitaillement -->
<!-- Modal d'édition -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier le ravitaillement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="edit-ravitaillement-form">
                    @csrf
                    <input type="hidden" name="ravitaillement_id" id="editRavitaillementId">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_date">Date ravitaillement <span class="text-danger">**</span></label>
                                <input type="date" class="form-control" name="date" id="edit_date" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_fournisseur_id">Nom du fournisseur <span class="text-danger">**</span></label>
                                <select name="fournisseur_id" id="edit_fournisseur_id" class="form-control" required>
                                    <option value="" selected disabled>Multi fournisseur</option>
                                    @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#addFournisseurModal">Nouveau fournisseur</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Dénomination du produit</th>
                                    <th>Qte acheté</th>
                                    <th>Prix unitaire</th>
                                    <th>Prix total</th>
                                </tr>
                            </thead>
                            <tbody id="edit-produits-table-body">
                                <!-- Rempli par JavaScript -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-secondary" id="add-edit-product-row">Ajouter un produit</button>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_total_ravitaillement">Total ravitaillement</label>
                                <input type="number" step="0.01" class="form-control" name="total_ravitaillement" id="edit_total_ravitaillement" required readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_total_remise">Total remise <span class="text-danger">**</span></label>
                                <input type="number" step="0.01" class="form-control" name="total_remise" id="edit_total_remise" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_net_payer">Net à payer</label>
                                <input type="number" step="0.01" class="form-control" name="net_payer" id="edit_net_payer" required readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_montant_paye">Montant payé <span class="text-danger">**</span></label>
                                <input type="number" step="0.01" class="form-control" name="montant_paye" id="edit_montant_paye" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_dette_a_paye">Dette à payer</label>
                                <input type="number" step="0.01" class="form-control" name="dette_a_paye" id="edit_dette_a_paye" required readonly>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="edit_payer_par">Payé par</label>
                                <select name="payer_par" id="edit_payer_par" class="form-control" required>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails du ravitaillement -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Détail du ravitaillement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <p><strong>Nom du fournisseur:</strong> <span id="fournisseurNom"></span></p>
                <p><strong>Date du ravitaillement:</strong> <span id="dateRavitaillement"></span></p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Dénomination du produit</th>
                            <th>Qte acheté</th>
                            <th>Prix unitaire</th>
                            <th>Prix total</th>
                        </tr>
                    </thead>
                    <tbody id="produitsTableBody"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total achat:</strong></td>
                            <td><span id="totalRavitaillement"></span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total remise:</strong></td>
                            <td><span id="totalRemise"></span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Net à payer:</strong></td>
                            <td><span id="netPayer"></span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Montant payé:</strong></td>
                            <td><span id="montantPaye"></span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Reste à payer:</strong></td>
                            <td><span id="resteAPayer"></span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>

        </div>
    </div>
</div>

    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script>
$(document).ready(function() {
    $(document).on('click', '.info-item-btn', function() {
    var id = $(this).data('id');

    $.ajax({
        url: '/ravitaillements/' + id,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#fournisseurNom').text(response.ravitaillement.NomPrenomFournisseur);
                $('#dateRavitaillement').text(response.ravitaillement.Date_op);

                var produitsTableBody = '';
                response.produits.forEach(function(produit) {
                    produitsTableBody += '<tr>' +
                        '<td>' + produit.Denomination + '</td>' +
                        '<td>' + produit.quantite + '</td>' +
                        '<td>' + produit.prix_unitaire + '</td>' +
                        '<td>' + produit.prix_total + '</td>' +
                        '</tr>';
                });
                $('#produitsTableBody').html(produitsTableBody);

                $('#totalRavitaillement').text(response.ravitaillement.TotalRavitaillement);
                $('#totalRemise').text(response.ravitaillement.totalRemise);
                $('#netPayer').text(response.ravitaillement.TotalRavitaillement - response.ravitaillement.totalRemise);
                $('#montantPaye').text(response.ravitaillement.montant_payé);
                $('#resteAPayer').text(response.ravitaillement.montantDette);

                $('#infoModal').modal('show');
            }
        }
    });
});

});

    </script>

    <script>
$(document).ready(function() {
    // Remplir le formulaire de modification avec les données existantes
    $('.edit-item-btn').click(function() {
    var ravitaillementId = $(this).closest('tr').data('id');
    $.ajax({
        url: '/ravitaillements/' + ravitaillementId,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                var ravitaillement = response.ravitaillement;
                var produits = response.produits;

                $('#editRavitaillementId').val(ravitaillement.id);
                $('#edit_date').val(ravitaillement.Date_op);
                $('#edit_fournisseur_id').val(ravitaillement.Fournisseur);

                var produitsTableBody = '';
                produits.forEach(function(produit) {
                    produitsTableBody += '<tr>' +
                        '<td><select name="produit_id[]" class="form-control" required>' +
                        // Ajouter l'option du produit concerné en premier
                        '<option value="' + produit.id + '" selected>' + produit.Denomination + '</option>' +
                        // Les autres options
                        '@foreach($produits as $produitOption)' +
                        '<option value="{{ $produitOption->id }}"' +
                        (produit.id == '{{ $produitOption->id }}' ? ' disabled' : '') +
                        '>{{ $produitOption->Denomination }}</option>' +
                        '@endforeach' +
                        '</select></td>' +
                        '<td><input type="number" name="qte_produit[]" class="form-control" value="' + produit.quantite + '" required></td>' +
                        '<td><input type="number" step="0.01" name="prix_unitaire_p[]" class="form-control" value="' + produit.prix_unitaire + '" required></td>' +
                        '<td><input type="number" step="0.01" name="prix_total_p[]" class="form-control" value="' + produit.prix_total + '" required readonly></td>' +
                        '</tr>';
                });
                $('#edit-produits-table-body').html(produitsTableBody);

                $('#edit_total_ravitaillement').val(ravitaillement.TotalRavitaillement);
                $('#edit_total_remise').val(ravitaillement.totalRemise);
                $('#edit_net_payer').val(ravitaillement.TotalRavitaillement - ravitaillement.totalRemise);
                $('#edit_montant_paye').val(ravitaillement.montant_payé);
                $('#edit_dette_a_paye').val(ravitaillement.montantDette);
                $('#edit_payer_par').val(ravitaillement.Payer_par);

                $('#editModal').modal('show');
            }
        }
    });
});


    // Enregistrer les modifications
    $('#edit-ravitaillement-form').submit(function(event) {
        event.preventDefault();

        var formData = $(this).serialize();
        var ravitaillementId = $('#editRavitaillementId').val();

        $.ajax({
            url: '/Editravitaillements/' + ravitaillementId,
            type: 'PUT',
            data: formData,
            success: function(response) {
                if(response.success) {
                    $('#editModal').modal('hide');
                    location.reload(); // Recharge la page pour refléter les modifications
                } else {
                    alert('Une erreur est survenue lors de la mise à jour.');
                }
            }
        });
    });

    $('.delete-item-btn').click(function() {
    var ravitaillementId = $(this).closest('tr').data('id');

    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-cancel',
        },
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/suppravitaillement/' + ravitaillementId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Supprimé !',
                            'Le ravitaillement a été supprimé.',
                            'success'
                        ).then(() => {
                            location.reload(); // Recharge la page pour refléter les modifications
                        });
                    } else {
                        Swal.fire(
                            'Erreur !',
                            'Une erreur est survenue lors de la suppression.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Erreur !',
                        'Une erreur est survenue lors de la suppression.',
                        'error'
                    );
                }
            });
        }
    });
});

});


    </script>
</body>
