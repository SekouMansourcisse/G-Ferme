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
                        <h4>Liste d'alimentation des vaches</h4>
                        <h6>Alimentation</h6>
                    </div>
                    @can('create alimentation_betail')
                    <div class="page-btn">
                        <a href="{{url('betail/create')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img">Démarrer
                            l'alimentation</a>
                    </div>
                    @endcan
                </div>
                @if (session('success'))
                <div id="success-message" class="alert alert-success alert-dismissible fade show"
                    role="alert">
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" href="{{url('listVachepdf')}}"><img
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
                                        <th>Date d'alimentation</th>
                                        <th>Type de nourriture</th>
                                        <th>Qte consommée</th>
                                        <th>Periode</th>
                                        <th>Commentaire</th>
                                        @can('edit alimentation_betail')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alimentations as $alimentation)
                                        <tr data-id="{{$alimentation->id}}" data-date-alimentation="{{ $alimentation->date_alimentation }}"
                                            data-produit_id="{{ $alimentation->Produit->id }}"
                                            data-periode="{{ $alimentation->periode }}"
                                            data-commentaire="{{ $alimentation->commentaire }}"
                                            data-quantite="{{ $alimentation->quantite }}">
                                            <td>{{ $alimentation->date_alimentation }}</td>
                                            @if ($alimentation->Produit->Denomination)
                                            <td>{{ $alimentation->Produit->Denomination }}</td>
                                            @else
                                            <td>Produit supprimée</td>
                                            @endif

                                            <td>{{ $alimentation->quantite }}</td>
                                            <td>{{ $alimentation->periode }}</td>
                                            <td>{{ $alimentation->commentaire }}</td>

                                            @can('edit alimentation_betail')
                                            <td>
                                                <a href="javascript:void(0);" class="me-3 edit-item-btn"
                                                data-bs-toggle="modal" data-bs-target="#editVacheModal">
                                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                            </a>
                                                @can('delete alimentation_betail')
                                                <a class="me-3 delete-item-btn" href="javascript:void(0);" onclick="confirmDelete({{ $alimentation->id }})">
                                                    <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="Delete">
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
    <div class="modal fade" id="editVacheModal" tabindex="-1" aria-labelledby="editVacheModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVacheModalLabel">Éditer alimentation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editVacheForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="aliment_id" id="aliment_id">
                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="date_alimentation">Date d'Alimentation</label>
                                    <input type="date" name="date_alimentation" id="date_alimentation"
                                        class="form-control"
                                         required>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="operation_id">Nom du produit<span
                                            class="text-danger">**</span></label>
                                    <!-- Menu déroulant avec recherche Select2 -->
                                    <select name="produit_id" id="produit_id"
                                        class="form-control select2">
                                        <option value="" selected disabled>Selectionnez le numéro
                                            de l'opération
                                        </option>
                                        @foreach ($produits as $produit)
                                            <option value="{{ $produit->id }}">
                                                {{ $produit->Denomination }}(Stock
                                                actuelle:{{ $produit->qte_stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="Age" class="form-label">Quantité(Kg)</label>
                                    <input type="text" class="form-control" id="quantite"
                                        name="quantite" required>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="type_elevage">Periode <span
                                            class="text-danger">*</span></label>
                                    <select name="periode" id="periode" class="form-control"
                                        required>
                                        <option value="" selected disabled>Selectionner la
                                            periode</option>
                                        <option value="Matin">Matin</option>
                                        <option value="Soir">Soir</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="Resume" class="form-label">Commentaire</label>
                                    <textarea class="form-control" id="commentaire" name="commentaire" required></textarea>
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
    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ouverture du modal d'édition et pré-remplissage
            document.querySelectorAll('.edit-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const vacheRow = this.closest('tr');

                    // Extraire les données et remplir le modal
                    document.getElementById('aliment_id').value = vacheRow.getAttribute('data-id');
                    document.getElementById('produit_id').value = vacheRow.getAttribute('data-produit_id');
                    document.getElementById('quantite').value = vacheRow.getAttribute('data-quantite');
                    document.getElementById('periode').value = vacheRow.getAttribute(
                        'data-periode');
                    document.getElementById('date_alimentation').value = vacheRow.getAttribute(
                        'data-date-alimentation');
                    document.getElementById('commentaire').value = vacheRow.getAttribute(
                        'data-commentaire');
                });
            });
                // Soumission du formulaire d'édition
    $('#editVacheForm').submit(function(e) {
        e.preventDefault();
        var vacheId = $("#editVacheForm input[name=aliment_id]").val();

        // Récupération des données du formulaire
        var formData = $(this).serialize();

        // Vous pouvez envoyer les données du formulaire pour l'édition du produit ici en utilisant AJAX
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/betail/' + vacheId,
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
                    text: 'alimentation modifié avec succès.',
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
            fetch(`/betail/${id}`, {
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
                        'alimentation supprimée.',
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
