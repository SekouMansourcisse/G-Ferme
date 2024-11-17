@include('partials._head')
<style>
    .card {
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.1);
}

.card-body h5 {
    font-size: 1.2rem;
    color: #333;
}

.card-body p {
    color: #007bff;
}

.bande-card {
    border: 3px solid #f39c12; /* Orange border */
    position: relative;
    padding: 10px;
}

.bande-title {
    position: absolute;
    top: -20px;
    left: 20px;
    background-color: white;
    padding: 0 5px;
    z-index: 10;
    font-weight: bold;
}

.bande-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 5px solid #2980b9; /* Blue border */
    z-index: -1;
    box-sizing: border-box;
}

</style>
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
                        <h4 id="titre">Rapport de production</h4>

                    </div>
                </div>
                <div class="row mb-4" id="filtre">
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
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end"> <button id="exportPdf" class="btn btn-primary no-print">Exporter en PDF</button></div>
                        </div>
                        <div class="container mt-5">
                            @foreach ($rapports as $rapport)
                            <div class="row mb-4 bande-card">
                                <div class="col-lg-12">
                                    <h4 class="bande-title">Bande {{ $rapport['bande']->nom_bande }}</h4>
                                </div>

                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Âge actuel</h5>
                                                        <p>
                                                            @if (floor($rapport['ageDetails']['months']) >= 1)
                                                                {{ floor($rapport['ageDetails']['months']) }} mois
                                                                @if (floor($rapport['ageDetails']['weeks']) >= 1)
                                                                    , {{ floor($rapport['ageDetails']['weeks']) }} semaines
                                                                @endif
                                                                @if (floor($rapport['ageDetails']['days']) >= 1)
                                                                    , {{ floor($rapport['ageDetails']['days']) }} jours
                                                                @endif
                                                            @elseif (floor($rapport['ageDetails']['weeks']) >= 1)
                                                                {{ floor($rapport['ageDetails']['weeks']) }} semaines
                                                                @if (floor($rapport['ageDetails']['days']) >= 1)
                                                                    , {{ floor($rapport['ageDetails']['days']) }} jours
                                                                @endif
                                                            @else
                                                                {{ floor($rapport['ageDetails']['days']) }} jours
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Effectif initial</h5>
                                                        <p>{{ $rapport['bande']->cheptel_depart }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Effectif actuel</h5>
                                                        <p>{{ $rapport['bande']->cheptel_actuel }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Sujet Mort</h5>
                                                        <p>{{$rapport['journalisation']->sum('Sujet_Mort')}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Taux de mortalité</h5>
                                                        <p>{{ number_format(($rapport['journalisation']->sum('Sujet_Mort') / $rapport['bande']->cheptel_depart) * 100, 2) }}%</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($rapport['bande']->type_elevage === 'Poules pondeuses')
                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Œuf Total produit</h5>
                                                        <p>{{ $rapport['oeufs'] ? $rapport['oeufs'] : 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Taux de pointe global</h5>
                                                        <p>{{ $rapport['tauxPointe'] ? number_format($rapport['tauxPointe'], 2) : 'N/A' }}%</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Taux de croissance</h5>
                                                        <p>{{ $rapport['tauxCroissance'] ? number_format($rapport['tauxCroissance'], 2) : 'N/A' }}%</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Consommation</h5>
                                                        <p>{{ $rapport['qte_c'] }} kg</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5>Nombre de traitements</h5>
                                                        <p>{{ $rapport['journalisation']->count() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            @endforeach
                        </div>
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
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('exportPdf').addEventListener('click', function () {
                // Sélection de la partie que vous souhaitez exporter en PDF
                var element = document.querySelector(".page-wrapper");
                var exportButton = document.getElementById('exportPdf');  // Bouton à masquer
                var formFiltre =document.getElementById('filtre');
                // Masquer le bouton d'exportation
                exportButton.style.display = 'none';
                formFiltre.style.display= 'none';

                let startDate = document.getElementById('startDate').value;
                let endDate = document.getElementById('endDate').value;
                // Convert dates to readable format (month name)
                // Convert dates to readable format (day number, month name, year number)
                let start = new Date(startDate);
                let end = new Date(endDate);

                let options = { year: 'numeric', month: 'long', day: 'numeric' };

                let startFormatted = start.toLocaleDateString('default', options);  // e.g., 12 May 2024
                let endFormatted = end.toLocaleDateString('default', options);      // e.g., 25 September 2024

                // Update the text in the <p> element
                document.getElementById('titre').innerText = `Rapport de Production du ${startFormatted} au ${endFormatted}`;

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
                    pdf.save('rapport-production.pdf');

                    // Supprimer l'élément temporaire après la génération du PDF
                    pdfContent.remove();

                    // Rendre le bouton d'exportation visible à nouveau
                    exportButton.style.display = 'block';
                    formFiltre.style.displayc ='block'
                }).catch(function (error) {
                    console.error("Erreur pendant la génération du PDF : ", error);
                });
            });
        });
    </script>
</body>
