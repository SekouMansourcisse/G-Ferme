@include('partials._head')

<body>
    <style>
        .red-text {
        color: red;
    }

    </style>
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
                        <h4>Enregistrer une bande</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" id="add-ravitaillement-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="responsable">Responsable cycle <span class="text-danger">*</span></label>
                                        <select class="form-control" id="responsable" name="responsable" required>
                                            @foreach($users as $user)
                                            @if ($user->profil=="simple utilisateur")
                                            <option value="{{ $user->id }}">{{ $user->firstname .' '.$user->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="nom_bande">Nom de la bande <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nom_bande" name="nom_bande" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="cheptel_depart">Cheptel de départ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cheptel_depart" name="cheptel_depart" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="type_elevage">Type d'élevage <span class="text-danger">*</span></label>
                                        <select class="form-control" id="type_elevage" name="type_elevage" required>
                                            <option value="Poulet de chair">Poulet de chair</option>
                                            <option value="Poules pondeuses">Poules pondeuses</option>
                                            <!-- Other options -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="souche_bande">Souche bande <span class="text-danger">*</span></label>

                                        <select name="souche_bande" id="souche_bande" class="form-control" required>
                                            <option value="" selected disabled>Selectionnez un fournisseur</option>
                                            @foreach($souches as $souche)
                                            <option value="{{ $souche->id }}">{{ $souche->Denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date_demarrage">Date de démarrage <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date_demarrage" name="date_demarrage" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date_fin">Date de fin prévue <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date_fin" name="date_fin" readonly required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="age_arrive">Âge d'arrivée de la bande(Jours) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="age_arrive" name="age_arrive" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="poid_moyen_depart">Poids moyen de départ(Gram) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="poid_moyen_depart" name="poid_moyen_depart" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="cout_acquisition">Coût de l'acquisition <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cout_acquisition" name="cout_acquisition" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="fournisseur_id">Nom du fournisseur <span class="text-danger">**</span></label>
                                        <select name="fournisseur_id" id="fournisseur_id" class="form-control" required>
                                            <option value="" selected disabled>Selectionnez un fournisseur</option>
                                            @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#addFournisseurModal">Nouveau fournisseur</button>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="observation">Observation sur la bande</label>
                                        <textarea class="form-control" id="observation" name="observation"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sélectionner</th> <!-- Nouvelle colonne pour la case à cocher -->
                                            <th>Dénomination poulailler</th>
                                            <th>Contenance normale</th>
                                            <th>Dimension</th>
                                            <th>Cheptel de départ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produits-table-body">
                                        @foreach($poulaillers as $poulailler)
                                        <tr>
                                            <td><input type="checkbox" name="poulailler_selectionne[]" value="{{ $poulailler->id }}"></td> <!-- Case à cocher -->
                                            <td><input type="numeric" name="prix_unitaire_p[]" value="{{ $poulailler->Denomination }}" class="form-control prix-unitaire" readonly required></td>
                                            <td><input type="numeric" name="contenance[]" value="{{ $poulailler->contenance_normale }}" class="form-control qte-produit" readonly required></td>
                                            <td><input type="numeric" name="dimension[]" value="{{ $poulailler->Dimension }}" class="form-control prix-unitaire" readonly required></td>
                                            <td><input type="numeric" name="nombre_cheptel[]" class="form-control prix-total" required></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                            </div>

                            <div class="row mt-4">

                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_remise">Total remise <span class="text-danger">**</span></label>
                                        <input type="number"  class="form-control" name="total_remise" id="total_remise" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="net_payer">Net à payer</label>
                                        <input type="number"  class="form-control" name="net_payer" id="net_payer" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="montant_paye">Montant payé <span class="text-danger">**</span></label>
                                        <input type="number"  class="form-control" name="montant_paye" id="montant_paye" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dette_a_paye">Dette à payer</label>
                                        <input type="number"  class="form-control" name="dette_a_paye" id="dette_a_paye" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="payer_par">Payé par</label>

                                        <select name="payer_par" id="payer_par" class="form-control" required>
                                            <option value="" selected disabled>Selectionner un compte</option>
                                            @foreach($comptes as $compte)
                                            <option value="{{ $compte->id }}">{{ $compte->Denomination }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <button type="button" class="btn btn-secondary" onclick="window.history.back();">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addFournisseurModal" tabindex="-1" aria-labelledby="addFournisseurModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFournisseurModalLabel">Ajouter un nouveau fournisseur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="add-fournisseur-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" name="nom" id="nom" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Prénom</label>
                                    <input type="text" name="prenom" id="prenom" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Redevance Initiale</label>
                                    <input type="text" name="redevance_initiale" id="redevance_initiale" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Téléphone</label>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Numéro WhatsApp</label>
                                    <input type="text" name="num_whatsapp" id="num_whatsapp" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Adresse Physique</label>
                                    <input type="text" name="adresse_physique" id="adresse_physique" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Produit</label>
                                    <select class="form-control" name="produit" id="produit">
                                        <option value="">Sélectionnez un produit</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}">{{ $produit->Denomination }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Informations Supplémentaires</label>
                                    <textarea name="infos_supp" id="infos_supp" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary me-2">Enregistrer</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
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
    <script>
        $(document).ready(function() {

            document.getElementById('date_demarrage').addEventListener('change', function() {
        const dateDemarrage = new Date(this.value);
        if (!isNaN(dateDemarrage.getTime())) {
            // Ajoutez 18 mois à la date de démarrage
            dateDemarrage.setMonth(dateDemarrage.getMonth() + 18);

            // Formatez la date de fin prévue en yyyy-mm-dd
            const year = dateDemarrage.getFullYear();
            const month = (dateDemarrage.getMonth() + 1).toString().padStart(2, '0');
            const day = dateDemarrage.getDate().toString().padStart(2, '0');
            const dateFinPrev = `${year}-${month}-${day}`;

            // Définissez la date de fin prévue dans l'input
            document.getElementById('date_fin').value = dateFinPrev;
        }
    });
            // Soumission du formulaire d'ajout de fournisseur
            $('#add-fournisseur-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: "POST",
                    url: '/addfournisseur',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Le fournisseur a été ajouté avec succès.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK',
                            customClass: { confirmButton: 'btn btn-primary' }
                        }).then(() => { location.reload(); });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de l\'ajout du fournisseur.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK',
                            customClass: { confirmButton: 'btn btn-primary' }
                        });
                    }
                });
            });
        });
    </script>
    <script>
$(document).ready(function() {
    $('#add-ravitaillement-form').on('submit', function(event) {
        event.preventDefault(); // Empêche la soumission par défaut du formulaire

        let formData = $(this).serialize(); // Sérialise les données du formulaire

        $.ajax({
            url: '/addbande', // Remplacez par l'URL de votre route pour le stockage de la bande
            method: 'POST',
            data: formData,
            success: function(response) {
                // Vérifiez la réponse du serveur et affichez un message de succès
                if (response.success) {
                    Swal.fire({
                        title: 'Succès',
                        text: 'La bande a été ajoutée avec succès',
                        icon: 'success'
                    }).then(() => {
                        // Redirigez ou effectuez une autre action après l'ajout réussi
                        window.location.href = '/bande'; // Remplacez par l'URL de redirection souhaitée
                    });
                } else {
                    Swal.fire({
                        title: 'Erreur',
                        text: response.message || 'Une erreur est survenue',
                        icon: 'error'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Affichez un message d'erreur en cas de problème avec la requête AJAX
                Swal.fire({
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de l\'ajout de la bande',
                    icon: 'error'
                });
            }
        });
    });
});
function calculateNetAPayer() {
        const coutAcquisition = parseFloat(document.getElementById('cout_acquisition').value) || 0;
        const totalRemise = parseFloat(document.getElementById('total_remise').value) || 0;
        const netAPayer = coutAcquisition - totalRemise;
        document.getElementById('net_payer').value = netAPayer.toFixed(2);
        calculateDetteAPayer();
    }

    function calculateDetteAPayer() {
        const netAPayer = parseFloat(document.getElementById('net_payer').value) || 0;
        const montantPaye = parseFloat(document.getElementById('montant_paye').value) || 0;
        const detteAPayer = netAPayer - montantPaye;
        document.getElementById('dette_a_paye').value = detteAPayer.toFixed(2);
    }

    document.getElementById('cout_acquisition').addEventListener('input', calculateNetAPayer);
    document.getElementById('total_remise').addEventListener('input', calculateNetAPayer);
    document.getElementById('montant_paye').addEventListener('input', calculateDetteAPayer);
    </script>

</body>
