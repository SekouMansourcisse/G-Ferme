$(document).ready(function() {
    // Détection du clic sur l'icône d'édition
    $('.edit-item-btn').click(function() {
        // Récupération des données du client à partir de la ligne correspondante dans le tableau
        var row = $(this).closest('tr');
        var nom = row.find('td:eq(0)').text(); // Colonne 'Nom'
        var prenom = row.find('td:eq(1)').text(); // Colonne 'Prénom'
        var telephone = row.find('td:eq(2)').text(); // Colonne 'Téléphone'
        var numWhatsApp = row.find('td:eq(3)').text(); // Colonne 'N° WhatsApp'
        var detteInitiale = row.find('td:eq(4)').text(); // Colonne 'Redevance Initiale'
        var adresse = row.find('td:eq(5)').text(); // Colonne 'Adresse'
        var email = row.find('td:eq(6)').text();
        var infosSupplementaires = row.find('td:eq(7)').text(); // Colonne 'Infos supplémentaires'
        var Id = row.data('id');

        // Pré-remplissage des données dans le formulaire de la modal
        $('#edit-id').val(Id);
        $('#edit-nom').val(nom);
        $('#edit-prenom').val(prenom);
        $('#edit-telephone').val(telephone);
        $('#edit-num-whatsapp').val(numWhatsApp);
        $('#edit-dette-initiale').val(detteInitiale);
        $('#edit-adresse').val(adresse);
        $('#edit-email').val(email);
        $('#edit-infos-supplementaires').val(infosSupplementaires);

        // Affichage de la modal
        $('#editClientModal').modal('show');
    });


    // Soumission du formulaire d'édition
    $('#edit-client-form').submit(function(e) {
        e.preventDefault();
        var clientId = $("#edit-client-form input[name=edit-id]").val();

        // Récupération des données du formulaire
        var formData = $(this).serialize();

        // Vous pouvez envoyer les données du formulaire pour l'édition du client ici en utilisant AJAX
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/client/' + clientId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Gérer la réponse ici
                // Par exemple, fermer la modal, afficher un message de succès, etc.
                $('#editClientModal').modal('hide');
                // Afficher un message de succès (optionnel)
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Le client a été modifié avec succès.',

                    confirmButtonText: 'OK', // Texte du bouton "OK"
                    customClass: {
                        confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
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
                    text: 'Une erreur est survenue lors de la modification du client.',

                    confirmButtonText: 'OK', // Texte du bouton "OK"
                    customClass: {
                        confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
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
                // Récupérer l'ID du client à supprimer
                var clientId = deleteBtn.closest('tr').data('id');

                // Envoyer une requête AJAX pour supprimer le client
                $.ajax({
                    type: "DELETE",
                    url: '/deleteclient/' + clientId,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Si la suppression est réussie, vous pouvez afficher un message de succès
                        Swal.fire(
                            'Supprimé!',
                            'Le client a été supprimé avec succès.',
                            'success'
                        );
                        // Actualiser la page ou mettre à jour la liste des clients après la suppression
                        location.reload();
                    },
                    error: function(error) {
                        // En cas d'erreur, afficher un message d'erreur à l'utilisateur
                        Swal.fire(
                            'Erreur!',
                            'Une erreur est survenue lors de la suppression du client.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
