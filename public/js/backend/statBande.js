
document.addEventListener('DOMContentLoaded', function() {
    applyFilter();
    initializeCharts();
});

function initializeCharts() {
    // Initialisation des graphiques
    var ctxDonut = document.getElementById('donutChart').getContext('2d');
    window.donutChart = new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],

            }]
        },
        options: {
            /* Options */
        }
    });

    var ctxLine = document.getElementById('lineChart').getContext('2d');
    window.lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            /* Options */
        }
    });

    var ctxArea = document.getElementById('areaChart').getContext('2d');
    window.areaChart = new Chart(ctxArea, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{

                data: [],
                backgroundColor: ['#ff9f43'],
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            /* Options */
        }
    });
}

function applyFilter() {
    let statType = document.getElementById('statType').value;
    let frequence = document.getElementById('frequence');
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;
    let bande_id = document.getElementById('bande_id').value;
    const startD = document.getElementById('startD');
    const endD = document.getElementById('endD');
    const startS = document.getElementById('startS');
    const endS = document.getElementById('endS');
    const startSemaine = document.getElementById('startSemaine');
    const endSemaine = document.getElementById('endSemaine');
    let titre1 = "";
    let tire2 = "";

    if (statType === 'croissance') {
        // Met à jour la valeur du sélecteur de fréquence
        frequence.value = 'hebdomadaire';

        // Cacher les champs de dates et afficher les sélecteurs de semaines
        startD.style.display = 'none';
        endD.style.display = 'none';
        startS.style.display = 'block';
        endS.style.display = 'block';

                    // Définir les valeurs par défaut pour les semaines
    if (startSemaine.options.length > 0) {
        startSemaine.value = startSemaine.options[0].value; // Semaine 1 par défaut
    }
    if (endSemaine.options.length > 0) {
        endSemaine.value = endSemaine.options[endSemaine.options.length - 1].value; // Dernière semaine par défaut
    }

    // Mettre à jour les titres
    startSemaine.options[0].text = "Semaine debut pesage";
    endSemaine.options[endSemaine.options.length - 1].text = "Semaine fin pesage";
            // Fetch croissance statistics
    $.ajax({
        url: '/getCroissanceStatistics',
        method: 'GET',
        data: {
            bande_id: bande_id,
            startSemaine: startSemaine.value,
            endSemaine: endSemaine.value
        },
        success: function(response) {
            titre1 = "Semaine de pesage";
            tire2 = "Taux de croissance (%)";
            updateCharts(response, statType, titre1, tire2);
            updateTitles(statType);
        }
    });

    } else {
        // Réinitialise la fréquence à journalière si ce n'est pas "croissance"
        if (frequence.value === 'hebdomadaire') {
            frequence.value = 'journalier';
        }

        // Afficher les champs de dates et cacher les sélecteurs de semaines
        startD.style.display = 'block';
        endD.style.display = 'block';
        startS.style.display = 'none';
        endS.style.display = 'none';

        // Gérer les autres types de statistiques
        if (statType === 'mortalite') {
            $.ajax({
                url: '/getMortaliteStatistics',
                method: 'GET',
                data: {
                    bande_id: bande_id,
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    titre1 = "Date journalisation";
                    tire2 = "Taux de mortalité(%)";
                    updateCharts(response, statType, titre1, tire2);
                    updateTitles(statType);
                }
            });
        } else if (statType === 'pointe') {
            $.ajax({
                url: '/getPointeStatistics',
                method: 'GET',
                data: {
                    bande_id: bande_id,
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    titre1 = "Date Ramassage";
                    tire2 = "Taux de pointe(%)";
                    updateCharts(response, statType, titre1, tire2);
                    updateTitles(statType);
                }
            });
        } else if (statType === 'consommation') {
            $.ajax({
                url: '/getConsomationStatistics',
                method: 'GET',
                data: {
                    bande_id: bande_id,
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    titre1 = "Date Alimentation";
                    tire2 = "Quantité Consommé";
                    updateCharts(response, statType, titre1, tire2);
                    updateTitles(statType);
                }
            });
        }
    }
}



function updateCharts(data, statType, titre1, titre2) {
    let rate = data.Rate;
    let pourcent = statType === 'consommation' ? 'kg' : '%';

    document.getElementById('mainChartValue').innerText = rate.toFixed(2) + pourcent;

    // Mise à jour des graphiques
    donutChart.data.labels = Object.keys(data.DonutChartData);
    donutChart.data.datasets[0].data = Object.values(data.DonutChartData);

    // Génération de couleurs dynamiques pour le donutChart
    donutChart.data.datasets[0].backgroundColor = generateColors(Object.keys(data.DonutChartData).length);

    donutChart.update();

    let labels = [];
    let datasets = [];
    for (const poulailler in data.LineChartData) {
        let poulaillerData = data.LineChartData[poulailler];
        let dataPoints = [];
        for (const date in poulaillerData) {
            if (!labels.includes(date)) labels.push(date);
            dataPoints.push(poulaillerData[date]);
        }
        datasets.push({
            label: poulailler,
            data: dataPoints,
            borderColor: getRandomColor(),
            backgroundColor: 'rgba(0, 0, 0, 0.2)',
            fill: true,
            tension: 0.4
        });
    }

    lineChart.data.labels = labels;
    lineChart.data.datasets = datasets;
    lineChart.options = {
        ...lineChart.options,
        plugins: {
            legend: {
                display: true
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += context.parsed.y.toFixed(2) + pourcent;
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: titre1
                }
            },
            y: {
                title: {
                    display: true,
                    text: titre2
                }
            }
        }
    };
    lineChart.update();

    areaChart.data.labels = Object.keys(data.AreaChartData);
    areaChart.data.datasets[0].label = titre2;
    areaChart.data.datasets[0].data = Object.values(data.AreaChartData);
    areaChart.options = {
        ...areaChart.options,
        plugins: {
            legend: {
                display: true
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += context.parsed.y.toFixed(2) + pourcent;
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: titre1
                }
            },
            y: {
                title: {
                    display: true,
                    text: titre2
                }
            }
        }
    };
    areaChart.update();
}

// Fonction pour générer des couleurs dynamiques
function generateColors(count) {
    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(getRandomColor());
    }
    return colors;
}

// Fonction pour générer une couleur aléatoire
function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}



function updateTitles(statType) {
    let mainChartHeader, secondaryChartHeader, lineChartHeader, areaChartHeader;

    switch (statType) {
        case 'mortalite':
            mainChartHeader = 'Taux de mortalité de la bande';
            secondaryChartHeader = 'Répartition du nombre de mortalité par bâtiment';
            lineChartHeader = 'Évolution du taux de mortalité de la bande par bâtiment';
            areaChartHeader = 'Évolution du taux de mortalité de la bande';
            break;
        case 'croissance':
            mainChartHeader = 'Taux de croissance de la bande';
            secondaryChartHeader = 'Répartition du nombre de croissance par bâtiment';
            lineChartHeader = 'Évolution du taux de croissance de la bande par bâtiment';
            areaChartHeader = 'Évolution du taux de croissance de la bande';
            break;
        case 'consommation':
            mainChartHeader = 'Quantité de consommation de la bande';
            secondaryChartHeader = 'Répartition de la consommation par bâtiment';
            lineChartHeader = 'Évolution de consommation de la bande par bâtiment';
            areaChartHeader = 'Évolution de consommation de la bande';
            break;
        case 'pointe':
            mainChartHeader = 'Taux de pointe de la bande';
            secondaryChartHeader = 'Répartition du nombre de pointe par bâtiment';
            lineChartHeader = 'Évolution du taux de pointe de la bande par bâtiment';
            areaChartHeader = 'Évolution du taux de pointe de la bande';
            break;
    }

    document.getElementById('mainChartHeader').innerText = mainChartHeader;
    document.getElementById('secondaryChartHeader').innerText = secondaryChartHeader;
    document.getElementById('lineChartHeader').innerText = lineChartHeader;
    document.getElementById('areaChartHeader').innerText = areaChartHeader;

}
