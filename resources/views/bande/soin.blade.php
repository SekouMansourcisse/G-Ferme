<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="page-title" id="titre">
                    <h4>Soins</h4>
                    <h6>Liste Soins</h6>
                </div>
                @can('create operation sur bande')
                @if ($bande->etat==1)
                <div class="page-btn">
                    <button id="addSoinBtn" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg') }}"
                            alt="img">Programmer un Soin
                    </button>
                </div>
                @endif
                @endcan

            </div>
            <div class="card col-md-12">

                <div class="card-body" id="SoinList">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-path">
                                <a class="btn btn-filter" id="filter_search">
                                    <img src="{{ asset('assets/img/icons/filter.svg') }}" alt="img">
                                    <span><img src="{{ asset('assets/img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                            </div>
                            <div class="search-input">
                                <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}"
                                        alt="img"></a>
                            </div>
                        </div>
                        <div class="wordset">
                            <ul>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                            src="{{ asset('assets/img/icons/pdf.svg') }}" alt="img"></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                            src="{{ asset('assets/img/icons/excel.svg') }}" alt="img"></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                            src="{{ asset('assets/img/icons/printer.svg') }}" alt="img"></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter User Name">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter Email">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" class="datetimepicker cal-icon" placeholder="Choose Date">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <select class="select">
                                            <option>Disable</option>
                                            <option>Enable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                                    <div class="form-group">
                                        <a class="btn btn-filters ms-auto"><img
                                                src="{{ asset('assets/img/icons/search-whites.svg') }}"
                                                alt="img"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date soin</th>
                                    <th>Denomination soin</th>
                                    <th>Description soin</th>
                                    <th>Produit </th>
                                    <th>statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($soins as $soin)
                                    <tr data-id="{{ $soin->id }}" data-date="{{ $soin->date }}"
                                        data-nom="{{ $soin->denomination }}"
                                        data-description="{{ $soin->description }}"
                                        data-produit="{{ $soin->Produit }}" data-etat="{{ $soin->etat }}"
                                        data-qte_utilise="{{ $soin->qte_utilise }}">
                                        <td>{{ $soin->date }}</td>
                                        <td>{{ $soin->denomination }}</td>
                                        <td>{{ $soin->description }}</td>
                                        <td>{{ $soin->Produit }}</td>
                                        <td>{{ $soin->etat == 1 ? 'en attente' : 'Effectué' }}</td>
                                        @can('edit operation sur les vaches')
                                            <td>

                                                <a href="javascript:void(0);" id="view-item-btn" class="me-3 view-item-btn">
                                                    <img src=" {{ asset('assets/img/icons/time.svg') }}" alt="">
                                                </a>
                                                <a href="javascript:void(0);" class="me-3 edit-item-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editVacheModal">
                                                    <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                                </a>

                                                @can('delete operation sur les vaches')
                                                <a class="me-3 delete-item-btn" href="javascript:void(0);" onclick="confirmDelete({{ $soin->id }})">
                                                    <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="Delete">
                                                </a>
                                                @endcan
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
            <div class="card">
                <div class="card-body" id="addSoinForm" style="display:none;">

                    <form id="treatmentForm" method="POST" action="{{ route('traitements.store') }}">
                        @csrf
                        <input type="hidden" name="bande_id" value="{{ $bande->id }}">
                        <input type="hidden" name="type" value="bande">
                        <div class="form-group">
                            <label for="date">Date prévue du traitement</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="denomination">Dénomination du traitement</label>
                            <input type="text" class="form-control" id="denomination" name="denomination"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description du traitement</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="denomination">Produit à utilisé</label>
                            <input type="text" class="form-control" id="produit" name="Produit" required>
                        </div>
                        <div class="form-group">
                            <label for="denomination">Quantité Produit à administrer</label>
                            <input type="text" class="form-control" id="qte_utilise" name="qte_utilise">
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                            <button type="button" class="btn btn-cancel" onclick="window.history.back();">Annuler</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editVacheModal" tabindex="-1" aria-labelledby="editVacheModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVacheModalLabel">Éditer traitement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVacheForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="vache_id" value="{{ $bande->id }}">
                    <input type="hidden" name="type" value="bande">
                    <input type="hidden" name="soin_id" id="soin_id">
                    <div class="form-group">
                        <label for="date">Date prévue du traitement</label>
                        <input type="date" class="form-control" id="edit_date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="denomination">Dénomination du traitement</label>
                        <input type="text" class="form-control" id="edit_denomination" name="denomination"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description du traitement</label>
                        <textarea class="form-control" id="edit_description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="denomination">Produit à utilisé</label>
                        <input type="text" class="form-control" id="edit_produit" name="Produit" required>
                    </div>
                    <div class="form-group">
                        <label for="denomination">Quantité Produit à administrer</label>
                        <input type="text" class="form-control" id="edit_qte_utilise" name="qte_utilise">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Le statut du traitement</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body">
                <!-- Formulaire de paiement -->
                <form action="{{ route('validertraitement') }}" method="POST">
                    @csrf
                    <input type="hidden" name="traitement_id" id="traitement_id">

                    <div class="form-group">
                        <label for="typeFerme">Statut Traitement<span class="text-danger">*</span></label>
                        <select name="etat" id="etat" class="form-control" required>
                            <option value="" selected disabled>Selectionner le statut du traitement</option>
                            <option value="1">Traitement en attente</option>
                            <option value="2">Traitement effuctué</option>

                            <!-- Ajoutez d'autres options si nécessaire -->
                        </select>
                    </div>


                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@include('partials.script')
<script src="{{ asset('assets/version/bootstrap.min.js')}}"></script>
<script>
$(document).ready(function() {
    // Lorsqu'on clique sur un bouton "view-item-btn"
    $(document).on('click', '.view-item-btn', function() {
        // Récupérer l'ID du traitement à partir de la ligne parent (tr)
        var traitementId = $(this).closest('tr').data('id');

        // Assigner l'ID du traitement à l'input hidden du modal
        $('#traitement_id').val(traitementId);

        // Afficher le modal
        $('#paymentModal').modal('show');
    });
});

    document.getElementById('addSoinBtn').addEventListener('click', function() {
        // Affiche le formulaire et cache la liste
        document.getElementById('SoinList').style.display = 'none';
        document.getElementById('addSoinForm').style.display = 'block';
        document.getElementById('addSoinBtn').style.display = 'none';
        // Met à jour le texte du titre
        var titreH4 = document.querySelector('#titre h4');
        var titreH6 = document.querySelector('#titre h6');
        titreH4.textContent = 'Soins';
        titreH6.textContent = 'Ajouter une nouvelle Soin';


    });

    document.getElementById('cancelAddSoinBtn').addEventListener('click', function() {
        // Cache le formulaire et affiche la liste
        document.getElementById('addSoinForm').style.display = 'none';
        document.getElementById('SoinList').style.display = 'block';

        // Réinitialise le texte du titre
        var titreH4 = document.querySelector('#titre h4');
        var titreH6 = document.querySelector('#titre h6');
        titreH4.textContent = 'Soins';
        titreH6.textContent = 'Liste des Soins';

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ouverture du modal d'édition et pré-remplissage
        document.querySelectorAll('.edit-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const vacheRow = this.closest('tr');

                // Extraire les données et remplir le modal
                document.getElementById('soin_id').value = vacheRow.getAttribute('data-id');
                document.getElementById('edit_denomination').value = vacheRow.getAttribute(
                    'data-nom');
                document.getElementById('edit_description').value = vacheRow.getAttribute(
                    'data-description');
                document.getElementById('edit_produit').value = vacheRow.getAttribute(
                    'data-produit');
                document.getElementById('edit_date').value = vacheRow.getAttribute(
                    'data-date');
                document.getElementById('edit_qte_utilise').value = vacheRow.getAttribute(
                    'data-qte_utilise');
            });
        });
        // Soumission du formulaire d'édition
        $('#editVacheForm').submit(function(e) {
            e.preventDefault();
            var vacheId = $("#editVacheForm input[name=soin_id]").val();

            // Récupération des données du formulaire
            var formData = $(this).serialize();

            // Vous pouvez envoyer les données du formulaire pour l'édition du produit ici en utilisant AJAX
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '/traitements/' + vacheId,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Gérer la réponse ici
                    // Par exemple, fermer la modal, afficher un message de succès, etc.
                    $('#editVacheModal').modal('hide');
                    // Afficher un message de succès (optionnel)
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'traitement modifié avec succès.',
                        confirmButtonColor: '#d33', // Couleur du bouton "OK"
                        confirmButtonText: 'OK', // Texte du bouton "OK"
                        customClass: {
                            confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
                        }
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';

                    // Parcourez les erreurs et construisez un message
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] +
                        '\n'; // Ajoutez chaque erreur à un message
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: errorMessage ||
                            'Une erreur est survenue lors de la modification.',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });
                }

            });
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-cancel',
            },
            confirmButtonText: 'Oui, supprimer !'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/traitements/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Supprimé !',
                                'traitement supprimée.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Erreur !', data.message, 'error');
                        }
                    })
                    .catch(error => Swal.fire('Erreur !', 'Une erreur s\'est produite lors de la suppression.',
                        'error'));
            }
        });
    }
</script>
