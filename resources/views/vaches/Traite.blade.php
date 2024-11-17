
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="page-title" id="titre">
                    <h4>Traites</h4>
                    <h6>Liste Traites</h6>
                </div>
                @can('create operation sur les vaches')
                <div class="page-btn">
                    <button id="addTraiteBtn" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Démarrer la Traite
                        </button>
                </div>
                @endcan
            </div>
            <div class="card col-md-12">

                <div class="card-body" id="TraiteList">
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
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                            src="{{ asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                            src="{{ asset('assets/img/icons/excel.svg')}}" alt="img"></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                            src="{{ asset('assets/img/icons/printer.svg')}}" alt="img"></a>
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
                                        <input type="text" class="datetimepicker cal-icon"
                                            placeholder="Choose Date">
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
                                                src="{{ asset('assets/img/icons/search-whites.svg')}}" alt="img"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>traite</th>
                                    <th>Production Matin</th>
                                    <th>Production Soir</th>
                                    <th>Quantité de Lait produite</th>
                                    <th>Date de Traite</th>
                                    @can('edit operation sur les vaches')
                                    <th>Actions</th>
                                    @endcan

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($traites as $traite)
                                <tr data-id="{{$traite->id}}"  data-prodM="{{$traite->production_matin}}"
                                    data-prodS="{{$traite->production_soir}}" data-date="{{ $traite->date_production }}">
                                    <td>{{ $traite->vache->nom}}</td>
                                    <td>{{ $traite->production_matin }} (Litres)</td>
                                    <td>{{ $traite->production_soir}} (Litres) </td>
                                    <td>{{ $traite->production_matin + $traite->production_soir }} (Litres)</td>

                                    <td>{{ $traite->date_production }}</td>
                                    @can('edit operation sur les vaches')
                                        <td>

                                            <a href="javascript:void(0);" class="me-3 edit-traite-btn"
                                            data-bs-toggle="modal" data-bs-target="#editTraiteModal">
                                            <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                        </a>

                                            @can('delete operation sur les vaches')
                                            <a class="me-3 delete-traite-btn" href="javascript:void(0);" onclick="confirmDelete2({{ $traite->id }})">
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
             <div class="card-body" id="addTraiteForm" style="display:none;">

                <form method="POST" id="add-form" action="{{ route('laitieres.store') }}">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="vache_id" id="vache_id" value="{{$vache->id}}">
                        <!-- Champ pour le nom de la ferme -->
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="nom">Date de Traite</label>
                                <input type="date" class="form-control" name="date" id="date"
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                            </div>
                        </div>

                        <!-- Champ pour le type de ferme -->
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="race">Quantite Lait Matin(en Litre)</label>
                                <input type="number" name="qte_m" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="race">Quantite Lait Soir(en Litre)</label>
                                <input type="number" name="qte_s" class="form-control" required>
                            </div>
                        </div>

                        <!-- Boutons pour soumettre ou annuler -->
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                            <button type="button" class="btn btn-cancel" onclick="window.history.back();">Annuler</button>
                        </div>
                    </div>
                </form>

             </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editTraiteModal" tabindex="-1" aria-labelledby="editTraiteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTraiteModalLabel">Éditer traitement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTraiteform" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <input type="hidden" name="traite_id" id="traite_id">
                    <div class="row">
                        <input type="hidden" name="vache_id" id="vache_id" value="{{$vache->id}}">
                        <!-- Champ pour le nom de la ferme -->
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="nom">Date de Traite</label>
                                <input type="date" class="form-control" name="date_production" id="edit_date"
                                 required>
                            </div>
                        </div>

                        <!-- Champ pour le type de ferme -->
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="race">Quantite Lait Matin(en Litre)</label>
                                <input type="number" name="production_matin"  id="edit_qte_m" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="race">Quantite Lait Soir(en Litre)</label>
                                <input type="number" name="production_soir" id="edit_qte_s" class="form-control" required>
                            </div>
                        </div>
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
<script src="{{ asset('assets/js/moment.min.js')}}"></script>
<script>
    document.getElementById('addTraiteBtn').addEventListener('click', function() {
        // Affiche le formulaire et cache la liste
        document.getElementById('TraiteList').style.display = 'none';
        document.getElementById('addTraiteForm').style.display = 'block';
        document.getElementById('addTraiteBtn').style.display = 'none';
        // Met à jour le texte du titre
        var titreH4 = document.querySelector('#titre h4');
        var titreH6 = document.querySelector('#titre h6');
        titreH4.textContent = 'Traites';
        titreH6.textContent = 'Ajouter une nouvelle Traite';


    });

    document.getElementById('cancelAddTraiteBtn').addEventListener('click', function() {
        // Cache le formulaire et affiche la liste
        document.getElementById('addTraiteForm').style.display = 'none';
        document.getElementById('TraiteList').style.display = 'block';

        // Réinitialise le texte du titre
        var titreH4 = document.querySelector('#titre h4');
        var titreH6 = document.querySelector('#titre h6');
        titreH4.textContent = 'Traites';
        titreH6.textContent = 'Liste des Traites';

            });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ouverture du modal d'édition et pré-remplissage
        document.querySelectorAll('.edit-traite-btn').forEach(button => {
            button.addEventListener('click', function() {
                const vacheRow = this.closest('tr');

                // Extraire les données et remplir le modal
                document.getElementById('traite_id').value = vacheRow.getAttribute('data-id');
                document.getElementById('edit_qte_s').value = vacheRow.getAttribute(
                    'data-prodS');
                document.getElementById('edit_qte_m').value = vacheRow.getAttribute(
                    'data-prodM');
                document.getElementById('edit_date').value = vacheRow.getAttribute(
                    'data-date');

            });
        });
        // Soumission du formulaire d'édition
        $('#editTraiteform').submit(function(e) {
            e.preventDefault();
            var vacheId = $("#editTraiteform input[name=traite_id]").val();

            // Récupération des données du formulaire
            var formData = $(this).serialize();

            // Vous pouvez envoyer les données du formulaire pour l'édition du produit ici en utilisant AJAX
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '/laitieres/' + vacheId,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Gérer la réponse ici
                    // Par exemple, fermer la modal, afficher un message de succès, etc.
                    $('#editTraiteModal').modal('hide');
                    // Afficher un message de succès (optionnel)
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'traite modifié avec succès.',
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

    function confirmDelete2(id) {
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
                fetch(`/laitieres/${id}`, {
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
                                'traite supprimée.',
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

