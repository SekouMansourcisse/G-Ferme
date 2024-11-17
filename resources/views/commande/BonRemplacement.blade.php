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
                @if (session('success'))
                <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
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
                        <div class="page-header">
                            <div class="page-title">
                                <h4 >Retour vente</h4>
                                <h6>Bon de remplacement</h6>
                            </div>
                        </div>
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
                        <!-- Détails de la facture -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p><strong>Date de facture :</strong>
                                    {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</p>
                                <p><strong>Numéro de commande :</strong> #{{ $commande->id }}</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <p><strong>Statut :</strong> {{ $etatRemboursement == true ? 'Bon non signé' : 'Bon signé' }}</p>
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
                                            <th>Quantité Initiale</th>
                                            <th>Quantité à Remplacer</th>
                                            <th>Prix Unitaire</th>
                                            <th>Montant perte</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $TotalR=0;
                                        @endphp
                                        @foreach ($operations as $operation)
                                            @php
                                                $qteRetour = explode(';', $operation->qteR);
                                                $TotalR+=$operation->Montant_R;
                                            @endphp
                                            @foreach ($qteRetour as $item)
                                                @php
                                                    [$elementId, $qte] = explode('*', $item);
                                                @endphp
                                                <!-- Affichage des produits, œufs et poulets -->
                                                @if ($operation->TypeVenteR == 'vente-autre')
                                                    @if (!empty($commande->produit))
                                                        @foreach (explode(',', $commande->produit) as $produit)
                                                            @php
                                                                [
                                                                    $produitId,
                                                                    $quantite,
                                                                    $prixUnitaire,
                                                                    $montantTotal,
                                                                ] = explode('*', $produit);

                                                            @endphp
                                                            <tr>
                                                                <td>Produit</td>
                                                                <td>{{ $quantite }}</td>
                                                                <td>{{ $qte }}</td>

                                                                <td>{{ $prixUnitaire }}</td>
                                                                <td>{{ $operation->Montant_R }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endif

                                                @if ($operation->TypeVenteR == 'vente-oeuf')
                                                    @if (!empty($commande->oeufs))
                                                        @foreach (explode(',', $commande->oeufs) as $oeuf)
                                                            @php
                                                                [$categorieId, $quantite, $montantTotal] = explode(
                                                                    '*',
                                                                    $oeuf,
                                                                );
                                                                $prixUnitaire = $montantTotal / $quantite;
                                                            @endphp
                                                            <tr>
                                                                <td>Œuf</td>
                                                                <td>{{ $quantite }}</td>
                                                                <td>{{ $qte }}</td>

                                                                <td>{{ $prixUnitaire }}</td>
                                                                <td>{{ $operation->Montant_R }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endif

                                                @if ($operation->TypeVenteR == 'vente-sujet')
                                                    @if (!empty($commande->poulets))
                                                        @foreach (explode(',', $commande->poulets) as $poulet)
                                                            @php
                                                                [
                                                                    $bandeId,
                                                                    $quantite,
                                                                    $prixUnitaire,
                                                                    $montantTotal,
                                                                ] = explode('*', $poulet);
                                                            @endphp
                                                            <tr>
                                                                <td>Poulet</td>
                                                                <td>{{ $quantite }}</td>
                                                                <td>{{ $qte }}</td>

                                                                <td>{{ $prixUnitaire }}</td>
                                                                <td>{{ $operation->Montant_R }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>


                            </div>
                        </div>
                        <!-- Montants finaux -->
                        <div class="row mt-4 justify-content-end">
                            <div class="col-md-4">
                                <p><strong>Montant perte :</strong> {{ $TotalR }} X0F</p>
                                <h4 class="text-right" style="background-color: #b38526; padding: 10px; color: white;">
                                    Total perte: {{ $TotalR }} X0F</h4>
                            </div>
                        </div>
                        <!-- Boutons d'action avec alignement -->
                        <div class="row mt-4">
                            <div class="col-md-6 text-left">

                                <a href="{{ url('RetourVente') }}" class="btn btn-secondary float-left mb-1"
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
                                @if ($etatRemboursement)
                                <a href="javascript:void(0);" id="pay" class="btn btn-success" data-toggle="modal"
                                data-target="#paymentModal"><i class="fa fa-file-invoice"></i> Televerser Bon signé
                                </a>
                                @endif

                            </div>
                        </div>
                        @if (!$etatRemboursement)
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
                        @endif
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
                    <h5 class="modal-title" id="paymentModalLabel">Importer la facture signé</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <!-- Formulaire de paiement -->
                    <form action="{{ route('uploadRemplacement')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="commande_id" id="commande_id" value="{{$commande->id}}">
                        <div class="form-group">
                            <label for="logo">Bon de Sortie signé</label>
                            <div class="image-upload">
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*,.pdf">
                                <div class="image-uploads">
                                    <!-- Aperçu de l'image ou du fichier PDF -->
                                    <div id="file-preview" style="margin-top: 10px;">
                                        <img id="preview-image" src="{{asset('assets/img/icons/upload.svg')}}" alt="Aperçu" style="max-width: 200px; display: none;">
                                        <a id="preview-pdf" href="#" target="_blank" style="display: none; font-size: 16px; color: blue;">Voir le fichier PDF</a>
                                    </div>
                                    <h4>Ajouter le Bon de Sortie Signé</h4>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Televerser Fichier</button>
                            </div>
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
        document.getElementById('print').addEventListener('click', function () {
            var exportButton = document.getElementById('exportPdf');  // Bouton à masquer
            var printButton = document.getElementById('print');
            var backButton = document.getElementById('back');
            var titre1 = document.getElementById('titre1');
            var titre2 = document.getElementById('titre2');
            var signer = document.getElementById('signer');

            // Masquer les éléments avant l'exportation
            exportButton.style.display = 'none';
            printButton.style.display = 'none';
            backButton.style.display = 'none';
            titre1.style.display = 'none';
            titre2.style.display = 'none';
            signer.style.display = 'block';
            window.print();
        });
        document.getElementById('exportPdf').addEventListener('click', function () {
            // Sélection de la partie que vous souhaitez exporter en PDF
            var element = document.querySelector(".page-wrapper");
            var exportButton = document.getElementById('exportPdf');  // Bouton à masquer
            var printButton = document.getElementById('print');
            var backButton = document.getElementById('back');
            var titre1 = document.getElementById('titre1');
            var titre2 = document.getElementById('titre2');
            var signer = document.getElementById('signer');
            var infosF=document.getElementById('infosF');
            // Masquer les éléments avant l'exportation
            exportButton.style.display = 'none';
            printButton.style.display = 'none';
            backButton.style.display = 'none';
            titre1.style.display = 'none';
            titre2.style.display = 'none';
            signer.style.display = 'block';

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
                scale: 2,  // Améliorer la qualité
                useCORS: true,
                logging: true,
                windowWidth: document.body.scrollWidth,
                windowHeight: document.body.scrollHeight
            }).then(canvas => {
                var imgData = canvas.toDataURL('image/png');
                var imgWidth = 210;  // Largeur en mm (A4)
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

                // Enregistrer le PDF
                pdf.save('Bon_RemplacementCmdN°#' + {{ $commande->id }} + '.pdf');

                // Rendre les éléments visibles après la génération du PDF
                exportButton.style.display = 'block';
                printButton.style.display = 'block';
                backButton.style.display = 'block';
                titre1.style.display = 'block';
                titre2.style.display = 'block';
                signer.style.display = 'none';
                infosF.style.display='block';

                // Actualiser la page après un court délai (pour s'assurer que le téléchargement a commencé)
                setTimeout(function() {
                    window.location.reload();
                }, 1000);  // Attendre 1 seconde avant de rafraîchir
            }).catch(function (error) {
                console.error("Erreur pendant la génération du PDF : ", error);
            });
        });
    });
</script>

</body>
