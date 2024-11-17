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
                        <h4>Liste des fermes</h4>
                        <h6>Gerer vos fermes</h6>
                    </div>
                    @can('create Paramétrages')
                    <div class="page-btn">
                        <a href="{{url('fermes/create')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            Ferme</a>
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
                                        <th>Nom de la Ferme</th>
                                        <th>Type de Ferme</th>
                                        <th>Adresse</th>
                                        <th>Entreprise Associée</th>

                                        @can('edit Paramétrages')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fermes as $ferme)
                                    <tr data-id="{{ $ferme->id }}" data-nom="{{ $ferme->name }}" data-type="{{ $ferme->typeFerme }}"
                                        data-adresse="{{ $ferme->adresse }}" data-sc="{{ $ferme->entreprise_id}}">
                                        <td>{{ $ferme->name }}</td>
                                        <td>{{ $ferme->typeFerme }}</td>
                                        <td>{{ $ferme->adresse }}</td>
                                        <td>{{ $ferme->entreprise->name }}</td> <!-- Relation entreprise -->

                                        @can('edit Paramétrages')
                                        <td>

                                            <a href="javascript:void(0);" class="me-3 edit-ferme-btn"
                                            data-bs-toggle="modal" data-bs-target="#editFermeModal">
                                            <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                        </a>

                                            @can('delete Paramétrages')
                                            <a class="me-3 delete-traite-btn" href="javascript:void(0);" onclick="confirmDelete2({{ $ferme->id }})">
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

            </div>
        </div>
    </div>
  <!-- Modal -->
  <div class="modal fade" id="editFermeModal" tabindex="-1" aria-labelledby="editFermeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="editFermeModalLabel">Éditer traitement</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editFermeform" method="POST">
              @csrf

              <div class="modal-body">

                  <input type="hidden" name="ferme_id" id="ferme_id">
                  <div class="row">
                    <!-- Champ pour le nom de la ferme -->
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="name">Nom de la Ferme</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>

                    <!-- Champ pour le type de ferme -->
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="typeFerme">Type de Ferme <span class="text-danger">*</span></label>
                            <select name="typeFerme" id="typeFerme" class="form-control" required>
                                <option value="" selected disabled>Selectionner le type de ferme</option>
                                <option value="lait">Elevage Laitières</option>
                                <option value="avicole">Elevage Avicole</option>
                                <option value="agri">Agriculture</option>
                                <!-- Ajoutez d'autres options si nécessaire -->
                            </select>
                        </div>
                    </div>

                    <!-- Champ pour l'adresse de la ferme -->
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="adresse">Adresse</label>
                            <input type="text" name="adresse" id="adresse" class="form-control" required>
                        </div>
                    </div>

                    <!-- Sélection de l'entreprise associée -->
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="entreprise_id">Entreprise Associée</label>
                            <select name="entreprise_id" id="entreprise_id" class="form-control" required>
                                <option value="" selected disabled>Selectionner l'entreprise</option>
                                @foreach($entreprises as $entreprise)
                                    <option value="{{ $entreprise->id }}">{{ $entreprise->name }}</option>
                                @endforeach
                            </select>
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
            document.querySelectorAll('.edit-ferme-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const FermeRow = this.closest('tr');

                    // Extraire les données et remplir le modal
                    document.getElementById('ferme_id').value = FermeRow.getAttribute('data-id');
                    document.getElementById('name').value = FermeRow.getAttribute(
                        'data-nom');
                    document.getElementById('typeFerme').value = FermeRow.getAttribute(
                        'data-type');
                    document.getElementById('adresse').value = FermeRow.getAttribute(
                            'data-adresse');
                    document.getElementById('entreprise_id').value = FermeRow.getAttribute(
                                'data-sc');

                });
            });
            // Soumission du formulaire d'édition
            $('#editFermeForm').submit(function(e) {
                e.preventDefault();
                var FermeId = $("#editFermeForm input[name=ferme_id]").val();
                // Récupération des données du formulaire
                var formData = $(this).serialize();

                // Vous pouvez envoyer les données du formulaire pour l'édition du produit ici en utilisant AJAX
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '/fermes/' + FermeId,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        // Gérer la réponse ici
                        // Par exemple, fermer la modal, afficher un message de succès, etc.
                        $('#editFermeForm').modal('hide');
                        // Afficher un message de succès (optionnel)
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Ferme modifié avec succès.',
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
                    fetch(`/fermes/${id}`, {
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
                                    'Ferme supprimée.',
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
</body>
