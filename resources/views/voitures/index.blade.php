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
                        <h4>Liste des Voitures de service</h4>
                        <h6>Gerer vos Voitures</h6>
                    </div>
                    @can('create voitures')
                    <div class="page-btn">
                        <a href="{{url('voitures/create')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            Voiture</a>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" href="{{ url('listvoiturepdf')}}"><img
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
                                        <th>Numéro de Plaque</th>
                                        <th>Modèle</th>
                                        <th>Marque</th>
                                        <th>Année</th>
                                        <th>Commentaires</th>
                                        @can('edit voitures')
                                        <th>Actions</th>
                                        @endcan

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($voitures as $voiture)
                                        <tr data-id="{{$voiture->id}}">
                                            <td>{{ $voiture->plaque_immatriculation }}</td>
                                            <td>{{ $voiture->modele }}</td>
                                            <td>{{ $voiture->marque }}</td>
                                            <td>{{ $voiture->annee_fabrication }}</td>
                                            <td>{{ $voiture->commentaire }}</td>
                                            @can('edit voitures')
                                            <td>

                                            <a class="me-3 edit-item-btn"  href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>

                                            @can('delete voitures')
                                            <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                                                <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="img">
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
    <div class="modal fade" id="editVoitureModal" tabindex="-1" aria-labelledby="editVoitureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Ajout de 'modal-lg' pour une modal large -->
            <div class="modal-content">
                <form id="editVoitureForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVoitureModalLabel">Modifier Voiture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="marque" class="form-label">Marque</label>
                                <input type="text" class="form-control" name="marque">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="modele" class="form-label">Modèle</label>
                                <input type="text" class="form-control" name="modele">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="plaque_immatriculation" class="form-label">Plaque d'immatriculation</label>
                                <input type="text" class="form-control" name="plaque_immatriculation">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="annee_fabrication" class="form-label">Année de Fabrication</label>
                                <input type="number" class="form-control" name="annee_fabrication">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kilometrage" class="form-label">Kilométrage</label>
                                <input type="number" class="form-control" name="kilometrage">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="etat" class="form-label">État</label>
                                <input type="text" class="form-control" name="etat">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="commentaire" class="form-label">Commentaire</label>
                                <textarea class="form-control" name="commentaire"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
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
        $(document).ready(function() {
    // Ouvrir la modal d'édition
    $('.edit-item-btn').click(function() {
        var voitureId = $(this).closest('tr').data('id');

        $.ajax({
            url: '/voitures/' + voitureId + '/edit',
            type: 'GET',
            success: function(data) {
                // Remplir les champs de la modal d'édition avec les données
                $('#editVoitureModal input[name="marque"]').val(data.marque);
                $('#editVoitureModal input[name="modele"]').val(data.modele);
                $('#editVoitureModal input[name="plaque_immatriculation"]').val(data.plaque_immatriculation);
                $('#editVoitureModal input[name="annee_fabrication"]').val(data.annee_fabrication);
                $('#editVoitureModal input[name="kilometrage"]').val(data.kilometrage);
                $('#editVoitureModal input[name="etat"]').val(data.etat);
                $('#editVoitureModal textarea[name="commentaire"]').val(data.commentaire);
                $('#editVoitureModal').data('id', voitureId).modal('show');
            },
            error: function() {
                alert('Erreur lors de la récupération des données.');
            }
        });
    });

    // Soumettre la mise à jour
    $('#editVoitureForm').submit(function(e) {
        e.preventDefault();

        var voitureId = $('#editVoitureModal').data('id');
        var formData = $(this).serialize();

        $.ajax({
            url: '/voitures/' + voitureId,
            type: 'PUT',
            data: formData,
            success: function(response) {
                $('#editVoitureModal').modal('hide');
                // Afficher un message de succès (optionnel)
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'voiture a été modifié avec succès.',
                    confirmButtonColor: '#d33', // Couleur du bouton "OK"
                    confirmButtonText: 'OK', // Texte du bouton "OK"
                    customClass: {
                        confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
                    }
                }).then(() => {
                    location.reload();
                });
            },
            error: function() {
                alert('Erreur lors de la mise à jour.');
            }
        });
    });

    // Supprimer une voiture
    $('.delete-item-btn').click(function() {
        var voitureId = $(this).closest('tr').data('id');

        Swal.fire({
            title: 'Êtes-vous sûr de vouloir supprimer cette voiture?',
            text: "Cela supprimera également les assurances, vignettes et maintenances associées!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/voitures/' + voitureId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Supprimé!', 'La voiture a été supprimée.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Erreur!', 'Une erreur est survenue.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Erreur!', 'Impossible de supprimer.', 'error');
                    }
                });
            }
        });
    });
});

    </script>
</body>
