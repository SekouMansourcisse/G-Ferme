@include('partials._head')

<!-- CSS supplémentaire pour embellir la facture -->
<style>
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background-color: #f9f9f9;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .btn {
        margin: 5px;
    }

    .text-right {
        text-align: right;
    }

    @media print {
        .hide-on-print {
            display: none !important;
        }
    }

    /* Masquer la signature par défaut */
    #signer {
        display: none;
    }

    /* Afficher la signature lors de l'impression */
    @media print {
        #signer {
            display: block;
        }
    }

    @media print {
        #headerP {
            display: none;
        }
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
                        <h4 id="titre1">Détails de la facture</h4>
                        <h6 id="titre2">Commande N°{{ $commande->id }}</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <!-- Section Logo et infos entreprise à gauche / Infos client à droite -->
                        <div class="row">
                            <div class="col-md-12 text-center" id="infosF">
                                <!-- Logo et informations de l'entreprise -->
                                <img src="{{ asset('storage/' . ($settings->logo_facture ?? 'default_logo.png')) }}" alt="Logo Entreprise">

                                <h2>{{ $settings->nomFerme }}</h2>
                                <p>
                                    {{ $settings->adresse }}<br>
                                    Téléphone : {{ $settings->phone_ferme }}<br>
                                    Email : {{ $settings->email_ferme ?? 'Non spécifié' }}
                                </p>
                            </div>

                        </div>

                        @if ($commande->client != null)
                            <!-- Informations du client -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <label><strong>client :</strong></label>
                                    <input type="text" class="form-control"
                                        value="{{ $client->prenom }} {{ $client->nom }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label><strong>Adresse :</strong></label>
                                    <input type="text" class="form-control" value="{{ $client->adresse_physique }}"
                                        readonly>
                                </div>
                                <div class="col-md-3">
                                    <label><strong>N°Tel :</strong></label>
                                    <input type="text" class="form-control" value="{{ $client->phone }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label><strong>Email:</strong></label>
                                    <input type="text" class="form-control" value="{{ $client->email }}" readonly>
                                </div>


                            </div>
                        @else
                            <!-- Informations du client -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <label><strong>client :</strong></label>
                                    <input type="text" class="form-control" value="{{ $commande->NomPrenomClient }}"
                                        readonly>
                                </div>


                            </div>
                        @endif

                        <!-- Détails de la facture -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p><strong>Date de facture :</strong>
                                    {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</p>
                                <p><strong>Numéro de commande :</strong> #{{ $commande->id }}</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <p><strong>Statut :</strong> {{ $commande->etat == 1 ? 'Non payé' : 'Payé' }}</p>
                                <p><strong>Date d'échéance :</strong>
                                    {{ \Carbon\Carbon::parse($commande->date_echeance)->format('d/m/Y') }}</p>
                            </div>
                        </div>


                        <!-- Détails de la commande -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Type de Vente</th>
                                            <th>Quantité</th>
                                            <th>Prix Unitaire</th>
                                            <th>Montant Vente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Affichage des produits, œufs et poulets -->
                                        @if (!empty($commande->produit))
                                            @foreach (explode(',', $commande->produit) as $produit)
                                                @php
                                                    [$produitId, $quantite, $prixUnitaire, $montantTotal] = explode(
                                                        '*',
                                                        $produit,
                                                    );
                                                @endphp
                                                <tr>
                                                    <td>Produit</td>
                                                    <td>{{ $quantite }}</td>
                                                    <td>{{ $prixUnitaire }}</td>
                                                    <td>{{ $montantTotal }}</td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        @if (!empty($commande->oeufs))
                                            @foreach (explode(',', $commande->oeufs) as $oeuf)
                                                @php
                                                    [$categorieId, $quantite, $montantTotal] = explode('*', $oeuf);
                                                    $prixUnitaire = $montantTotal / $quantite;
                                                @endphp
                                                <tr>
                                                    <td>Œuf</td>
                                                    <td>{{ $quantite }}</td>
                                                    <td>{{ $prixUnitaire }}</td>
                                                    <td>{{ $montantTotal }}</td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        @if (!empty($commande->poulets))
                                            @foreach (explode(',', $commande->poulets) as $poulet)
                                                @php
                                                    [$bandeId, $quantite, $prixUnitaire, $montantTotal] = explode(
                                                        '*',
                                                        $poulet,
                                                    );
                                                @endphp
                                                <tr>
                                                    <td>Poulet</td>
                                                    <td>{{ $quantite }}</td>
                                                    <td>{{ $prixUnitaire }}</td>
                                                    <td>{{ $montantTotal }}</td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>


                            </div>
                        </div>
                        <!-- Montants finaux -->
                        <div class="row mt-4 justify-content-end">
                            <div class="col-md-4">
                                <p><strong>Montant Total :</strong> {{ $commande->TotalVente }} X0F</p>
                                <p><strong>Montant Remise :</strong> {{ $commande->TotalRemise }} X0F</p>
                                <p><strong>Montant Net a payer :</strong> {{ $commande->Net_a_payer }} X0F</p>
                                <p><strong>Montant Payé :</strong> {{ $commande->Montant_paye }} X0F</p>
                                <p><strong>Montant Dette :</strong> {{ $commande->MontantDette }} X0F</p>
                                <h4 class="text-right" style="background-color: #b38526; padding: 10px; color: white;">
                                    Total : {{ $commande->Net_a_payer }} X0F</h4>
                            </div>
                        </div>
                        <!-- Boutons d'action avec alignement -->
                        <div class="row mt-4">
                            <div class="col-md-6 text-left">

                                <a href="{{ url('commandes') }}" class="btn btn-secondary float-left mb-1"
                                    id="back"><i class="fa fa-arrow-circle-left"></i> Retour </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <a rel="noopener" class="btn btn-secondary float-right mr-2" id="print"><i
                                        class="fas fa-print"></i>
                                    Impression</a>
                                <a type="button" id="exportPdf" class="btn btn-primary float-right"
                                    style="margin-right: 5px;">
                                    <i class="fas fa-download"></i> produire PDF
                                </a>
                                @can('edit commandes')
                                <a href="javascript:void(0);" id="pay" class="btn btn-success" data-toggle="modal"
                                data-target="#paymentModal"><i class="far fa-credit-card"></i> Soumettre
                                Paiement</a>
                                @endcan

                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6 text-left" id="signer" style="display: none;">
                                <h6><strong>Signature:</strong></h6>
                                <br>
                                <br>
                                <p>Comptable</p>
                            </div>

                            <div class="col-md-6 text-right" id="signer2" style="display: none;">
                                <h6><strong>Signature:</strong></h6>
                                <br>
                                <br>
                                <p>Magasinier</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Paiement de la commande</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <!-- Formulaire de paiement -->
                    <form action="{{ route('commande.payment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="commande_id" id="commande_id" value="{{ $commande->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="NomPrenomClient">Nom du client</label>
                                <input type="text" class="form-control" id="NomPrenomClient"
                                    value="{{ $commande->NomPrenomClient }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="TotalVente">Montant Total facture</label>
                                <input type="text" class="form-control" id="TotalVente"
                                    value="{{ $commande->TotalVente }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="TotalRemise">Montant Remise</label>
                                <input type="text" class="form-control" id="TotalRemise"
                                    value="{{ $commande->TotalRemise }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="Net_a_payer">Montant net à payer</label>
                                <input type="text" class="form-control" id="Net_a_payer"
                                    value="{{ $commande->Net_a_payer }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="Montant_paye">Montant payé</label>
                                <input type="number" class="form-control" id="Montant_paye" name="montant_paye"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="Montant_dette">Montant dette</label>
                                <input type="text" class="form-control" id="Montant_dette" value="0"
                                    readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="payer_par">Payé par <span class="text-danger">*</span></label>
                                    <select name="payer_par" id="payer_par" class="form-control">
                                        @foreach ($comptes as $compte)
                                            <option value="{{ $compte->id }}">{{ $compte->Denomination }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Enregistrer Paiement</button>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="settings-data" style="display: none;">
        <span id="farmName">{{ $settings->nomFerme }}</span>
        <span id="farmAddress">{{ $settings->adresse }}</span>
        <span id="farmPhone">{{ $settings->phone_ferme }}</span>
        <span id="farmEmail">{{ $settings->email_ferme ?? 'Non spécifié' }}</span>
        @php
            $path = public_path('storage/' . ($settings->logo_facture ?? 'default_logo.png'));
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        @endphp
        <span id="farmLogoBase64" data-base64="{{ $base64 }}"></span>

    </div>

    @include('partials.script')
    <script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>
    <script src="{{ asset('assets/js/jspdf.umd.min.js') }}"></script>

    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/version/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script>
        document.getElementById('Montant_paye').addEventListener('input', function() {
            var montantNet = parseFloat(document.getElementById('Net_a_payer').value);
            var montantPaye = parseFloat(this.value);
            var montantDette = montantNet - montantPaye;
            document.getElementById('Montant_dette').value = montantDette >= 0 ? montantDette.toFixed(2) : 0;
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Impression
    document.getElementById('print').addEventListener('click', function () {
        var exportButton = document.getElementById('exportPdf');
        var printButton = document.getElementById('print');
        var backButton = document.getElementById('back');
        var titre1 = document.getElementById('titre1');
        var titre2 = document.getElementById('titre2');
        var pay = document.getElementById('pay');

        // Masquer les éléments avant l'impression
        exportButton.style.display = 'none';
        printButton.style.display = 'none';
        backButton.style.display = 'none';
        titre1.style.display = 'none';
        titre2.style.display = 'none';
        pay.style.display = 'none';

        // Imprimer
        window.print();

        // Afficher les éléments après l'impression
        setTimeout(function () {
            exportButton.style.display = 'block';
            printButton.style.display = 'block';
            backButton.style.display = 'block';
            titre1.style.display = 'block';
            titre2.style.display = 'block';
            pay.style.display = 'block';
        }, 1000);
    });

    // Exporter en PDF
    document.getElementById('exportPdf').addEventListener('click', function () {
        var element = document.querySelector(".page-wrapper");
        var exportButton = document.getElementById('exportPdf');
        var printButton = document.getElementById('print');
        var backButton = document.getElementById('back');
        var titre1 = document.getElementById('titre1');
        var titre2 = document.getElementById('titre2');
        var pay = document.getElementById('pay');
        var infosF=document.getElementById('infosF');

        // Masquer les éléments avant l'exportation
        exportButton.style.display = 'none';
        printButton.style.display = 'none';
        backButton.style.display = 'none';
        titre1.style.display = 'none';
        titre2.style.display = 'none';
        pay.style.display = 'none';
        infosF.style.display = 'none';
        // Récupérer les données des paramètres
        var farmName = document.getElementById('farmName').innerText;
        var farmAddress = document.getElementById('farmAddress').innerText;
        var farmPhone = document.getElementById('farmPhone').innerText;
        var farmEmail = document.getElementById('farmEmail').innerText;
        var farmLogo = document.getElementById('farmLogoBase64').getAttribute('data-base64');

        // Pour confirmer le lien d'image généré

        // Créer un div temporaire pour le contenu PDF
        var pdfContent = document.createElement('div');
        pdfContent.classList.add('pdf-only');
        pdfContent.innerHTML = `
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="${farmLogo}" alt="Logo Entreprise" style="width: 100px;">
                <h2>${farmName}</h2>
                <p>${farmAddress}<br>
                Téléphone : ${farmPhone}<br>
                Email : ${farmEmail}</p>
            </div>
        `;

        // Ajouter cet élément au début du document pour la capture
        element.prepend(pdfContent);
        // Utilisation de html2canvas pour capturer l'élément
        html2canvas(element, {
            scale: 2,
            useCORS: true,
            logging: true,
            windowWidth: document.body.scrollWidth,
            windowHeight: document.body.scrollHeight
        }).then(canvas => {
            var imgData = canvas.toDataURL('image/png');
            var imgWidth = 210; // Largeur en mm (A4)
            var pageHeight = 295; // Hauteur en mm (A4)
            var imgHeight = (canvas.height * imgWidth) / canvas.width;
            var heightLeft = imgHeight;

            // Création du PDF
            var pdf = new window.jspdf.jsPDF('p', 'mm', 'a4');
            var position = 0;

            // Ajouter l'image au PDF
            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            // Ajouter des pages si le contenu dépasse une page
            while (heightLeft > 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            // Enregistrer le PDF avec l'ID de commande intégré
            pdf.save('FactureN°#' + {{ $commande->id }} + '.pdf');

            // Afficher les éléments après la génération du PDF
            exportButton.style.display = 'block';
            printButton.style.display = 'block';
            backButton.style.display = 'block';
            titre1.style.display = 'block';
            titre2.style.display = 'block';
            pay.style.display = 'block';
            infosF.style.display='block';

            // Actualiser la page après un court délai
            setTimeout(function () {
                window.location.reload();
            }, 1000); // Attendre 1 seconde avant de rafraîchir
        }).catch(function (error) {
            console.error("Erreur pendant la génération du PDF : ", error);
        });
    });
});

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si l'on doit générer automatiquement le PDF après la redirection
            if ('{{ session('generate_pdf') }}' === 'true') {
                generatePdf(); // Fonction pour générer le PDF
            }

            // Fonction pour générer le PDF
            function generatePdf() {
                var element = document.querySelector(".page-wrapper");
                var exportButton = document.getElementById('exportPdf');
                var printButton = document.getElementById('print');
                var backButton = document.getElementById('back');
                var titre1 = document.getElementById('titre1');
                var titre2 = document.getElementById('titre2');
                var pay = document.getElementById('pay');
                var signer = document.getElementById('signer');
                var signer2 = document.getElementById('signer2');

                // Masquer les éléments avant l'exportation
                exportButton.style.display = 'none';
                printButton.style.display = 'none';
                backButton.style.display = 'none';
                titre1.style.display = 'none';
                titre2.style.display = 'none';
                pay.style.display = 'none';
                signer.style.display = 'block';
                signer2.style.display = 'block';

                // Utiliser html2canvas pour capturer l'élément
                html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    windowWidth: document.body.scrollWidth,
                    windowHeight: document.body.scrollHeight
                }).then(canvas => {
                    var imgData = canvas.toDataURL('image/png');
                    var imgWidth = 210;
                    var pageHeight = 295;
                    var imgHeight = (canvas.height * imgWidth) / canvas.width;
                    var heightLeft = imgHeight;

                    var pdf = new window.jspdf.jsPDF('p', 'mm', 'a4');
                    var position = 0;

                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    while (heightLeft > 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    pdf.save('FactureN°#' + '{{ $commande->id }}' + '.pdf');

                    // Réafficher les éléments après génération
                    exportButton.style.display = 'block';
                    printButton.style.display = 'block';
                    backButton.style.display = 'block';
                    titre1.style.display = 'block';
                    titre2.style.display = 'block';
                    signer.style.display = 'none';
                    signer2.style.display = 'none';

                    // Actualiser la page après un court délai
                    // Après génération du PDF, redirection avec session active
                    setTimeout(function() {

                        // Redirection vers la route commandes.index avec le paramètre active_tab
                        window.location.href = "{{ route('commandes.index') }}?active_tab=paye";


                    }, 1000); // Attendre 1 seconde avant de rediriger
                }).catch(function(error) {
                    console.error("Erreur pendant la génération du PDF : ", error);
                });
            }
        });
    </script>

</body>
