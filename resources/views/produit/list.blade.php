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
                        <h4>Liste des produits</h4>
                        <h6>Gerer vos produits</h6>
                    </div>
                    @can('create Produit')
                    <div class="page-btn">
                        <a href="{{url('produit')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            Produit</a>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="{{ url('produits/export-pdf')}}" title="pdf"><img
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

                                        <th>Denomination </th>
                                        <th>Reference</th>
                                        <th>Notifier par seuil</th>
                                        <th>Quantite Stock</th>
                                        <th>Prix unitaire</th>
                                        <th>Infos supp</th>
                                        @can('edit Produit')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody id="ProduitTable">
                                    @foreach($produits as $produit)
                                    <tr data-id="{{ $produit->id }}">
                                        <td>{{ $produit->Denomination }}</td>
                                        <td>{{ $produit->reference_produit }}</td>
                                        <td>{{ $produit->stock_seuil }}</td>
                                        <td>{{ $produit->qte_stock }}</td>
                                        <td>{{ $produit->prix_unitaire }}</td>
                                        <td>{{ $produit->infos_supp }}</td>
                                        @can('edit Produit')
                                        <td>
                                            <a class="me-3 edit-item-btn" href="javascript:void(0);">
                                                <img src="{{asset('assets/img/icons/edit.svg')}}" alt="Modifier">
                                            </a>

                                            @can('delete Produit')
                                            <a class="me-3 delete-item-btn" href="javascript:void(0);">
                                                <img src="{{asset('assets/img/icons/delete.svg')}}" alt="Supprimer">
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
  <div class="modal fade" id="editProduitModal" tabindex="-1" aria-labelledby="editProduitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProduitModalLabel">Modifier le produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for editing product -->
                <form method="POST" id="edit-produit-form">
                    @csrf
                    <div class="row">
                        <input type="hidden" class="form-control" name="edit-id" id="edit-id">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Référence Produit</label>
                                <input type="text" class="form-control" name="reference_produit" id="edit-reference_produit"> <!-- Changement ici -->
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Dénomination</label>
                                <input type="text" class="form-control" name="Denomination" id="edit-denomination"> <!-- Changement ici -->
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Quantité de Stock (Kg)</label>
                                <input type="text" class="form-control" name="qte_stock" id="edit-qte_stock"> <!-- Changement ici -->
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Seuil de Stock (Kg)</label>
                                <input type="text" class="form-control" name="stock_seuil" id="edit-stock_seuil"> <!-- Changement ici -->
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Prix Unitaire (FCFA)</label>
                                <input type="text" class="form-control" name="prix_unitaire" id="edit-prix_unitaire"> <!-- Changement ici -->
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Informations Supplémentaires</label>
                                <input type="text" class="form-control" name="infos_supp" id="edit-infos-supplementaires"> <!-- Changement ici -->
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
    <script src="{{ asset('js/backend/listproduit.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    let rowsPerPage = 5; // Nombre de lignes par page
    let tableBody = document.getElementById('ProduitTable');
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
