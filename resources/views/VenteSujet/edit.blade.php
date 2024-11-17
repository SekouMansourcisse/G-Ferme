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
                        <h4>Interface d'edition de vente de Sujet</h4>
                        <h6>Modifier une vente</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('venteSujet.update', $operation->id) }}">
                            @csrf
                            @method('PUT') <!-- Pour indiquer que c'est une mise à jour -->
                            <input type="hidden" name="type" value="vente-sujet">

                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date Vente <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date"
                                            value="{{ $operation->Date_op }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_client">Type de client<span
                                                class="text-danger">**</span></label>
                                        <select name="type_client" id="type_client" class="form-control" required>
                                            <option value="" disabled>Selectionnez le type de client</option>
                                            <option value="Client Comptoir"
                                                {{ $operation->client == null ? 'selected' : '' }}>Client Comptoir
                                            </option>
                                            <option value="Client Fidèle"
                                                {{ $operation->client != null ? 'selected' : '' }}>Client Fidèle
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-sm-12" id="client_comptoir_div"
                                    style="{{ $operation->client ? 'display: none;' : '' }}">
                                    <div class="form-group">
                                        <label for="client_comptoir">Nom & Prénom du client<span
                                                class="text-danger">**</span></label>
                                        <input type="text" name="client_comptoir" id="client_comptoir"
                                            class="form-control" value="{{ $operation->NomPrenomClient }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="client_fidele_div"
                                    style="{{ !$operation->client ? 'display: none;' : '' }}">
                                    <div class="form-group">
                                        <label for="client_id">Nom & Prénom du client<span
                                                class="text-danger">**</span></label>
                                        <select name="client_id" id="client_id" class="form-control">
                                            <option value="" disabled>Selectionnez un client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" data-phone="{{ $client->phone }}"
                                                    data-dette="{{ $client->dette_initiale }}"
                                                    data-addresse="{{ $client->adresse_physique }}"
                                                    {{ $client->id == $operation->client ? 'selected' : '' }}>
                                                    {{ $client->nom }} {{ $client->prenom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="phone_div">
                                    <div class="form-group">
                                        <label>Numéro de Téléphone <b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            value="{{ $operation->phone }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="dette_div" style="display: none;">
                                    <div class="form-group">
                                        <label>Dette Actuelle<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="dette_initiale" style="color: red ; text:bold;"
                                            id="dette_initiale" class="form-control" readonly
                                            value="{{ $operation->dette_initiale }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="adresse_div" style="display: none;">
                                    <div class="form-group">
                                        <label>Adresse<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="addresse" style="color: red ; text:bold;"
                                            id="addresse" class="form-control" readonly
                                            value="{{ $operation->addresse }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal"
                                        data-target="#categorieModal">
                                        Choisissez une bande
                                    </button>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nom de la bande</th>
                                                    <th>Quantité Vendue</th>
                                                    <th>Prix Unitaire</th>
                                                    <th>Montant Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="categorieTableBody">
                                                @foreach ($categories as $category)
                                                    <tr>
                                                        <td>{{ $category['nom'] }}
                                                        </td> <!-- Affichez le nom de la bande directement -->

                                                        <!-- Ajoutez la variable $cheptel ici -->
                                                        <td>
                                                            <input type="number"
                                                                name="qte_vente[{{ $category['bandeId'] }}]"
                                                                class="form-control qte_vente"
                                                                value="{{ $category['qteVendu'] }}" min="1"
                                                                required>
                                                        </td>
                                                        <td>
                                                            <input type="number"
                                                                name="prixUnitaire[{{ $category['bandeId'] }}]"
                                                                class="form-control prix_unitaire"
                                                                value="{{ $category['prixUnitaire'] }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="number"
                                                                name="Montant_total[{{ $category['bandeId'] }}]"
                                                                class="form-control montant_total"
                                                                value="{{ $category['montantTotal'] }}" required
                                                                readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-product-btn">X</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_ravitaillement">Total Vente <span
                                                class="text-danger">**</span></label>
                                        <input type="text" class="form-control" name="total_ravitaillement"
                                            id="total_ravitaillement" value="{{ $operation->Totalvente }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_remise">Total Remise <span
                                                class="text-danger">**</span></label>
                                        <input type="text" class="form-control" name="total_remise"
                                            id="total_remise" value="{{ $operation->totalRemise }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="net_payer">Net à payer</label>
                                        <input type="number" class="form-control" name="net_payer" id="net_payer"
                                            value="{{ $operation->Totalvente - $operation->totalRemise }}" required
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_paye">Montant Payé <span
                                                class="text-danger">**</span></label>
                                        <input type="text" class="form-control" name="montant_paye"
                                            id="montant_paye" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dette_a_paye">Dette à Payer <span
                                                class="text-danger">**</span></label>
                                        <input type="text" class="form-control" name="dette_a_paye"
                                            id="dette_a_paye" value="{{ $operation->montantDette }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="payer_par">Payé par</label>
                                        <select name="payer_par" id="payer_par" class="form-control" required>
                                            <option value="" disabled>Selectionner un compte</option>
                                            @foreach ($comptes as $compte)
                                                <option value="{{ $compte->id }}"
                                                    {{ $operation->payer_par == $compte->id ? 'selected' : '' }}>
                                                    {{ $compte->Denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                <a href="{{ route('vente-sujets.index') }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>


                    </div>
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
                    <h5 class="modal-title" id="categorieModalLabel">Liste des catégories</h5>
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
                                                <input type="checkbox" class="categorie-checkbox"
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
                    <button type="button" class="btn btn-primary" id="ajoutercategoriesBtn">Ajouter</button>
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
    <script src="{{ asset('js/backend/editventesujet.js')}}"></script>


</body>
