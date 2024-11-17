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
                        <h4>Liste des ventes</h4>
                        <h6>visualiser vos ventes </h6>
                    </div>
                    @can('create Autres Ventes')
                        <div class="page-btn">
                            <a href="{{ url('vente-autres/create') }}" class="btn btn-added"><img
                                    src="{{ asset('assets/img/icons/plus.svg') }}" alt="img">Enregister
                                une Vente</a>
                        </div>
                    @endcan

                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                            href="{{ url('vente_autres/export-pdf') }}" title="pdf"><img
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
                                        <th>Date Vente</th>
                                        <th>Client</th>

                                        <th>Quantité vendu</th>
                                        <th>Montant Vente</th>
                                        <th>Total Remise</th>
                                        <th>Net à Payer</th>
                                        <th>Montant Payé</th>
                                        <th>Dette à Payer</th>

                                        @can('edit Autres Ventes')
                                            <th>Actions</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody id="AutreventeTables">
                                    @foreach ($ventes as $vente)
                                        <tr>
                                            <td>{{ $vente->Date_op }}</td>
                                            <td>
                                                Nom&Prenom:{{ $vente->NomPrenomClient }} <br> N°Tel: {{ $vente->phone }}
                                            </td>
                                            <td>
                                                @foreach (explode(',', $vente->AutresInfos) as $produit)
                                                    @php
                                                        [$id, $qte] = explode('*', $produit);
                                                        $produitNom = App\Models\Produit::find($id)->Denomination;
                                                    @endphp
                                                    {{ $produitNom }} ({{ $qte }} Kg)<br>
                                                @endforeach
                                            </td>
                                            <td>{{ $vente->TotalRavitaillement }}</td>
                                            <td>{{ $vente->totalRemise }}</td>
                                            <td>{{ $vente->Montant_facture }}</td>
                                            <td>{{ $vente->montant_payé }}</td>
                                            <td>{{ $vente->montantDette }}</td>

                                            @can('edit Autres Ventes')
                                                <td>

                                                    <a class="me-3 edit-item-btn"
                                                        href="{{ route('vente-autres.edit', $vente->id) }}"
                                                        id="edit-item-btn">
                                                        <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                                                    </a>

                                                    @can('delete Autres Ventes')
                                                        <a class="me-3 delete-item-btn" href="javascript:void(0);"
                                                            onclick="confirmDelete({{ $vente->id }})">
                                                            <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="Delete">
                                                        </a>
                                                    @endcan
                                                </td>
                                            @endcan
                                        </tr>
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


    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowsPerPage = 5; // Nombre de lignes par page
            let tableBody = document.getElementById('AutreventeTables');
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
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible!",
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-primary', // Classe CSS personnalisée pour le bouton "OK"
                    cancelButton: 'btn btn-cancel',
                },
                confirmButtonText: 'Oui, supprimer !'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envoyer la requête de suppression
                    fetch(`/vente-autres/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Supprimé !',
                                    'La vente a été supprimée.',
                                    'success'
                                ).then(() => {
                                    location
                                .reload(); // Rafraîchir la page ou supprimer l'élément du DOM
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
