// Appeler cette fonction lors du chargement de la page
$(document).ready(function() {
    initializeCharts();  // Assure l'initialisation des graphiques
    applyFilter();
});

function initializeCharts() {

    var type=document.getElementById('type').value;
    //if(type=="lait"){

            // Graphique de production de lait
    var ctxLine = document.getElementById('lineChart').getContext('2d');
    window.lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Production de lait (L)',
                data: [],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique de consommation
   /* var ctxLine1 = document.getElementById('lineChart1').getContext('2d');
    window.lineChart1 = new Chart(ctxLine1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Consommation (kg)',
                data: [],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique de production de lait par rapport à la consommation
    var ctxLine2 = document.getElementById('lineChart2').getContext('2d');
    window.lineChart2 = new Chart(ctxLine2, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Production de lait (L)',
                data: [],
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 1
            },
            {
                label: 'Consommation (kg)',
                data: [],
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

   /* }else{
            // Graphique de consommation
    var ctxLine1 = document.getElementById('lineChart1').getContext('2d');
    window.lineChart1 = new Chart(ctxLine1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Consommation (kg)',
                data: [],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


    }*/

}

function applyFilter() {
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;
    let vache_id = document.getElementById('vache_id').value;
    let statType = document.getElementById('type').value;

    // Requête AJAX pour récupérer les statistiques
    $.ajax({
        url: '/getVacheStatistics',
        method: 'GET',
        data: {
            vache_id: vache_id,
            date_1: startDate,
            date_2: endDate
        },
        success: function (response) {

                updateCharts(response, statType);

        },
        error: function (xhr, status, error) {
            console.error('Erreur AJAX :', error);
        }
    });
}

function updateCharts(data, statType) {
    // Vérifiez que les données existent avant d'essayer de les appliquer aux graphiques
    if (!data || !data.dates) {
        console.error('Les données sont manquantes ou incorrectes.');
        return;
    }

   // if (statType === "lait" && window.lineChart && window.lineChart2) {
        // Mise à jour du graphique de production de lait
        window.lineChart.data.labels = data.dates;
        window.lineChart.data.datasets[0].data = data.production || [];
        window.lineChart.update();

        // Mise à jour du graphique de consommation
       /* window.lineChart1.data.labels = data.dates;
        window.lineChart1.data.datasets[0].data = data.consommation || [];
        window.lineChart1.update();

        // Mise à jour du graphique de production de lait par rapport à la consommation
        window.lineChart2.data.labels = data.dates;
        window.lineChart2.data.datasets[0].data = data.production || [];
        window.lineChart2.data.datasets[1].data = data.consommation || [];
        window.lineChart2.update();*/
    //} else if (statType !== "lait" && window.lineChart1) {
        // Mise à jour du graphique de consommation uniquement si le type n'est pas "lait"
        /*window.lineChart1.data.labels = data.dates;
        window.lineChart1.data.datasets[0].data = data.consommation || [];
        window.lineChart1.update();*/
   /* } else {
        console.error('Type de statistique non supporté ou graphique non initialisé.');
    }*/
}

