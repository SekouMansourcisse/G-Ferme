$(document).ready(function() {



    // Détection du clic sur l'icône d'édition
    $('.edit-item-btn').click(function() {
        // Récupération de l'ID du transfert
        var transfertId = $(this).closest('tr').data('id');

        // Envoi de la requête AJAX pour obtenir les détails du transfert
        $.ajax({
            type: 'GET',
            url: '/getTransfertDetails/' + transfertId,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Pré-remplissage des données dans le formulaire de la modal
                    console.log(response.transfert);
                    $('#edit-id').val(response.transfert.id);
                    $('#edit-compte-source').val(response.transfert.compte_source.Denomination);
                    $('#edit-compte-destination').val(response.transfert.compte_destination.Denomination);
                    $('#montant').val(response.transfert.montant);

                    // Affichage de la modal
                    $('#editTransfertModal').modal('show');
                } else {
                    // Affichage d'un message d'erreur si la requête échoue
                    alert('Erreur : Impossible de récupérer les détails du transfert.');
                }
            },
            error: function() {
                // Affichage d'un message d'erreur en cas d'échec de la requête AJAX
                alert('Erreur : Impossible de récupérer les détails du transfert.');
            }
        });
    });


    // Soumission du formulaire d'édition
    $('#edit-compte-form').submit(function(e) {
        e.preventDefault();
        var compteId = $("#edit-compte-form input[name=edit-id]").val();

        // Récupération des données du formulaire
        var formData = $(this).serialize();

        // Vous pouvez envoyer les données du formulaire pour l'édition du compte ici en utilisant AJAX
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/compte/' + compteId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Gérer la réponse ici
                // Par exemple, fermer la modal, afficher un message de succès, etc.
                $('#editCompteModal').modal('hide');
                // Afficher un message de succès (optionnel)
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Le compte a été modifié avec succès.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                }).then(() => {
                    location.reload();
                });
            },
            error: function(error) {
                // Gérer les erreurs ici
                // Afficher un message d'erreur (optionnel)
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de la modification du compte.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            }
        });
    });

    // Soumission du formulaire de transfert
    $('#add-transfert-form').submit(function(e) {
        e.preventDefault();
        var source = $("#add-transfert-form select[name=compte_source]").val();
        var desti = $("#add-transfert-form select[name=compte_destination]").val();

        if (source != desti) {
            // Créer un nouvel objet FormData
            var formData = new FormData(this); // 'this' fait référence au formulaire

            // Envoi des données du formulaire pour effectuer le transfert
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '/addtransfert',
                data: formData,
                contentType: false, // Nécessaire pour l'envoi de fichiers
                processData: false, // Nécessaire pour l'envoi de fichiers
                dataType: 'json',
                success: function(response) {
                    $('#addTransfertModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'Le transfert a été effectué avec succès.',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(error) {
                    var errorMessage = 'Une erreur est survenue lors du transfert.';

                    // Vérifie si le message d'erreur personnalisé est renvoyé
                    if (error.responseJSON && error.responseJSON.message) {
                        errorMessage = error.responseJSON.message; // Récupère le message "Solde insuffisant pour effectuer ce transfert"
                    } else if (error.responseJSON && error.responseJSON.errors) {
                        errorMessage = Object.values(error.responseJSON.errors).flat().join('\n');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: errorMessage,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Les deux comptes doivent être différents.',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        }
    });



    // Détection du clic sur l'icône de suppression
    $('.delete-item-btn').click(function(e) {
        e.preventDefault();
        var deleteBtn = $(this);

        // Utiliser SweetAlert pour confirmer la suppression
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-cancel',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Récupérer l'ID du compte à supprimer
                var compteId = deleteBtn.closest('tr').data('id');

                // Envoyer une requête AJAX pour supprimer le compte
                $.ajax({
                    type: "DELETE",
                    url: '/deletetransfert/' + compteId,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Si la suppression est réussie, vous pouvez afficher un message de succès
                        Swal.fire(
                            'Supprimé!',
                            'Le Transfert a été supprimé avec succès.',
                            'success'
                        );
                        // Actualiser la page ou mettre à jour la liste des comptes après la suppression
                        location.reload();
                    },
                    error: function(error) {
                        // En cas d'erreur, afficher un message d'erreur à l'utilisateur
                        Swal.fire(
                            'Erreur!',
                            'Une erreur est survenue lors de la suppression du transfert.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
