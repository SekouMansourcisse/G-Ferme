@include('partials._head')
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
                        <h4>Declaration de perte de Produit</h4>
                        <h6>Declarer une perte </h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('perte-produit-o-eufs.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="type_perte" value="Produit">
                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="Date" class="form-label">Date de déclaration perte <b><span class="text-danger">*</span></b></label>
                                        <input type="date" class="form-control" id="Date" name="Date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="Resume" class="form-label">Description de la perte <b><span class="text-danger">*</span></b></label>
                                        <textarea class="form-control" id="Resume" name="Resume" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#produitModal">
                                        Ajouter la liste des produits
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Type de Produit</th>
                                                    <th>Dénomination produit</th>
                                                    <th>Quantité Stock</th>
                                                    <th>Quantité perdu</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="produitTableBody">
                                                <!-- Les produits sélectionnés seront ajoutés ici -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <br>
                            <div class="row">
                                <div class="col-lg-8">
                                    <button type="submit" class="btn btn-primary me-2">Enregistrer</button>
                                    <button type="button" id="cancelAddalimentationBtn" class="btn btn-secondary" onclick="window.history.back();">Annuler</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal pour la liste des produits -->
<div class="modal fade" id="produitModal" tabindex="-1" aria-labelledby="produitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="produitModalLabel">Liste des produits</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Type de Produit</th>
                            <th>Produit</th>
                            <th>Quantité stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produits as $produit)

                            <tr>
                                <td>
                                    <input type="checkbox" class="produit-checkbox" data-id="{{ $produit->id }}" data-type="{{ $produit->typeProduit }}" data-nom="{{ $produit->Denomination }}" data-stock="{{ $produit->qte_stock }}">
                                </td>
                                <td>{{ $produit->typeProduit }}</td>
                                <td>{{ $produit->Denomination }}</td>
                                <td>{{ $produit->qte_stock }}Kg</td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="ajouterProduitsBtn">Ajouter</button>
            </div>
        </div>
    </div>
</div>
    @include('partials.script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('assets/version/bootstrap.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#ajouterProduitsBtn').click(function() {
                $('.produit-checkbox:checked').each(function() {
                    var produitId = $(this).data('id');
                    var produitNom = $(this).data('nom');
                    var type=$(this).data('type');
                    var produitStock = $(this).data('stock');

                    var row = '<tr>' +
                        '<td>' + type + '</td>' +
                        '<td>' + produitNom + '</td>' +
                        '<td>' + produitStock + ' Kg</td>'+
                        '<td><input type="text" class="form-control" name="qte_perdu[' + produitId + ']" required></td>'+
                        '<td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>' +
                        '</tr>';

                    $('#produitTableBody').append(row);
                });

                $('#produitModal').modal('hide');
            });

            $(document).on('click', '.remove-product-btn', function() {
                $(this).closest('tr').remove();
            });
        });

        </script>
</body>
