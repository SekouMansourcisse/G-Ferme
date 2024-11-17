@include('partials._head')

<body>
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>
    <div class="main-wrapper">
        @include('partials._topbar')
        @include('partials._sidebar_collapsed')
        @include('partials._sidebar')
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Meilleur Article</h4>
                        <h6>Les articles classé par les plus vendu  </h6>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form id="filterForm">
                            <div class="row">

                                <div class="col-md-4" id="startD">
                                    <input type="date" class="form-control" id="startDate" name="startDate"
                                        value="2024-05-12">
                                </div>
                                <div class="col-md-4" id="endD">
                                    <input type="date" class="form-control" id="endDate" name="endDate"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary mt-1"
                                        onclick="applyFilter()">Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="assets/img/icons/filter.svg" alt="img">
                                        <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                src="assets/img/icons/pdf.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="assets/img/icons/excel.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="assets/img/icons/printer.svg" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Designation Article</th>
                                        <th>Montant Vendues</th>
                                        <th>Quantité Vendues</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="article-body">
                                    <!-- Les lignes de vos articles seront insérées ici -->
                                </tbody>
                                <tfoot>
                                    <tr class="bg-secondary text-white">
                                        <td>Total</td>
                                        <td id="total-montant"></td>
                                        <td id="total-quantite"></td>

                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.script')
    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script>
        function applyFilter() {
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;

    $.ajax({
        url: '/getBestArticles',
        method: 'GET',
        data: { startDate: startDate, endDate: endDate },
        success: function(response) {
            let tbody = $('.table-bordered tbody');
            tbody.empty(); // Vide le tableau
            let totalMontant = 0;
            let totalQuantite = 0;
            response.forEach(function(article) {
                totalMontant +=article.totalVentes;
                totalQuantite +=article.quantiteVendue;
                let row = `<tr>
                    <td>${article.designation}</td>
                    <td>${article.totalVentes.toFixed(2)} FCFA</td>
                    <td>${article.quantiteVendue}</td>
                    <td><button class="btn btn-info">Détails</button></td>
                </tr>`;
                tbody.append(row);
            });

// Afficher les totaux dans la dernière ligne
document.getElementById('total-montant').innerText = totalMontant.toFixed(2) + ' FCFA';
document.getElementById('total-quantite').innerText = totalQuantite.toFixed(2);

        },
        error: function(xhr, status, error) {
            console.error("Erreur lors de la récupération des meilleurs articles: ", error);
        }
    });
}



document.addEventListener('DOMContentLoaded', function() {
    applyFilter();
});

    </script>

</body>
