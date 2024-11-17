$(document).ready(function() {
        //affichage du formulaire d'approvisionnement
        $('.supply-item-btn').click(function() {
            // Récupération des données de l'utilisateur à partir de la ligne correspondante dans le tableau
            var row = $(this).closest('tr');
            var nom = row.find('td:eq(0)').text(); // Colonne 'Nom'
            var solde = row.find('td:eq(1)').text(); // Colonne 'solde'

            var Id = $(this).closest('tr').data('id');

            // Pré-remplissage des données dans le formulaire de la modal
            $('#compte_id').val(Id);
            $('#denomination').val(nom);
            $('#solde-actuel').val(solde);

            // Affichage de la modal
            $('#approCompteModal').modal('show');
        });
    // Détection du clic sur l'icône d'édition
    $('.edit-item-btn').click(function() {
        // Récupération des données du compte à partir de la ligne correspondante dans le tableau
        var row = $(this).closest('tr');
        var denomination = row.find('td:eq(0)').text(); // Colonne 'Denomination'
        var solde_actuel = row.find('td:eq(1)').text(); // Colonne 'Solde Actuel'
        var infosSupplementaires = row.find('td:eq(2)').text(); // Colonne 'Infos supplémentaires'
        var Id = row.data('id');

        // Pré-remplissage des données dans le formulaire de la modal
        $('#edit-id').val(Id);
        $('#edit-denomination').val(denomination);
        $('#edit-solde-actuel').val(solde_actuel);
        $('#edit-infos-supplementaires').val(infosSupplementaires);

        // Affichage de la modal
        $('#editCompteModal').modal('show');
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
                    url: '/deletecompte/' + compteId,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Si la suppression est réussie, vous pouvez afficher un message de succès
                        Swal.fire(
                            'Supprimé!',
                            'Le compte a été supprimé avec succès.',
                            'success'
                        );
                        // Actualiser la page ou mettre à jour la liste des comptes après la suppression
                        location.reload();
                    },
                    error: function(error) {
                        // En cas d'erreur, afficher un message d'erreur à l'utilisateur
                        Swal.fire(
                            'Erreur!',
                            'Une erreur est survenue lors de la suppression du compte.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
