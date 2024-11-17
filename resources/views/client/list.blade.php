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
                        <h4>Liste Client</h4>
                        <h6>Gerer vos Client</h6>
                    </div>
                    @can('create Clients')
                    <div class="page-btn">
                        <a href="{{url('client')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            Client</a>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="{{ url('clients/export-pdf')}}" title="pdf"><img
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

                                        <th>Nom </th>
                                        <th>Prenom</th>
                                        <th>N°Telephone</th>
                                        <th>N°whatsapp</th>
                                        <th>Dette initiale</th>
                                        <th>Adresse</th>
                                        <th>Email</th>
                                        <th>Infos supp</th>
                                        @can('edit Clients')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody id="clientTable">
                                    @foreach($clients as $client)
                                    <tr data-id="{{ $client->id }}">
                                        <td>{{ $client->nom }}</td>
                                        <td>{{ $client->prenom }}</td>
                                        <td>{{ $client->phone }}</td>
                                        <td>{{ $client->num_whatsapp }}</td>
                                        <td>{{ $client->dette_initiale }}</td>
                                        <td>{{ $client->adresse_physique }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->infos_supp }}</td>
                                        @can('edit Clients')
                                        <td>

                                            <a class="me-3 edit-item-btn"  href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>

                                            @can('delete Clients')
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
                             <!-- Pagination Controls -->
                             <div id="paginationControls" class="pagination-wrapper"></div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
  <!-- Modal -->
  <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel">Modifier le client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for editing client -->
                <form method="POST" id="edit-client-form">
                    @csrf
                    <div class="row">
                        <input type="hidden" class="form-control" name="edit-id" id="edit-id">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" class="form-control" name="edit-prenom" id="edit-prenom">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" class="form-control" name="edit-nom" id="edit-nom">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" class="form-control" name="edit-telephone" id="edit-telephone">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Adresse</label>
                                <input type="text" class="form-control" name="edit-adresse" id="edit-adresse">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" name="edit-email" id="edit-email">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Numéro WhatsApp</label>
                                <input type="text" class="form-control" name="edit-num-whatsapp" id="edit-num-whatsapp">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Dette Initiale</label>
                                <input type="text" class="form-control" name="edit-dette-initiale" id="edit-dette-initiale">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Informations supplémentaires</label>
                                <textarea class="form-control" name="edit-infos-supplementaires" id="edit-infos-supplementaires" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </form>
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
    <script src="{{ asset('js/backend/listclient.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    let rowsPerPage = 5; // Nombre de lignes par page
    let tableBody = document.getElementById('clientTable');
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
