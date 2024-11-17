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
                        <h4>Interface d'ajout d'une commande</h4>
                        <h6>Enregistrer une Commande</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('commandes.store') }}">
                            @csrf
                            <div class="row">
                                <!-- Sélecteur de type de vente -->
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_vente">Type de Vente<span class="text-danger">**</span></label>
                                        <select name="type_vente[]" id="type_vente" class="form-control tagging"
                                            multiple="multiple" required>
                                            <option value="oeufs">Vente d'Oeufs</option>
                                            <option value="bandes">Vente de Bandes</option>
                                            <option value="produits">Vente de Produits</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="type" value="vente-autre">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date Vente <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_client">Type de client<span
                                                class="text-danger">**</span></label>
                                        <select name="type_client" id="type_client" class="form-control" required>
                                            <option value="" selected disabled>Selectionnez le type de client
                                            </option>
                                            <option value="Client Comptoir">Client Comptoir</option>
                                            <option value="Client Fidèle">Client Fidèle</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12" id="client_comptoir_div" style="display: none;">
                                    <div class="form-group">
                                        <label for="client_comptoir">Nom & Prénom du client<span
                                                class="text-danger">**</span></label>
                                        <input type="text" name="client_comptoir" id="client_comptoir"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="client_fidele_div" style="display: none;">
                                    <div class="form-group">
                                        <label for="client_id">Nom & Prénom du client<span
                                                class="text-danger">**</span></label>
                                        <select name="client_id" id="client_id" class="form-control">
                                            <option value="" selected disabled>Selectionnez un client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" data-phone="{{ $client->phone }}"
                                                    data-dette="{{ $client->dette_initiale }}"
                                                    data-addresse="{{ $client->adresse_physique }}">
                                                    {{ $client->nom }} {{ $client->prenom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal"
                                            data-bs-target="#addFournisseurModal">Nouveau Client</button>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="phone_div">
                                    <div class="form-group">
                                        <label>Numéro de Téléphone <b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="phone" id="phone" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="dette_div" style="display: none;">
                                    <div class="form-group">
                                        <label>Dette Actuelle<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="dette_initiale" style="color: red ; text:bold;"
                                            id="dette_initiale" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="adresse_div" style="display: none;">
                                    <div class="form-group">
                                        <label>Adresse<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="addresse" style="color: red ; text:bold;"
                                            id="addresse" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Section vente d'oeufs -->
                            <div id="vente_oeufs" style="display: none;">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-secondary" data-toggle="modal"
                                            data-target="#categorieModal">
                                            Ajouter la liste des categories
                                        </button>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Type d'Oeufs</th>
                                                        <th>Avec Alvéoles</th>
                                                        <th>Nombre de plateaux</th>
                                                        <th>Prix plateaux</th>
                                                        <th>Montant Total</th>
                                                        <th>Action</th>
                                                        <!-- Nouvelle colonne pour le bouton de suppression -->
                                                    </tr>
                                                </thead>
                                                <tbody id="categorieTableBody">
                                                    <!-- Les categories sélectionnés seront ajoutés ici -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section vente de bandes -->
                            <div id="vente_bandes" style="display: none;">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-secondary" data-toggle="modal"
                                            data-target="#categorieModal1">
                                            Choisissez une bande
                                        </button>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Nom de la bande</th>
                                                        <th>Quantité stock</th>
                                                        <th>Quantite Vendu</th>
                                                        <th>Prix Unitaire</th>
                                                        <th>Prix Total</th>
                                                        <th>Action</th>
                                                        <!-- Nouvelle colonne pour le bouton de suppression -->
                                                    </tr>
                                                </thead>
                                                <tbody id="categorieTableBody1">
                                                    <!-- Les categories sélectionnés seront ajoutés ici -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section vente de produits -->
                            <div id="vente_produits" style="display: none;">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-secondary" data-toggle="modal"
                                            data-target="#produitModal">
                                            Selectionnez les produits a vendre
                                        </button>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Dénomination du produit</th>
                                                        <th>Quantité stock</th>
                                                        <th>Qte Vendu</th>
                                                        <th>Prix unitaire</th>
                                                        <th>Prix total</th>
                                                        <th>Action</th>
                                                        <!-- Nouvelle colonne pour le bouton de suppression -->
                                                    </tr>
                                                </thead>
                                                <tbody id="categorieTableBody2">
                                                    <!-- Les produits sélectionnés seront ajoutés ici -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row mt-4">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_ravitaillement">Total Vente</label>
                                        <input type="number" class="form-control" name="total_ravitaillement"
                                            id="total_ravitaillement" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_remise">Total remise <span
                                                class="text-danger">**</span></label>
                                        <input type="number" class="form-control" name="total_remise"
                                            id="total_remise" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="net_payer">Net à payer</label>
                                        <input type="number" class="form-control" name="net_payer" id="net_payer"
                                            required readonly>
                                    </div>
                                </div>

                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <button type="button" class="btn btn-secondary"
                                    onclick="window.history.back();">Annuler</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal pour la liste des produits -->
    <div class="modal fade" id="produitModal" tabindex="-1" aria-labelledby="produitModalLabel"
        aria-hidden="true">
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
                                <th>Denomination du produits</th>

                                <th>Quantité stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produits as $produit)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="produit-checkbox" data-id="{{ $produit->id }}"
                                            data-nom="{{ $produit->Denomination }}"
                                            data-stock="{{ $produit->qte_stock }}" data-prix="{{$produit->prix_unitaire}}">
                                    </td>

                                    <td>{{ $produit->Denomination }}</td>

                                    <td>{{ $produit->qte_stock }}
                                        @if ($produit->Denomination == 'Lait')
                                            Litres
                                        @else
                                            Kg
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="ajoutercategoriesBtn3">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour la liste des categories -->
    <div class="modal fade" id="categorieModal" tabindex="-1" aria-labelledby="categorieModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categorieModalLabel">Liste des categories</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Type Oeufs</th>

                                <th>Quantité stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $categorie)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="categorie-checkbox"
                                            data-id="{{ $categorie->id }}" data-nom="{{ $categorie->Denomination }}"
                                            data-stock="{{ $categorie->qteEnplateaux }}"
                                            data-prix="{{ $categorie->PrixPlateaux }}">
                                    </td>

                                    <td>{{ $categorie->Denomination }}</td>
                                    <td>{{ $categorie->qteEnplateaux }}plateaux</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="ajoutercategoriesBtn2">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour la liste des categories -->
    <div class="modal fade" id="categorieModal1" tabindex="-1" aria-labelledby="categorieModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categorieModalLabel1">Liste des catégories</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Bande</th>
                                <th>Poulailler</th>
                                <th>Quantité sujet</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bandes as $bande)
                                @php
                                    $poulaillers = explode(',', $bande->poulailler);
                                @endphp
                                @foreach ($poulaillers as $index => $poulaillerEntry)
                                    @php
                                        [$id, $cheptel] = explode('*', $poulaillerEntry);
                                        $poulailler_n = \App\Models\Poulailler::find($id)->Denomination;
                                    @endphp
                                    <tr>
                                        @if ($index == 0)
                                            <td rowspan="{{ count($poulaillers) }}">
                                                <input type="checkbox" class="categorie1-checkbox"
                                                    data-id="{{ $bande->id }}"
                                                    data-poulailler="{{ $id }}"
                                                    data-quantite="{{ $bande->cheptel_actuel }}"
                                                    data-nom="{{ $bande->nom_bande }}">
                                            </td>

                                            <td rowspan="{{ count($poulaillers) }}">{{ $bande->nom_bande }}
                                                ({{ $bande->cheptel_actuel }} Sujet)
                                            </td>
                                        @endif
                                        <td>{{ $poulailler_n }}</td>
                                        <td>{{ $cheptel }}</td>

                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="ajoutercategoriesBtn1">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour l'ajout de nouveau client -->
    <div class="modal fade" id="addFournisseurModal" tabindex="-1" aria-labelledby="addFournisseurModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFournisseurModalLabel">Ajouter un nouveau fournisseur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="add-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nom <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="nom" id="nom" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Prénom <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="prenom" id="prenom" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Dette Initiale <b><span class="text-danger">*</span></b> </label>
                                    <input type="text" name="dette_initiale" id="redevance_initiale"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Téléphone <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" id="email" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Numéro WhatsApp <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="num_whatsapp" id="num_whatsapp"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Adresse Physique <b><span class="text-danger">*</span></b></label>
                                    <input type="text" name="adresse_physique" id="adresse_physique"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Informations Supplémentaires <b><span
                                                class="text-danger">*</span></b></label>
                                    <textarea name="infos_supp" id="infos_supp" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                                <button type="button" class="btn btn-cancel">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('partials.script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('assets/version/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/backend/commande.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialiser Select2 avec le mode tagging
            $(".tagging").select2({
                tags: true
            });

            // Utiliser l'événement 'change' de Select2
            $('#type_vente').on('change', function() {
                var selectedOptions = $(this)
                    .val(); // Récupère les options sélectionnées sous forme de tableau

                // Vérifier si "oeufs" est sélectionné
                if (selectedOptions.includes('oeufs')) {
                    document.getElementById('vente_oeufs').style.display = 'block';
                } else {
                    document.getElementById('vente_oeufs').style.display = 'none';
                }

                // Vérifier si "bandes" est sélectionné
                if (selectedOptions.includes('bandes')) {
                    document.getElementById('vente_bandes').style.display = 'block';
                } else {
                    document.getElementById('vente_bandes').style.display = 'none';
                }

                // Vérifier si "produits" est sélectionné
                if (selectedOptions.includes('produits')) {
                    document.getElementById('vente_produits').style.display = 'block';
                } else {
                    document.getElementById('vente_produits').style.display = 'none';
                }
            });
        });
    </script>

</body>
