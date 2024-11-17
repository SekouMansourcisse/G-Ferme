@include('partials._head')
<style>
    @media print {
    .no-print {
        display: none !important;
    }
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
                        <h4 id="titre">Recette et Depense :<span class="text-success" id="report"> </span> </h4>
                        <h6></h6>
                    </div>
                </div>
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
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end"> <button id="exportPdf" class="btn btn-primary no-print">Exporter en PDF</button></div>
                </div>
                <div class="row">

                    <div class="col-md-6">

                        <div class="card bg-light mb-3">
                            <div class="card-header" id="secondaryChartHeader">Resumé des sorties
                            </div>
                            <div class="card-body">
                                <div id="donut-chart" class="chart-set"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-header" id="lineChartHeader">Resume des entrées</div>
                            <div id="legend-container"></div>
                            <div class="card-body">

                                <div id="radial-chart1" class="chart-set"></div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card bg-light mb-3">
                            <div class="card-header" id="lineChartHeader">Recette et Depenses</div>
                            <div class="card-body">
                                <div id="s-col" class="chart-set"></div>
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
                document.getElementById('titre').innerText = `Rapport de Recette et Depense du ${startFormatted} au ${endFormatted}`;

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
                    pdf.save('recette-depense.pdf');

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



    <script>
        let chart;

        function initializeChart(recettes, depenses) {
            var sCol = {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: 'Recettes',
                    data: [recettes]
                }, {
                    name: 'Dépenses',
                    data: [depenses]
                }],
                xaxis: {
                    categories: ['Recettes', 'Dépenses'],
                },
                yaxis: {
                    title: {
                        text: 'FCFA'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " FCFA";
                        }
                    }
                }
            };

            chart = new ApexCharts(document.querySelector("#s-col"), sCol);
            chart.render();
        }

        function applyFilter() {
            let startDate = document.getElementById('startDate').value;
            let endDate = document.getElementById('endDate').value;

            $.ajax({
                url: '/getRecetteDepenseStatistics',
                method: 'GET',
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    //console.log("Data received: ", response);

                    // Filtrer les données pour le radial chart (ventes)
                    const radialData = response.filter(item => item.typevente || item.type === 'remboursement');

                    // Filtrer les données pour le donut chart (ravitaillement, redevance, pertes, dépenses)
                    const donutData = response.filter(item =>
                        item.typeS === 'Total Ravitaillement' ||
                        item.typeS === 'Total Redevance Fournisseur' ||
                        item.typeS === 'Montant Total des Pertes' ||
                        item.typeS === 'Montant Total des Dépenses'
                    );

                    // Calculer les recettes et les dépenses pour le graphique en barres
                    const recettes = radialData.reduce((total, item) => total + (item.total || 0), 0);
                    const depenses = donutData.reduce((total, item) => total + (item.total || 0), 0);

                    // Initialiser ou mettre à jour les graphiques
                    initializeRadialChart(radialData); // Initialiser le graphique radial
                    initializeDonutChart(donutData); // Initialiser le graphique donut

                    if (!chart) {
                        initializeChart(recettes, depenses); // Initialiser le graphique en barres
                    } else {
                        updateChart(recettes, depenses); // Mettre à jour le graphique en barres
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors de la récupération des statistiques: ", error);
                }
            });
        }

        function initializeRadialChart(chartData) {
            const labels = chartData.map(item => item.typevente || item.type || 'N/A');
            const series = chartData.map(item => item.total || 0);

            var radialChart = {
                chart: {
                    height: 350,
                    type: 'radialBar',
                    toolbar: {
                        show: false,
                    }
                },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            show: true,
                            name: {
                                fontSize: '22px',
                                show: true
                            },
                            value: {
                                fontSize: '16px',
                                show: true,
                                formatter: function(value) {
                                    return Number(value).toFixed(2) + ' FCFA';
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Entrée',
                                formatter: function() {
                                    return series.reduce((a, b) => a + b, 0).toFixed(2) + ' FCFA';
                                }
                            }
                        }
                    }
                },
                series: series,
                labels: labels,
                legend: {
                    show: true,
                    position: 'bottom'
                }
            };

            var chartElement = document.querySelector("#radial-chart1");
            if (chartElement) {
                var chart = new ApexCharts(chartElement, radialChart);
                chart.render();
            } else {
                console.error("Element with id 'radial-chart1' not found.");
            }
        }


        function initializeDonutChart(chartData) {
            const labels = chartData.map(item => item.typeS || 'N/A');
            const series = chartData.map(item => item.total || 0);

            var donutChart = {
                chart: {
                    height: 350,
                    type: 'donut',
                    toolbar: {
                        show: false,
                    }
                },
                series: series,
                labels: labels,
                dataLabels: {
                    enabled: false // Désactive les dataLabels pour ne rien afficher
                },
                legend: {
                    show: true,
                    position: 'bottom'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chartElement = document.querySelector("#donut-chart");
            if (chartElement) {
                var chart = new ApexCharts(chartElement, donutChart);
                chart.render();
            } else {
                console.error("Element with id 'donut-chart' not found.");
            }
        }

        function updateChart(recettes, depenses) {
            chart.updateSeries([{
                name: 'Recettes',
                data: [recettes]
            }, {
                name: 'Dépenses',
                data: [depenses]
            }]);
        }


        document.addEventListener('DOMContentLoaded', function() {
            applyFilter();
        });
    </script>
</body>
