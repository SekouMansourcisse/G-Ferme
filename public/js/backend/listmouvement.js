document.addEventListener('DOMContentLoaded', function () {
    // Édition d'un mouvement
    document.querySelectorAll('.edit-item-btn').forEach(button => {
        button.addEventListener('click', function () {
            let mouvementId = this.closest('tr').getAttribute('data-id');

            // Récupérer les données du mouvement
            fetch(`/mouvements/${mouvementId}/edit`)
                .then(response => response.json())
                .then(data => {
                    // Remplir le formulaire avec les données existantes du mouvement
                    document.getElementById('mouvementId').value = data.id;
                    document.getElementById('equipement_id').value = data.Equipement_id;
                    document.getElementById('origine').value = data.Origine;
                    document.getElementById('destination').value = data.Destination;
                    document.getElementById('statut').value = data.Statut;
                    document.getElementById('date_mouvement').value = data.Date_mouvement;

                    // Afficher le modal pour l'édition
                    new bootstrap.Modal(document.getElementById('updateMouvementModal')).show();
                })
                .catch(error => console.error('Erreur:', error));
        });
    });

    // Mise à jour d'un mouvement
// Soumission du formulaire d'édition
$('#updateMouvementForm').on('submit', function (e) {
    e.preventDefault();

    // Récupération de l'ID du mouvement et des données du formulaire
    let mouvementId = $('#mouvementId').val();
    let formData = {
        Equipement_id: $('#equipement_id').val(),
        Origine: $('#origine').val(),
        Destination: $('#destination').val(),
        Statut: $('#statut').val(),
        Date_mouvement: $('#date_mouvement').val(),
        _token: $('meta[name="csrf-token"]').attr('content') // Assurez-vous que le token CSRF est bien inclus
    };

    $.ajax({
        url: `/mouvements/${mouvementId}`, // URL de mise à jour
        type: 'PUT',
        data: formData,
        success: function (response) {
            // Si succès, affiche un message de succès
            Swal.fire('Succès', response.message, 'success').then(() => {
                location.reload(); // Recharge la page pour refléter les modifications
            });
        },
        error: function (xhr) {
            // Si erreur, vérifie si c'est une erreur de validation (422)
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';

                // Construit un message d'erreur avec chaque message de validation
                $.each(errors, function (key, value) {
                    errorMessages += `${value[0]}<br>`;
                });

                // Affiche le message d'erreur
                Swal.fire('Erreur de validation', errorMessages, 'error');
            } else {
                // Pour toute autre erreur
                Swal.fire('Erreur', 'Une erreur est survenue lors de la mise à jour.', 'error');
            }
        }
    });
});


    // Suppression d'un mouvement
    document.querySelectorAll('.delete-item-btn').forEach(button => {
        button.addEventListener('click', function () {
            let mouvementId = this.closest('tr').getAttribute('data-id');

            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Cette action est irréversible!",
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-primary', // Classe CSS personnalisée pour le bouton "OK"
                    cancelButton: 'btn btn-cancel',
                },

                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/mouvements/${mouvementId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Supprimé!',
                                text: 'Le mouvement a été supprimé avec succès.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();  // Recharge la page pour voir les changements
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la suppression:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de la suppression du mouvement.',
                        });
                    });
                }
            });
        });
    });
});
