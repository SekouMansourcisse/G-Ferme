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
                        <h4>Statistique de la bande :<span class="text-success"> {{ $bande->nom_bande }} </span> </h4>
                        <h6 id="titre">Liste Bande/statistique</h6>
                    </div>
                </div>
                <div class="page-btn" id="back">
                    <a href="{{url('listbande')}}" class="btn btn-primary"> <img src="{{ asset('assets/img/icons/return1.svg')}}" alt="img">
                     Retour a la liste de bande   </a>
                </div>
                <!-- Formulaire de filtrage -->
                <div class="row mb-4" id="filtre">
                    <div class="col-md-12">
                        <form id="filterForm">
                            <div class="row">
                                <input type="hidden" name="bande_id" id="bande_id" value="{{ $bande->id }}">
                                <div class="col-md-3">
                                    <select class="form-control" id="statType" name="statType"
                                        onchange="applyFilter()">
                                        <option value="mortalite">Graphe de taux de mortalité</option>
                                        @if ($bande->type_elevage == 'Poulet de chair')
                                            <option value="croissance">Courbe de croissance</option>
                                        @endif

                                        <option value="consommation">Courbe de consommation</option>
                                        @if ($bande->type_elevage == 'Poules pondeuses')
                                            <option value="pointe">Graphe de taux de pointe</option>
                                        @endif

                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" id="frequence" name="frequence">
                                        <option value="journalier">Journalière</option>
                                        <option value="hebdomadaire">Hebdomadaire</option>

                                    </select>
                                </div>
                                <div class="col-md-2" id="startD">
                                    <input type="date" class="form-control" id="startDate" name="startDate"
                                        value="{{ $bande->date_demarrage }}">
                                </div>
                                <div class="col-md-2" id="endD">
                                    <input type="date" class="form-control" id="endDate" name="endDate"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-2" id="startS" style="display: none;">
                                    <select class="form-control" id="startSemaine" name="frequence">
                                        @foreach ($pesages as $pesage)
                                            <option value="{{ $pesage->semaine_p }}">Semaine de debut {{$pesage->semaine_p}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2" id="endS" style="display: none;">
                                    <select class="form-control" id="endSemaine" name="frequence">
                                        @foreach ($pesages as $pesage)
                                            <option value="{{ $pesage->semaine_p }}">Semaine {{$pesage->semaine_p}}</option>
                                        @endforeach
                                    </select>
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
                    <div class="col-md-4">
                        <div class="card bg-light mb-3">
                            <div class="card-header" id="mainChartHeader">Taux de mortalité de la bande <p
                                    id="Nombre"></p>
                            </div>
                            <div class="card-body text-center">
                                <h2 class="card-title" style="font-size: 3em; color: #FF6F61;" id="mainChartValue">
                                </h2>

                            </div>
                        </div>
                        <div class="card bg-light mb-3">
                            <div class="card-header" id="secondaryChartHeader">Répartition du nombre de mortalité par
                                bâtiment</div>
                            <div class="card-body">
                                <canvas id="donutChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light mb-3">
                            <div class="card-header" id="lineChartHeader">Évolution du taux de mortalité de la bande par
                                bâtiment</div>
                            <div class="card-body">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                        <div class="card bg-light mb-3">
                            <div class="card-header" id="areaChartHeader">Évolution du taux de mortalité de la bande
                            </div>
                            <div class="card-body">
                                <canvas id="areaChart"></canvas>
                            </div>
                        </div>
                    </div>
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
    <script src="{{ asset('js/backend/statBande.js')}}"></script>
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
                let typeStat= document.getElementById('statType').value;
                var type="";
                if(typeStat=="mortalite"){
                    type="Graphe de taux de mortalité";
                }else if(typeStat=="consommation"){
                    type="Courbe de consommation";
                }else if(typeStat=="pointe"){
                    type="Graphe de taux de pointe";
                }else {
                    type="Courbe de consommation";
                }
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
                    pdf.save('rapport-fermes.pdf');

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

</body>
