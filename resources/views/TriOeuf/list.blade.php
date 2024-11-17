@include('partials._head')
<head>
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
</head>

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
                        <h4>Liste Categorie d'Oeuf</h4>
                        <h6>Gerer vos Categorie</h6>
                    </div>
                    @can('create Tri des Oeufs')
                    <div class="page-btn">
                        <a href="{{url('categorieOeufs/create')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            Categorie</a>
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
                                        <img src="{{asset('assets/img/icons/filter.svg')}}" alt="img">
                                        <span><img src="{{asset('assets/img/icons/closes.sv')}}g" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="{{asset('assets/img/icons/search-white.svg')}}"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                src="{{asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="{{asset('assets/img/icons/excel.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="{{asset('assets/img/icons/printer.svg')}}" alt="img"></a>
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

                                        <th>Dénomination</th>
                                        <th>Prix Unitaire</th>
                                        <th>Prix Pltaux</th>
                                        <th>Quantité d'Oeufs</th>
                                        <th>Quantité en Plateaux</th>
                                        <th>Valeur Financière</th>
                                        @can('edit Tri des Oeufs')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoriesOeuf as $categorieOeuf)
                                    <tr data-id="{{ $categorieOeuf->id }}" data-nom="{{ $categorieOeuf->Denomination }}" data-prixU="{{ $categorieOeuf->PrixUnitaire }}"
                                        data-prixP="{{ $categorieOeuf->PrixPlateaux }}" data-qte="{{ $categorieOeuf->qteOeuf }}" data-qtep="{{ $categorieOeuf->qteEnplateaux }}"
                                        data-description="{{$categorieOeuf->description}}">

                                        <td>{{ $categorieOeuf->Denomination }}</td>
                                        <td>{{ $categorieOeuf->PrixUnitaire }}</td>
                                        <td>{{ $categorieOeuf->PrixPlateaux }}</td>
                                        <td>{{ $categorieOeuf->qteOeuf }}</td>
                                        <td>{{ $categorieOeuf->qteEnplateaux }}</td>
                                        <td>{{ $categorieOeuf->ValeurFinancier }}</td>
                                        @can('edit Tri des Oeufs')
                                        <td>

                                            <a href="javascript:void(0);" class="me-3 edit-traite-btn"
                                            data-bs-toggle="modal" data-bs-target="#editCategorieModal">
                                            <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                        </a>

                                            @can('delete Tri des Oeufs')
                                            <a class="me-3 delete-traite-btn" href="javascript:void(0);" onclick="confirmDelete2({{ $categorieOeuf->id }})">
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
    <div class="modal fade" id="editCategorieModal" tabindex="-1" aria-labelledby="editCategorieModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategorieModalLabel">Éditer traitement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategorieForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="categorie_id" id="categorie_id">
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label>Dénomination</label>
                                <input type="text" name="Denomination" id="Denomination" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label>Prix Unitaire</label>
                                <input type="text" name="PrixUnitaire" id="PrixUnitaire" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label>Prix Pltaux</label>
                                <input type="text" name="PrixPlateaux" id="PrixPlateaux" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label>Quantité en plateaux</label>
                                <input type="text" name="qteEnplateaux" id="qteEnplateaux" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label>quantité d'Oeufs</label>
                                <input type="text" name="qteOeuf" id="qteOeuf" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="Resume" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
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
            document.querySelectorAll('.edit-traite-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const CategorieRow = this.closest('tr');

                    // Extraire les données et remplir le modal
                    document.getElementById('categorie_id').value = CategorieRow.getAttribute('data-id');
                    document.getElementById('Denomination').value = CategorieRow.getAttribute(
                        'data-nom');
                    document.getElementById('PrixUnitaire').value = CategorieRow.getAttribute(
                        'data-prixU');
                    document.getElementById('PrixPlateaux').value = CategorieRow.getAttribute(
                            'data-prixP');
                    document.getElementById('qteEnplateaux').value = CategorieRow.getAttribute(
                                'data-qtep');
                    document.getElementById('qteOeuf').value = CategorieRow.getAttribute(
                        'data-qte');
                    document.getElementById('description').value = CategorieRow.getAttribute(
                            'data-description');

                });
            });
            // Soumission du formulaire d'édition
            $('#editCategorieForm').submit(function(e) {
                e.preventDefault();
                var CategorieId = $("#editCategorieForm input[name=categorie_id]").val();
                // Récupération des données du formulaire
                var formData = $(this).serialize();

                // Vous pouvez envoyer les données du formulaire pour l'édition du produit ici en utilisant AJAX
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '/categorieOeufs/' + CategorieId,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        // Gérer la réponse ici
                        // Par exemple, fermer la modal, afficher un message de succès, etc.
                        $('#editCategorieForm').modal('hide');
                        // Afficher un message de succès (optionnel)
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'categorie modifié avec succès.',
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
                    fetch(`/categorieOeufs/${id}`, {
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
                                    'categorie supprimée.',
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
