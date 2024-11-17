@include('partials._head')

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
                        <h4>Statistique de la vache :<span class="text-success"> {{ $vache->nom }} </span> </h4>
                        <h6 id="titre">Liste Vaches/statistique</h6>
                    </div>
                </div>
                <div class="page-btn" id="back">
                    @if ($vache->type_elevage=="lait")
                    <a href="{{ url('laitieres') }}" class="btn btn-secondary"> <i class="fa fa-arrow-circle-left"></i>
                        Retour a la liste </a>
                    @endif

                        @if ($vache->type_elevage=="viande")
                        <a href="{{ url('bovins') }}" class="btn btn-secondary"> <i class="fa fa-arrow-circle-left"></i>
                            Retour a la liste </a>
                        @endif
                </div>
                <br>
                <!-- Formulaire de filtrage -->
                <div class="row mb-4" id="filtre">
                    <div class="col-md-12">
                        <form id="filterForm">
                            <div class="row">
                                <input type="hidden" name="vache_id" id="vache_id" value="{{ $vache->id }}">
                                <input type="hidden" name="type" id="type" value="{{ $vache->type_elevage }}">
                                <div class="col-md-2" id="startD">
                                    <input type="date" class="form-control" id="startDate" name="startDate"
                                        >
                                </div>
                                <div class="col-md-2" id="endD">
                                    <input type="date" class="form-control" id="endDate" name="endDate"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-2">
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
                <div class="row">
                    <div class="col-md-12">
                        @if ($vache->type_elevage == "lait")
                        <div class="card bg-light mb-3">
                            <div class="card-header" id="lineChartHeader">Courbe de production de lait</div>
                            <div class="card-body">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                        @endif

                        <!--div class="card bg-light mb-3">
                            <div class="card-header" id="consumptionChartHeader">Courbe de consommation</div>
                            <div class="card-body">
                                <canvas id="lineChart1"></canvas>
                            </div>
                        </div-->
                    </div>


                    <!--div class="col-md-12">
                        <div class="card bg-light mb-3">
                            <div class="card-header" id="comparisonChartHeader">Courbe de production de lait par rapport à la consommation</div>
                            <div class="card-body">
                                <canvas id="lineChart2"></canvas>
                            </div>
                        </div>
                    </div-->


                    <div class="col-md-6">
                        <!-- Additional content if necessary -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('partials.script')
    <script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>
    <script src="{{ asset('assets/js/jspdf.umd.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('js/backend/statvache.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('exportPdf').addEventListener('click', function () {
                // Sélection de la partie que vous souhaitez exporter en PDF
                var element = document.querySelector(".page-wrapper");
                var exportButton = document.getElementById('exportPdf');  // Bouton à masquer
                var formFiltre =document.getElementById('filtre');
                var retour= document.getElementById('back');
                // Masquer le bouton d'exportation
                exportButton.style.display = 'none';
                formFiltre.style.display= 'none';
                retour.style.display= 'none';

                let startDate = document.getElementById('startDate').value;
                let endDate = document.getElementById('endDate').value;
                let type="Statistique de la vache du ";

                // Convert dates to readable format (month name)
                // Convert dates to readable format (day number, month name, year number)
                let start = new Date(startDate);
                let end = new Date(endDate);

                let options = { year: 'numeric', month: 'long', day: 'numeric' };

                let startFormatted = start.toLocaleDateString('default', options);  // e.g., 12 May 2024
                let endFormatted = end.toLocaleDateString('default', options);      // e.g., 25 September 2024

                // Update the text in the <p> element
                document.getElementById('titre').innerText = `${type} ${startFormatted} au ${endFormatted}`;

                // Créer un div temporaire pour ajouter le logo et les infos spécifiques au PDF
                var pdfContent = document.createElement('div');
                pdfContent.classList.add('pdf-only');  // Classe pour la mise en forme spécifique du PDF
                pdfContent.innerHTML = `
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="{{ asset('assets/img/logo-gferme.png') }}" alt="Logo Entreprise" style="width: 100px;">
                        <h2>Banankabougou</h2>
                        <p>Adresse de l'entreprise<br>
                        Téléphone : +123 456 789<br>
                        Email : entreprise@example.com</p>
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
                    pdf.save('statVache.pdf');

                    // Supprimer l'élément temporaire après la génération du PDF
                    pdfContent.remove();

                    // Rendre le bouton d'exportation visible à nouveau
                    exportButton.style.display = 'block';
                    formFiltre.style.display ='block';
                    retour.style.display='block';
                }).catch(function (error) {
                    console.error("Erreur pendant la génération du PDF : ", error);
                });
            });
        });
    </script>
    <script>
        // Fonction pour obtenir le premier jour du mois en cours
        function getFirstDayOfMonth() {
            var today = new Date();
            return new Date(today.getFullYear(), today.getMonth(), 1);
        }

        // Fonction pour obtenir le dernier jour du mois en cours
        function getLastDayOfMonth() {
            var today = new Date();
            return new Date(today.getFullYear(), today.getMonth() + 1, 0);
        }

        // Formatage de la date en 'YYYY-MM-DD'
        function formatDate(date) {
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            return date.getFullYear() + "-" + month + "-" + day;
        }

        // Appliquer les dates par défaut uniquement si elles ne sont pas définies
        document.addEventListener('DOMContentLoaded', function() {
            var startDateInput = document.getElementById('startDate');
            var endDateInput = document.getElementById('endDate');

            if (!startDateInput.value) {
                startDateInput.value = formatDate(getFirstDayOfMonth());
            }

            if (!endDateInput.value) {
                endDateInput.value = formatDate(getLastDayOfMonth());
            }
        });
        </script>

</body>
