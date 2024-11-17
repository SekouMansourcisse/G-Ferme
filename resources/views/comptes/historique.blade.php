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
.span_1 {
    background-color: #b38526; /* Couleur de fond dorée */
    padding: 10px;
    color: white;
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
                        <h4>Historique</h4>
                        <h6>Historique des transactions <span class="span_1">{{ $compte->Denomination}}</span> </h6>
                    </div>

                    <div class="page-btn">
                        <a href="{{url('listcompte')}}" class="btn btn-primary"> <img src="{{ asset('assets/img/icons/return1.svg')}}" alt="img">
                         Retour a la liste    </a>
                    </div>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                src="{{ asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src=" {{ asset('assets/img/icons/excel.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" onclick="window.print()"  data-bs-placement="top" title="print"><img
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
                                                    src="assets/img/icons/search-whites.svg" alt="img"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Détails</th>
                                    </tr>
                                </thead>
                                <tbody id="historiqueBody">
                                    @foreach($historique as $item)
                                    <tr>
                                        <td>{{ $item['type'] }}</td>
                                        <td>{{ $item['date'] }}</td>
                                        <td>{{ $item['montant'] }}</td>
                                        <td>{{ $item['details'] }}</td>
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

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{ asset('js/backend/listcompte.js')}}"></script>
<!-- JavaScript to Handle Pagination -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let rowsPerPage = 10; // Nombre de lignes par page
        let tableBody = document.getElementById('historiqueBody');
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
                prevButton.addEventListener('click', function () {
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
                pageButton.addEventListener('click', function () {
                    displayPage(i);
                });
                paginationControls.appendChild(pageButton);
            }

            // Ajouter le bouton "Suivant"
            if (page < totalPages) {
                let nextButton = document.createElement('button');
                nextButton.innerText = 'Suivant';
                nextButton.addEventListener('click', function () {
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
