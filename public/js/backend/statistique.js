
document.addEventListener('DOMContentLoaded', function() {
    applyFilter();


});
/*var bandDurationChart = new ApexCharts(document.querySelector("#bandDurationChart"), {
    chart: {
        type: 'bar',
        height: 350
    },
    series: [{
        name: 'Âge',
        data: [] // Data to be populated dynamically
    }],
    xaxis: {
        categories: [] // Band names will be added dynamically
    },
    yaxis: {
        title: {
            text: 'Âge (jours)'
        }
    },
    colors: [] // Tableau de couleurs à définir dynamiquement
});

bandDurationChart.render();

// Second chart: Mortality, Growth Rate, and Peak Rate
var performanceChart = new ApexCharts(document.querySelector("#performanceChart"), {
    chart: {
        type: 'line',
        height: 350,
        curve: 'smooth' // Convertir en courbes
    },
    series: [{
        name: 'Taux de mortalité',
        data: [] // Mortality data arrondi
    }, {
        name: 'Taux de croissance',
        data: [] // Growth rate data arrondi
    }, {
        name: 'Taux pointe',
        data: [] // Peak rate data arrondi
    }],
    xaxis: {
        categories: [] // Band names will be added dynamically
    },
    yaxis: {
        title: {
            text: 'Taux (%)'
        }
    },
    colors: ['#FF4560', '#00E396', '#FEB019']
});*/


performanceChart.render();
var options = {
    series: [{
        name: "Prix de vente",
        data: [ /* données des ventes ici */ ]
    }],
    chart: {
        type: 'line',
        height: 350
    },
    xaxis: {
        title: {
            text: 'Date de vente'
        },
        categories: [ /* dates des ventes ici */ ]
    },
    yaxis: {
        title: {
            text: 'Prix de vente (XOF)'
        }
    }
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
// initial ramassage
/*var ramassageOptions = {
    series: [{
        name: "Total Œufs Collectés",
        data: [] // Les données seront mises à jour via AJAX
    }],
    chart: {
        type: 'line',
        height: 350
    },
    xaxis: {
        categories: [], // Les dates seront mises à jour via AJAX
        title: {
            text: 'Date de collecte'
        }
    },
    yaxis: {
        title: {
            text: 'Total Collecté (unités)'
        }
    },
    colors: ['#008FFB']
};

var ramassageChart = new ApexCharts(document.querySelector("#RamassageChart"), ramassageOptions);
ramassageChart.render();
// initial pesage
var pesageOptions = {
    series: [{
        name: "Poids Moyen",
        data: [] // Les données seront mises à jour via AJAX
    }],
    chart: {
        type: 'line',
        height: 350
    },
    xaxis: {
        categories: [], // Les semaines seront mises à jour via AJAX
        title: {
            text: 'Semaine de pesage'
        }
    },
    yaxis: {
        title: {
            text: 'Poids Moyen (g)'
        }
    },
    colors: ['#00E396']
};

var pesageChart = new ApexCharts(document.querySelector("#PesageChart"), pesageOptions);
pesageChart.render();*/

// intial depenses
var depenseOptions = {
    series: [{
        name: "Montant Dépensé",
        data: [] // Les données seront mises à jour via AJAX
    }],
    chart: {
        type: 'line',
        height: 350
    },
    xaxis: {
        categories: [], // Les dates seront mises à jour via AJAX
        title: {
            text: 'Date'
        }
    },
    yaxis: {
        title: {
            text: 'Montant Dépensé (XOF)'
        }
    },
    colors: ['#FEB019']
};

var depenseChart = new ApexCharts(document.querySelector("#DepenseChart"), depenseOptions);
depenseChart.render();


function applyFilter() {
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
    /*document.getElementById('poulet').innerText = `poulet de chair : Evolution du poids des poulets ${startFormatted} - ${endFormatted}`;
    document.getElementById('depense').innerText = `Graphe des dépenses ${startFormatted} - ${endFormatted}`;
    document.getElementById('poules').innerText=`Poules pondeuse : Evolution des collectes ${startFormatted} - ${endFormatted}`;*/
    document.getElementById('st').innerText = `Situation activité (${startFormatted} - ${endFormatted})`;

    $.ajax({
        url: `/getFermeStatRepport`,
        method: 'GET',
        data: {
            startDate: startDate,
            endDate: endDate
        },
        success: function(data) {

            // Mettre à jour les valeurs dans les cartes
            document.querySelectorAll('#mainChartValue')[0].innerText = data.totalDetteFournisseurs +
                " XOF";
            document.querySelectorAll('#mainChartValue')[1].innerText = data.totalDetteClients + " XOF";

            // Mettre à jour la situation activité
            let cards = document.querySelectorAll('.card-body p');
            let oeufV=document.getElementById('oeuf_vendu');
            let sujetV=document.getElementById('sujet_vendu');
            let autreV=document.getElementById('autre_vente');
            oeufV.innerText = data.recettes.oeufs_vendus + " XOF";
            sujetV.innerText = data.recettes.sujets_vendus + " XOF";
            autreV.innerText = data.recettes.autres_ventes + " XOF";


            // Données pour le graphique
            let dates = data.ventesOeuf.map(item => item.date);
            let ventesOeuf = data.ventesOeuf.map(item => item.total);
            let ventesSujets = data.ventesSujets.map(item => item.total);
            let ventesAutres = data.ventesAutres.map(item => item.total);

            // Mise à jour ou création du graphique
            if (window.chart) {
                window.chart.updateOptions({
                    series: [{
                        name: "Ventes Oeuf",
                        data: ventesOeuf
                    }, {
                        name: "Ventes Sujet",
                        data: ventesSujets
                    }, {
                        name: "Autres Ventes",
                        data: ventesAutres
                    }],
                    xaxis: {
                        categories: dates
                    }
                });
            } else {
                var options = {
                    series: [{
                        name: "Ventes Oeuf",
                        data: ventesOeuf
                    }, {
                        name: "Ventes Sujet",
                        data: ventesSujets
                    }, {
                        name: "Autres Ventes",
                        data: ventesAutres
                    }],
                    chart: {
                        type: 'line',
                        height: 350
                    },
                    xaxis: {
                        categories: dates,
                        title: {
                            text: 'Date de vente'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Montant (XOF)'
                        }
                    },
                    colors: ['#008FFB', '#00E396', '#FEB019']
                };

                window.chart = new ApexCharts(document.querySelector("#chart"), options);
                window.chart.render();
            }

            // Ajout : Ramassage d'œufs
           /* let ramassageDates = data.ramassages.map(item => item.date);
            let ramassageTotals = data.ramassages.map(item => item.total);

            if (window.ramassageChart) {
                window.ramassageChart.updateOptions({
                    series: [{
                        data: ramassageTotals
                    }],
                    xaxis: {
                        categories: ramassageDates
                    }
                });
            } else {
                var ramassageOptions = {
                    series: [{
                        name: "Total Œufs Collectés",
                        data: ramassageTotals
                    }],
                    chart: {
                        type: 'line',
                        height: 350
                    },
                    xaxis: {
                        categories: ramassageDates,
                        title: {
                            text: 'Date'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Total Collecté'
                        }
                    }
                };
                window.ramassageChart = new ApexCharts(document.querySelector("#RamassageChart"),
                    ramassageOptions);
                window.ramassageChart.render();
            }*/
            // Ajout : Dépenses
            let depenseDates = data.depenses.map(item => item.date);
            let depenseMontants = data.depenses.map(item => item.montant_total);

            if (window.depenseChart) {
                window.depenseChart.updateOptions({
                    series: [{
                        data: depenseMontants
                    }],
                    xaxis: {
                        categories: depenseDates
                    }
                });
            } else {
                var depenseOptions = {
                    series: [{
                        name: "Montant Dépensé",
                        data: depenseMontants
                    }],
                    chart: {
                        type: 'line',
                        height: 350
                    },
                    xaxis: {
                        categories: depenseDates,
                        title: {
                            text: 'Date'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Montant (XOF)'
                        }
                    }
                };
                window.depenseChart = new ApexCharts(document.querySelector("#DepenseChart"),
                    depenseOptions);
                window.depenseChart.render();
            }
            // Ajout : Pesage de poulets
            /*let pesageSemaine = data.pesages.map(item => parseFloat(item.semaine_p.toFixed(2)));
            let poidsMoyen = data.pesages.map(item => parseFloat(item.poid_moyen.toFixed(2)));

            if (window.pesageChart) {
                window.pesageChart.updateOptions({
                    series: [{
                        data: poidsMoyen
                    }],
                    xaxis: {
                        categories: pesageSemaine
                    }
                });
            } else {
                var pesageOptions = {
                    series: [{
                        name: "Poids Moyen",
                        data: poidsMoyen
                    }],
                    chart: {
                        type: 'line',
                        height: 350
                    },
                    xaxis: {
                        categories: pesageSemaine,
                        title: {
                            text: 'Semaine'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Poids Moyen (kg)'
                        }
                    }
                };
                window.pesageChart = new ApexCharts(document.querySelector("#PesageChart"),
                    pesageOptions);
                window.pesageChart.render();
            }*/


        },
        error: function(xhr, status, error) {
            console.error('Erreur:', error);
        }
    });
}

/*function bandeDuration() {
    let selectedFerme = $('#fermeSelect').val();

    $.ajax({
        url: `/getBandAge?fermeId=${selectedFerme}`,
        method: 'GET',
        success: function(response) {
            let bandNames = response.map(item => item.bandName);
            let bandAges = response.map(item => item.age);

            // Couleurs prédéfinies pour chaque bande
            let colors = ['#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2',
                '#7f7f7f', '#bcbd22', '#17becf'
            ];

            // Limiter les couleurs en fonction du nombre de bandes
            colors = colors.slice(0, bandNames.length);

            // Mettre à jour les données du graphique
            bandDurationChart.updateOptions({
                series: [{
                    name: 'Âge',
                    data: bandAges
                }],
                xaxis: {
                    categories: bandNames
                },
                colors: colors // Appliquer des couleurs différentes
            });
        },
        error: function(xhr, status, error) {
            console.error("Erreur lors de la récupération des données: ", error);
        }
    });
}

function PerformanceBande() {
    // Filter function for performance chart

    let selectedFerme = $('#performanceFermeSelect').val();

    if (selectedFerme) {
        $.ajax({
            url: `/getBandPerformance?fermeId=${selectedFerme}`,
            method: 'GET',
            success: function(response) {
                let bands = response.map(item => item.bandName);
                let mortalite = response.map(item => parseFloat(item.mortalite.toFixed(2)));
                let croissance = response.map(item => parseFloat(item.croissance.toFixed(2)));
                let tauxPointe = response.map(item => parseFloat(item.pointeRate.toFixed(2)));
                // Update chart data
                performanceChart.updateOptions({
                    series: [{
                        name: 'Taux de mortalité',
                        data: mortalite
                    }, {
                        name: 'Taux de croissance',
                        data: croissance
                    }, {
                        name: 'Taux pointe',
                        data: tauxPointe
                    }],
                    xaxis: {
                        categories: bands
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de la récupération des données: ", error);
            }
        });
    }

}*/
// Fonction pour générer un tableau de couleurs distinctes
function generateDistinctColors(numColors) {
    let palette = ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#A133FF',
        '#33FFA1'
    ]; // Tableau de couleurs prédéfini
    let colors = [];
    for (let i = 0; i < numColors; i++) {
        colors.push(palette[i % palette.length]); // Utilise les couleurs en boucle si nécessaire
    }
    return colors;
}

// Fonction pour générer un tableau de couleurs
function generateColors(numColors) {
    let colors = [];
    for (let i = 0; i < numColors; i++) {
        colors.push(generateRandomColor());
    }
    return colors;
}

// Générer une couleur aléatoire
function generateRandomColor() {
    let letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
