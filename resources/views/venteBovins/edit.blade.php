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
                        <h4>Interface d'edition vente de vache</h4>
                        <h6>Modifier une vente</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('ventes_bovins.update', $vente->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <input type="hidden" name="type" value="vente-oeuf">

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">Date Vente <span class="text-danger">**</span></label>
                                        <input type="date" class="form-control" name="date" id="date"
                                            value="{{ old('date', $vente->date_vente) }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_client">Type de client<span
                                                class="text-danger">**</span></label>
                                        <select name="type_client" id="type_client" class="form-control" required>
                                            <option value="" disabled>Selectionnez le type de client</option>
                                            <option value="Client Comptoir"
                                                {{ $vente->client == null ? 'selected' : '' }}>Client Comptoir</option>
                                            <option value="Client Fidèle"
                                                {{ $vente->client != null ? 'selected' : '' }}>Client Fidèle</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Champs client -->
                            <div class="row">
                                <div class="col-lg-6 col-sm-12" id="client_comptoir_div"
                                    style="{{ $vente->client == null ? 'display: block;' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="client_comptoir">Nom & Prénom du client<span
                                                class="text-danger">**</span></label>
                                        <input type="text" name="client_comptoir" id="client_comptoir"
                                            class="form-control" value="Client comptoir">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="client_fidele_div"
                                    style="{{ $vente->client != null ? 'display: block;' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="client_id">Nom & Prénom du client<span
                                                class="text-danger">**</span></label>
                                        <select name="client_id" id="client_id" class="form-control">
                                            <option value="" disabled>Selectionnez un client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" data-phone="{{ $client->phone }}"
                                                    data-dette="{{ $client->dette_initiale }}"
                                                    data-addresse="{{ $client->adresse_physique }}"
                                                    {{ $vente->client == $client->id ? 'selected' : '' }}>
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
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            value="{{ $vente->phone }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="dette_div"
                                    style="{{ $vente->client ? 'display: block;' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label>Dette Actuelle<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="dette_initiale"
                                            style="color: red; font-weight: bold;" id="dette_initiale"
                                            class="form-control" value="{{ $vente->dette_initiale }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12" id="adresse_div"
                                    style="{{ $vente->client ? 'display: block;' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label>Adresse<b><span class="text-danger">*</span></b></label>
                                        <input type="text" name="addresse" style="color: red; font-weight: bold;"
                                            id="addresse" class="form-control" value="{{ $vente->addresse }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Champs vaches -->
                            <div class="table-responsive mt-4">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nom ou N° de la vache</th>
                                            <th>Prix de Vente</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="vaches-table-body">
                                        @foreach ($vente->vaches as $vache)
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <select name="operation_id[]" class="form-control select2">
                                                            <option value="" disabled>Selectionnez la vache
                                                            </option>
                                                            @foreach ($vaches as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $vache->operation_id == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->nom }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="numeric" name="prix_vente[]"
                                                        class="form-control prix-vente"
                                                        value="{{ $vache->prix_vente }}" required>
                                                </td>
                                                <td><button type="button"
                                                        class="btn btn-danger remove-row">X</button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                <button type="button" class="btn btn-secondary" id="add-product-row">Ajouter une
                                    vache</button>
                            </div>

                            <!-- Champs financiers -->
                            <div class="row mt-4">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_ravitaillement">Montant Vente</label>
                                        <input type="number" class="form-control" name="total_ravitaillement"
                                            id="total_ravitaillement"
                                            value="{{ old('total_ravitaillement', $vente->prix_vente) }}" required
                                            readonly>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_remise">Total remise <span
                                                class="text-danger">**</span></label>
                                        <input type="number" class="form-control" name="total_remise"
                                            id="total_remise" value="{{ old('total_remise', $vente->total_remise) }}"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="net_payer">Net à payer</label>
                                        <input type="number" class="form-control" name="net_payer" id="net_payer"
                                            value="{{ old('net_payer', $vente->prix_vente - $vente->total_remise) }}"
                                            required readonly>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_paye">Montant payé <span
                                                class="text-danger">**</span></label>
                                        <input type="number" class="form-control" name="montant_paye"
                                            id="montant_paye" value="{{ old('montant_paye', $vente->montant_paye) }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dette_a_paye">Dette à payer</label>
                                        <input type="number" class="form-control" name="dette_a_paye"
                                            id="dette_a_paye" value="{{ $vente->montantDette }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="payer_par">Payé par</label>
                                        <select name="payer_par" id="payer_par" class="form-control" required>
                                            <option value="" disabled>Selectionner un compte</option>
                                            @foreach ($comptes as $compte)
                                                <option value="{{ $compte->id }}"
                                                    {{ old('payer_par', $vente->payer_par) == $compte->id ? 'selected' : '' }}>
                                                    {{ $compte->Denomination }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
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
    <script src="{{ asset('js/backend/edit_venteB.js') }}"></script>

</body>
