@include('partials._head')
<style>
    .card { border: 1px solid #ccc; border-radius: 10px; box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.1); }
    .card-body h5 { font-size: 1.2rem; color: #333; }
    .card-body p { color: #007bff; }
    .bande-card { border: 3px solid #f39c12; position: relative; padding: 10px; margin-bottom: 30px; }
    .bande-title { position: absolute; top: -20px; left: 20px; background-color: white; padding: 0 5px; z-index: 10; font-weight: bold; }
    .bande-card::before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 5px solid #1b2850; z-index: -1; box-sizing: border-box; }
</style>

<body>
    <div class="main-wrapper">
        @include('partials._topbar')
        @include('partials._sidebar_collapsed')
        @include('partials._sidebar')
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4 id="titre">rapports Financier</h4>
                        <h6>Analyse des recettes et dépenses</h6>
                    </div>
                </div>

                <div class="row mb-4" id="filtre">
                    <div class="col-md-12">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-4" id="startD">
                                    <input type="date" class="form-control" id="startDate" name="startDate" value="2024-05-12">
                                </div>
                                <div class="col-md-4" id="endD">
                                    <input type="date" class="form-control" id="endDate" name="endDate" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary mt-1" onclick="applyFilter()">Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end"> <button id="exportPdf" class="btn btn-primary no-print">Exporter en PDF</button></div>
                </div>
                <!-- rapports financier - Recette -->
                <div class="bande-card">
                    <div class="bande-title">Recette</div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Totale Oeufs Vendu</h5>
                                    <p>{{ $rapports['Oeufs_v'] }} XOF</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Totale Sujet Vendu</h5>
                                    <p>{{ $rapports['Sujet_v'] }} XOF</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Totale autres ventes</h5>
                                    <p>{{ $rapports['autres_v'] }} XOF</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ravitaillement -->
                <div class="bande-card">
                    <div class="bande-title">Ravitaillement</div>
                    <div class="row">
                        @foreach($rapports['ravitaillements'] as $ravitaillement)
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{ $ravitaillement['typeProduit'] }}</h5>
                                    <p>Montant Total: {{ $ravitaillement['totalRavitaillement'] }} XOF</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Dépenses -->
                <div class="bande-card">
                    <div class="bande-title">Dépenses</div>
                    <div class="row">
                        @foreach($rapports['depenses'] as $depense)
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{ $depense['typeDepense'] }}</h5>
                                    <p>Montant Total: {{ $depense['totalDepenses'] }} XOF</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>


                <!-- Redevance Fournisseur -->
                <div class="bande-card">
                    <div class="bande-title">Redevance Fournisseur</div>
                    <div class="row">
                        @foreach($rapports['redevance_fournisseur'] as $redevance)
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{ $redevance->fournisseur->prenom }} {{ $redevance->fournisseur->nom }}</h5>
                                    <p>Montant dû: {{ $redevance->montant }} XOF</p>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Dette Client -->
                <div class="bande-card">
                    <div class="bande-title">Dette Client</div>
                    <div class="row">
                        @foreach($rapports['dette_client'] as $dette)
                        <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Client: {{ $dette->client }}</h5>
                                    <p>Montant dû: {{ $dette->montant }} XOF</p>
                                    <p>Date: {{ $dette->date }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
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
    <script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('exportPdf').addEventListener('click', function () {
        var element = document.querySelector(".page-wrapper");
        var exportButton = document.getElementById('exportPdf');
        var formFiltre = document.getElementById('filtre');

        // Masquer le bouton d'exportation et le filtre
        exportButton.style.display = 'none';
        if (formFiltre) {
            formFiltre.style.display = 'none';
        }

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
            scale: 2,          // Augmente la qualité de l'image
            useCORS: true,     // Permet le chargement des ressources distantes
            allowTaint: true,
            logging: true,
            windowWidth: document.body.scrollWidth,
            windowHeight: document.body.scrollHeight
        }).then(canvas => {
            var imgData = canvas.toDataURL('image/png');
            var imgWidth = 190; // Largeur ajustée pour marges
            var pageHeight = 295;
            var imgHeight = (canvas.height * imgWidth) / canvas.width;
            var heightLeft = imgHeight;

            // Création du PDF
            var pdf = new window.jspdf.jsPDF('p', 'mm', 'a4');
            var position = 10; // Marge supérieure de 10 mm

            // Ajouter l'image au PDF
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            // Ajouter des pages si nécessaire
            while (heightLeft > 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            // Enregistrer le PDF
            pdf.save('rapport-financier.pdf');

            // Supprimer l'élément temporaire après la génération du PDF
            pdfContent.remove();

            // Rendre le bouton d'exportation et le filtre visibles à nouveau
            exportButton.style.display = 'block';
            if (formFiltre) {
                formFiltre.style.display = 'block';
            }
        }).catch(function (error) {
            console.error("Erreur pendant la génération du PDF : ", error);
        });
    });
});


    </script>
</body>
