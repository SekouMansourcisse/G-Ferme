$(document).ready(function() {
    // Édition d'une assurance
    $('.edit-assurance-btn').click(function() {
        var assuranceId = $(this).closest('tr').data('id');

        $.ajax({
            url: '/assurances/' + assuranceId + '/edit',
            type: 'GET',
            success: function(response) {
                $('#editAssuranceModal #assuranceId').val(response.id);
                $('#editAssuranceModal #assureur').val(response.assureur);
                $('#editAssuranceModal #date_debut').val(response.date_debut);
                $('#editAssuranceModal #date_fin').val(response.date_fin);
                $('#editAssuranceModal #montant').val(response.montant);
                $('#editAssuranceModal').modal('show');
            },
            error: function() {
                Swal.fire('Erreur!', 'Impossible de récupérer les données.', 'error');
            }
        });
    });

    // Mise à jour de l'assurance
    $('#updateAssuranceForm').submit(function(e) {
        e.preventDefault();

        var assuranceId = $('#editAssuranceModal #assuranceId').val();
        $.ajax({
            url: '/assurances/' + assuranceId,
            type: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                assureur: $('#editAssuranceModal #assureur').val(),
                date_debut: $('#editAssuranceModal #date_debut').val(),
                date_fin: $('#editAssuranceModal #date_fin').val(),
                montant: $('#editAssuranceModal #montant').val(),
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Succès', response.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                }
            },
            error: function() {
                Swal.fire('Erreur!', 'La mise à jour a échoué.', 'error');
            }
        });
    });

    // Suppression d'une assurance
    $('.delete-assurance-btn').click(function() {
        var assuranceId = $(this).closest('tr').data('id');

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/assurances/' + assuranceId,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Supprimé!', response.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Erreur!', 'Une erreur est survenue.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Erreur!', 'Impossible de supprimer.', 'error');
                    }
                });
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    let rowsPerPage = 5; // Nombre de lignes par page
    let tableBody = document.getElementById('assuranceTables');
    let rows = tableBody.getElementsByTagName('tr');
    let paginationControls = document.getElementById('paginationControls');
    let totalRows = rows.length;
    let totalPages = Math.ceil(totalRows / rowsPerPage);
    let currentPage = 1;

    // Fonction pour afficher une page
    function displayPage(page) {
        let start = (page - 1) * rowsPerPage;
        let end = start + rowsPerPage;

        // Masquer toutes les lignes
        for (let i = 0; i < totalRows; i++) {
            rows[i].style.display = 'none';
        }

        // Afficher les lignes pour la page en cours
        for (let i = start; i < end && i < totalRows; i++) {
            rows[i].style.display = '';
        }

        // Mettre à jour les boutons de pagination
        updatePaginationControls(page);
    }

    // Fonction pour créer les boutons de pagination
    function updatePaginationControls(page) {
        paginationControls.innerHTML = '';

        // Ajouter le bouton "Précédent"
        if (page > 1) {
            let prevButton = document.createElement('button');
            prevButton.innerText = 'Précédent';
            prevButton.addEventListener('click', function() {
                displayPage(page - 1);
            });
            paginationControls.appendChild(prevButton);
        }

        // Ajouter les boutons pour chaque page
        for (let i = 1; i <= totalPages; i++) {
            let pageButton = document.createElement('button');
            pageButton.innerText = i;
            if (i === page) {
                pageButton.classList.add('active');
            }
            pageButton.addEventListener('click', function() {
                displayPage(i);
            });
            paginationControls.appendChild(pageButton);
        }

        // Ajouter le bouton "Suivant"
        if (page < totalPages) {
            let nextButton = document.createElement('button');
            nextButton.innerText = 'Suivant';
            nextButton.addEventListener('click', function() {
                displayPage(page + 1);
            });
            paginationControls.appendChild(nextButton);
        }
    }

    // Afficher la première page au chargement
    displayPage(currentPage);
});
