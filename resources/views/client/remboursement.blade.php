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

    .pagination-wrapper button {
        margin: 2px;
        padding: 5px 10px;
        cursor: pointer;
    }

    .pagination-wrapper .active {
        font-weight: bold;
        background-color: #ff9f43;
        color: white;
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
                        <h4>Liste des remboursements</h4>
                        <h6>Gérer les remboursements des clients</h6>
                    </div>
                    @can('create remboursement')
                        <div class="page-btn">
                            <a href="{{ url('showRemboursementC') }}" class="btn btn-added">
                                <img src="{{asset('assets/img/icons/plus.svg')}}" alt="img"> Enregistrer un remboursement
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
                                        <img src="{{asset('assets/img/icons/filter.svg')}}" alt="img">
                                        <span><img src="{{asset('assets/img/icons/closes.svg')}}" alt="img"></span>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                            href="{{ url('remboursementC/export-pdf') }}" title="pdf"><img
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
                                        <th>Date</th>
                                        <th>Dette</th>
                                        <th>Montant payé</th>
                                        <th>Client</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="remboursementTable">
                                    @foreach ($remboursements as $remboursement)
                                        @if ($remboursement->client != null)
                                            <tr>
                                                <td>{{ $remboursement->date_r }}</td>
                                                <td>{{ $remboursement->Dette }}</td>
                                                <td>{{ $remboursement->montant_paye }}</td>
                                                <td>{{ $remboursement->NomPrenomClient }}</td>
                                                <td>

                                                    <a class="me-3 info-item-btn" href="javascript:void(0);"
                                                        data-id="{{ $remboursement->id }}">
                                                        <img src="{{asset('assets/img/icons/eye1.svg')}}" alt="info">
                                                    </a>

                                                </a>
                                                <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn" data-id="{{$remboursement->id}}">
                                                    <img src="{{asset('assets/img/icons/edit.svg')}}" alt="img">
                                                </a>

                                                @can('delete remboursement')
                                                <a class="me-3 delete-item-btn" id="delete-item-btn" data-id="{{$remboursement->id}}" href="javascript:void(0);">
                                                    <img src="{{asset('assets/img/icons/delete.svg')}}" alt="img">
                                                </a>
                                                @endcan

                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Pagination Controls -->
                            <div id="paginationControls" class="pagination-wrapper"></div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal pour afficher les détails du remboursement -->
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Détail du remboursement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <p><strong>Nom du client:</strong> <span id="clientNom"></span></p>
                    <p><strong>Date du remboursement:</strong> <span id="dateRemboursement"></span></p>
                    <p><strong>N°operation:</strong> <span id="operation"></span></p>
                    <p><strong>Dette:</strong> <span id="dette"></span></p>
                    <p><strong>Montant payé:</strong> <span id="montantPaye"></span></p>
                    <p><strong>client:</strong> <span id="clientNom"></span></p>
                    <p><strong>Payé par:</strong> <span id="CompteNom"></span></p>
                </div>

                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editRemboursementModal" tabindex="-1" role="dialog"
        aria-labelledby="editRemboursementModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRemboursementModalLabel">Éditer Remboursement</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editRemboursementForm">
                        @csrf
                        <input type="hidden" name="type" value="client">
                        <input type="hidden" id="remboursement_id" name="remboursement_id">

                        <div class="form-group">
                            <label for="edit_date_reglement">Date règlement <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_date_reglement" name="date_r"
                                readonly required>
                        </div>

                        <div class="form-group">
                            <label for="edit-client-id">Nom & Prénoms client <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="edit_client_id" name="client" required>
                                <option value="">Sélectionnez un client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>N° opération</th>
                                        <th>Type opération</th>

                                        <th>Montant payé</th>
                                    </tr>
                                </thead>
                                <tbody id="operations-table">
                                    <!-- Les opérations seront chargées ici par JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="edit_montant">Montant payé <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_montant" name="montant_paye"
                                readonly required>
                        </div>

                        <div class="form-group">
                            <label for="edit_payer_par">Payé par</label>
                            <select name="payer_par" id="edit_payer_par" class="form-control" required>
                                <option value="" selected disabled>Selectionner un compte</option>
                                @foreach ($comptes as $compte)
                                    <option value="{{ $compte->id }}">{{ $compte->Denomination }}</option>
                                @endforeach
                            </select>
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

    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.info-item-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '/Fremboursements/' + id,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            r = response.remboursement;
                            $('#clientNom').text(response.remboursement.NomPrenomClient);
                            $('#dateRemboursement').text(response.remboursement.date_r);
                            $('#operation').text(response.remboursement.operation);
                            $('#dette').text(response.remboursement.Dette);
                            $('#montantPaye').text(response.remboursement.montant_paye);
                            $('#clientNom').text(response.remboursement.NomPrenomClient);
                            $('#CompteNom').text(r.compte.Denomination);

                            $('#infoModal').modal('show');
                        }
                    }
                });
            });

            $('.edit-item-btn').click(function() {
                var remboursementId = $(this).data('id');
                $.ajax({
                    url: '/Fremboursements/' + remboursementId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var remboursement = response.remboursement;
                            console.log(remboursement);
                            $('#remboursement_id').val(remboursement.id);
                            $('#edit_date_reglement').val(remboursement.date_r);
                            $('#edit_client_id').val(remboursement.client);
                            $('#edit_montant').val(remboursement.montant_paye);
                            $('#edit_payer_par').val(remboursement.payer_par);

                            var operationsTable = $('#operations-table');
                            operationsTable.empty();

                            operation = response.remboursement.operation_r;
                            operationsTable.append(
                                '<tr>' +
                                '<td> FactureN°' + operation.id + '</td>' +
                                '<td>' + operation.typeOperation + '</td>' +
                                '<td><input type="number" name="montant_paye" class="form-control" value="' +
                                remboursement.montant_paye + '" readonly></td>' +
                                '</tr>'
                            );


                            $('#editRemboursementModal').modal('show');
                        } else {
                            Swal.fire({
                                title: 'Erreur',
                                text: response.message ||
                                    'Une erreur est survenue lors de la récupération des données',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de la récupération des données',
                            icon: 'error'
                        });
                    }
                });
            });


            $('#editRemboursementForm').submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();
                var remboursementId = $('#remboursement_id').val();

                $.ajax({
                    url: '/Editremboursements/' + remboursementId,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès',
                                text: 'Le remboursement a été mis à jour avec succès',
                                icon: 'success'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#editRemboursementModal').modal('hide');
                                    location.reload();
                                }
                            });

                        } else {
                            alert('Une erreur est survenue lors de la mise à jour.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Une erreur est survenue lors de la mise à jour.');
                    }
                });
            });


            $('.delete-item-btn').click(function() {
                var remboursementId = $(this).data('id');

                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Vous ne pourrez pas revenir en arrière!",
                    icon: 'warning',
                    showCancelButton: true,
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-cancel',
                    },
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/deleteremboursement/' + remboursementId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Supprimé!',
                                        'Le remboursement a été supprimé.',
                                        'success'
                                    );
                                    location.reload();
                                } else {
                                    Swal.fire(
                                        'Erreur!',
                                        'Une erreur est survenue lors de la suppression.',
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowsPerPage = 5; // Nombre de lignes par page
            let tableBody = document.getElementById('remboursementTable');
            let rows = tableBody.getElementsByTagName('tr');
            let paginationControls = document.getElementById('paginationControls');
            let totalRows = rows.length;
            let totalPages = Math.ceil(totalRows / rowsPerPage);
            let currentPage = 1;

            // Fonction pour afficher une page
            function displayPage(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                // Masquer toutes les lignes
                for (let i = 0; i < totalRows; i++) {
                    rows[i].style.display = 'none';
                }

                // Afficher les lignes pour la page en cours
                for (let i = start; i < end && i < totalRows; i++) {
                    rows[i].style.display = '';
                }

                // Mettre à jour les boutons de pagination
                updatePaginationControls(page);
            }

            // Fonction pour créer les boutons de pagination
            function updatePaginationControls(page) {
                paginationControls.innerHTML = '';

                // Ajouter le bouton "Précédent"
                if (page > 1) {
                    let prevButton = document.createElement('button');
                    prevButton.innerText = 'Précédent';
                    prevButton.addEventListener('click', function() {
                        displayPage(page - 1);
                    });
                    paginationControls.appendChild(prevButton);
                }

                // Ajouter les boutons pour chaque page
                for (let i = 1; i <= totalPages; i++) {
                    let pageButton = document.createElement('button');
                    pageButton.innerText = i;
                    if (i === page) {
                        pageButton.classList.add('active');
                    }
                    pageButton.addEventListener('click', function() {
                        displayPage(i);
                    });
                    paginationControls.appendChild(pageButton);
                }

                // Ajouter le bouton "Suivant"
                if (page < totalPages) {
                    let nextButton = document.createElement('button');
                    nextButton.innerText = 'Suivant';
                    nextButton.addEventListener('click', function() {
                        displayPage(page + 1);
                    });
                    paginationControls.appendChild(nextButton);
                }
            }

            // Afficher la première page au chargement
            displayPage(currentPage);
        });
    </script>
</body>
