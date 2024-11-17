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
        border: 3px solid #f39c12;
        position: relative;
        padding: 10px;
        margin-bottom: 30px;
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
        border: 5px solid #1b2850;
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
                        <h4 id="titre">Statistiques des <span class="text-success">Centres </span> </h4>
                        <h6></h6>
                    </div>
                </div>

                <div class="container">
                    <!-- Formulaire de filtrage -->
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
                                            value="{{ date('Y-m-d') }}" >
                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary mt-1"
                                            onclick="applyFilter()">Filtrer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end"> <button id="exportPdf" class="btn btn-primary no-print">Exporter en PDF</button></div>
                    </div>
                    <!-- Graphique des ventes -->
                    <div class="row">

                        <div class="col-lg-8">
                            <div class="card ">
                                <p id="Nombre" class="text-center"> Evolution des ventes</p>
                                <div id="chart"></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card bg-info mb-4">
                                <div class="card-header" id="mainChartHeader">Redevance Total fournisseurs <p
                                        id="Nombre"></p>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="card-title" style="font-size: 3em; color: #FF6F61;" id="mainChartValue">
                                    </h2>

                                </div>
                            </div>
                            <div class="card bg-secondary mb-4">
                                <div class="card-header" id="mainChartHeader">Dette Total des clients <p id="Nombre">
                                    </p>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="card-title" style="font-size: 3em; color: #FF6F61;" id="mainChartValue">
                                    </h2>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bande-card">
                        <div class="bande-title" id="st">Situation activité</div>
                        <div class="row">
                            <br><br><br>
                            <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5>Totale Oeufs Vendu</h5>
                                        <p> XOF</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5>Totale Sujet Vendu</h5>
                                        <p> XOF</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-8 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5>Totale autres ventes</h5>
                                        <p>XOF</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- First graph for band duration -->
                    <div class="row">

                        <div class="col-md-7">
                            <div class="card">
                                <p class="text-center">Indicateur sur la durée d'une bande</p>

                                <div class="col-md-11 d-flex justify-content-end">
                                    <form id="bandFilterForm" class="float-end" onchange="bandeDuration()">
                                        <select id="fermeSelect" class="form-select">
                                            <option value="">Tous les fermes</option>
                                            <option value="">Ferme-1</option>
                                            <!-- Ajouter des options ici dynamiquement -->
                                        </select>
                                    </form>
                                </div>

                                <div id="bandDurationChart"></div>
                            </div>
                        </div>

                        <!-- Second graph for mortality, growth, and peak rate -->

                        <div class="col-md-5">
                            <div class="card">
                                <p class="text-center">Indicateur sur la performance d'une bande</p>
                                <div class="col-md-11 d-flex justify-content-end">
                                    <form id="performanceFilterForm">
                                        <select class="form-select" id="performanceFermeSelect"
                                            onchange="PerformanceBande()">
                                            <option>Tous les fermes</option>
                                            <option>Ferme-1</option>
                                        </select>
                                    </form>
                                </div>
                                <div id="performanceChart"></div>
                            </div>
                        </div>


                    </div>
                    <div class="rox"></div>
                    <br><br>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">
                                <p class="text-center" id="poules">poules pondeuse : Evolution des collectes</p>

                                <div id="RamassageChart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">
                                <p class="text-center" id="poulet">poulet de chair : Evolution du poids des poulets</p>

                                <div id="PesageChart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">
                                <p class="text-center" id="depense">Graphes des dépenses</p>

                                <div id="DepenseChart"></div>
                            </div>
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('js/backend/statistiqueFerme.js')}}"></script>

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
                document.getElementById('titre').innerText = `Rapport des Fermes ${startFormatted} au ${endFormatted}`;

                // Récupérer les données des paramètres
                var farmName = document.getElementById('farmName').innerText;
                var farmAddress = document.getElementById('farmAddress').innerText;
                var farmPhone = document.getElementById('farmPhone').innerText;
                var farmEmail = document.getElementById('farmEmail').innerText;
                var farmLogo = document.getElementById('farmLogoBase64').getAttribute('data-base64');
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
                    pdf.save('rapport-fermes.pdf');

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
