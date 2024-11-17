@include('partials._head')
<style>
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table i,
    .table img {
        width: 24px;
        height: 24px;
        cursor: pointer;
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
                        <h4>Liste des Vaches</h4>
                        <h6>Gerer vos Vaches</h6>
                    </div>
                    @can('create vaches')
                        <div class="page-btn">
                            <a href="{{ url('vaches/create') }}" class="btn btn-added"><img
                                    src="{{ asset('assets/img/icons/plus.svg') }}" alt="img">Ajouter
                                Vache</a>
                        </div>
                    @endcan
                </div>
                @if (session('success'))
                    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="{{ asset('assets/img/icons/filter.svg') }}" alt="img">
                                        <span><img src="{{ asset('assets/img/icons/closes.svg') }}"
                                                alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img
                                            src="{{ asset('assets/img/icons/search-white.svg') }}" alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"
                                            href="{{ url('listVachepdf') }}"><img
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
                                <form action="#" class="dropdown">
                                    <div class="searchinputs" id="dropdownMenuClickable" data-bs-auto-close="false">
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
                                        <th>Nom</th>
                                        <th>Race</th>
                                        <th>Type d'elevage</th>
                                        <th>Date d'arrivée</th>
                                        <th>État de Santé</th>
                                        @can('edit vaches')
                                            <th>Actions</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vaches as $vache)
                                        <tr data-id="{{ $vache->id }}" data-nom="{{ $vache->nom }}"
                                            data-race-id="{{ $vache->race->id }}"
                                            data-type-elevage="{{ $vache->type_elevage }}"
                                            data-date-naissance="{{ $vache->date_naissance }}"
                                            data-etat-sante="{{ $vache->etat_sante }}">

                                            <td>{{ $vache->nom }}</td>
                                            <td>{{ $vache->race->denomination }}</td>
                                            <td>{{ $vache->type_elevage }}</td>
                                            <td>{{ $vache->date_naissance }}</td>
                                            <td>{{ $vache->etat_sante }}</td>
                                            @can('edit vaches')
                                                <td>
                                                    <a href="javascript:void(0);" id="view-item-btn"
                                                        class="me-3 view-item-btn">
                                                        <i data-feather="thermometer"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="me-3 edit-item-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editVacheModal">
                                                        <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                                    </a>
                                                    @can('delete vaches')
                                                    <a class="me-3 delete-item-btn" href="javascript:void(0);" onclick="confirmDelete({{ $vache->id }})">
                                                        <img src="assets/img/icons/delete.svg" alt="Delete">
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

            </div>
        </div>
    </div>
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Etat de Santé</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <!-- Formulaire de paiement -->
                    <form action="{{ route('EtatSante') }}" method="POST">
                        @csrf
                        <input type="hidden" name="vache_id" id="vache_id">

                        <div class="form-group">
                            <label for="typeFerme">Etat de Santé<span class="text-danger">*</span></label>
                            <select name="etat" id="etat" class="form-control" required>
                                <option value="" selected disabled>Selectionner l'etat de sante de la vache
                                </option>
                                <option value="Bonne Sante">En Bonne Santé</option>
                                <option value="Malade">Malade</option>

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
    <div class="modal fade" id="editVacheModal" tabindex="-1" aria-labelledby="editVacheModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVacheModalLabel">Éditer Vache</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editVacheForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="vache_id" id="editVacheId">

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="editNom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="race" class="form-label">Race</label>
                            <select class="form-select" id="editRace" name="race_id" required>
                                @foreach ($races as $race)
                                    <option value="{{ $race->id }}">{{ $race->denomination }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="type_elevage" class="form-label">Type d'élevage</label>
                            <input type="text" class="form-control" id="editTypeElevage" name="type_elevage"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="date_naissance" class="form-label">Date de Naissance</label>
                            <input type="date" class="form-control" id="editDateNaissance" name="date_naissance"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="etat_sante" class="form-label">État de Santé</label>
                            <input type="text" class="form-control" id="editEtatSante" name="etat_sante"
                                required>
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

    @include('partials.script')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            // Lorsqu'on clique sur un bouton "view-item-btn"
            $(document).on('click', '.view-item-btn', function() {
                // Récupérer l'ID du traitement à partir de la ligne parent (tr)
                var traitementId = $(this).closest('tr').data('id');

                // Assigner l'ID du traitement à l'input hidden du modal
                $('#vache_id').val(traitementId);

                // Afficher le modal
                $('#paymentModal').modal('show');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ouverture du modal d'édition et pré-remplissage
            document.querySelectorAll('.edit-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const vacheRow = this.closest('tr');

                    // Extraire les données et remplir le modal
                    document.getElementById('editVacheId').value = vacheRow.getAttribute('data-id');
                    document.getElementById('editNom').value = vacheRow.getAttribute('data-nom');
                    document.getElementById('editRace').value = vacheRow.getAttribute('data-race-id');
                    document.getElementById('editTypeElevage').value = vacheRow.getAttribute(
                        'data-type-elevage');
                    document.getElementById('editDateNaissance').value = vacheRow.getAttribute(
                        'data-date-naissance');
                    document.getElementById('editEtatSante').value = vacheRow.getAttribute(
                        'data-etat-sante');
                });
            });
                // Soumission du formulaire d'édition
    $('#editVacheForm').submit(function(e) {
        e.preventDefault();
        var vacheId = $("#editVacheForm input[name=vache_id]").val();

        // Récupération des données du formulaire
        var formData = $(this).serialize();

        // Vous pouvez envoyer les données du formulaire pour l'édition du produit ici en utilisant AJAX
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/vaches/' + vacheId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Gérer la réponse ici
                // Par exemple, fermer la modal, afficher un message de succès, etc.
                $('#editProduitModal').modal('hide');
                // Afficher un message de succès (optionnel)
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'La vache a été modifié avec succès.',
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
                    errorMessage += value[0] + '\n'; // Ajoutez chaque erreur à un message
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: errorMessage || 'Une erreur est survenue lors de la modification.',
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
            fetch(`/vaches/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Supprimé !',
                        'La vache a été supprimée.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur !', data.message, 'error');
                }
            })
            .catch(error => Swal.fire('Erreur !', 'Une erreur s\'est produite lors de la suppression.', 'error'));
        }
    });
}
    </script>



</body>
