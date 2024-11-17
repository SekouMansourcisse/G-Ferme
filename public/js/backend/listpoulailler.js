$(document).ready(function() {
    // Détection du clic sur l'icône d'édition
    $('.edit-item-btn').click(function() {
        // Récupération des données du poulailler à partir de la ligne correspondante dans le tableau
        var row = $(this).closest('tr');
        var denomination = row.find('td:eq(0)').text(); // Colonne 'Denomination'
        var contenanceNormale = row.find('td:eq(1)').text(); // Colonne 'Contenance Normale'
        var dimension = row.find('td:eq(2)').text(); // Colonne 'Dimension'
        var infosSupplementaires = row.find('td:eq(3)').text(); // Colonne 'Informations Supplémentaires'
        var Id = $(this).closest('tr').data('id');

        // Pré-remplissage des données dans le formulaire de la modal
        $('#edit-id').val(Id);
        $('#edit-denomination').val(denomination);
        $('#edit-contenance-normale').val(contenanceNormale);
        $('#edit-dimension').val(dimension);
        $('#edit-infos-supplementaires').val(infosSupplementaires);

        // Affichage de la modal
        $('#editPoulaillerModal').modal('show');
    });

    // Soumission du formulaire d'édition
    $('#edit-poulailler-form').submit(function(e) {
        e.preventDefault();
        var poulaillerId = $("#edit-poulailler-form input[name=edit-id]").val();

        // Récupération des données du formulaire
        var formData = $(this).serialize();

        // Vous pouvez envoyer les données du formulaire pour l'édition du poulailler ici en utilisant AJAX
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/poulailler/' + poulaillerId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Gérer la réponse ici
                // Par exemple, fermer la modal, afficher un message de succès, etc.
                $('#editPoulaillerModal').modal('hide');
                // Afficher un message de succès (optionnel)
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Le poulailler a été modifié avec succès.'
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
                    text: 'Une erreur est survenue lors de la modification du poulailler.'
                });
            }
        });
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
                // Récupérer l'ID du poulailler à supprimer
                var poulaillerId = deleteBtn.closest('tr').data('id');

                // Envoyer une requête AJAX pour supprimer le poulailler
                $.ajax({
                    type: "DELETE",
                    url: '/deletepoulailler/' + poulaillerId,
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'json',
                    success: function(response) {
                        // Si la suppression est réussie, vous pouvez afficher un message de succès
                        Swal.fire(
                            'Supprimé!',
                            'Le poulailler a été supprimé avec succès.',
                            'success'
                        );
                        // Actualiser la page ou mettre à jour la liste des poulaillers après la suppression
                        location.reload();
                    },
                    error: function(error) {
                        // En cas d'erreur, afficher un message d'erreur à l'utilisateur
                        Swal.fire(
                            'Erreur!',
                            'Une erreur est survenue lors de la suppression du poulailler.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
