$(document).ready(function() {
    // Détection du clic sur l'icône d'édition
    $('.edit-item-btn').click(function() {
        // Récupération des données de l'utilisateur à partir de la ligne correspondante dans le tableau
        var row = $(this).closest('tr');
        var nom = row.find('td:eq(0)').text(); // Colonne 'Nom'
        var prenom = row.find('td:eq(1)').text(); // Colonne 'Prenom'
        var phone = row.find('td:eq(2)').text(); // Colonne 'Telephone'
        var adresse = row.find('td:eq(3)').text(); // Colonne 'Adresse'
        var email = row.find('td:eq(4)').text(); // Colonne 'Email'
        var profil = row.find('td:eq(5)').text(); // Colonne 'Profil'
        var Id = $(this).closest('tr').data('id');

        // Pré-remplissage des données dans le formulaire de la modal
        $('#edit-id').val(Id);
        $('#edit-nom').val(nom);
        $('#edit-firstname').val(prenom);
        $('#edit-phone').val(phone);
        $('#edit-adresse').val(adresse);
        $('#edit-email').val(email);

        // Sélection du profil correspondant dans la liste déroulante
        $('#edit-profil option').filter(function() {
            return $(this).text() === profil; // Sélectionne l'option dont le texte correspond au profil de l'utilisateur
        }).prop('selected', true);

        // Affichage de la modal
        $('#editUserModal').modal('show');
    });



    // Soumission du formulaire d'édition
    $('#edit-user-form').submit(function(e) {
        e.preventDefault();
        var userId=$("#edit-user-form input[name=edit-id]").val();

        // Récupération des données du formulaire
        var formData = $(this).serialize();

        // Vous pouvez envoyer les données du formulaire pour l'édition de l'utilisateur ici en utilisant AJAX
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/user/' + userId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Gérer la réponse ici
                // Par exemple, fermer la modal, afficher un message de succès, etc.
                $('#editUserModal').modal('hide');
                // Afficher un message de succès (optionnel)
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'L\'utilisateur a été modifié avec succès.'
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
                    text: 'Une erreur est survenue lors de la modification de l\'utilisateur.'
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
                // Récupérer l'ID de l'utilisateur à supprimer
                var userId = deleteBtn.closest('tr').data('id');

                // Envoyer une requête AJAX pour supprimer l'utilisateur
                $.ajax({
                    type: "DELETE",
                    url: '/deleteuser/' + userId,
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'json',
                    success: function(response) {
                        // Si la suppression est réussie, vous pouvez afficher un message de succès
                        Swal.fire(
                            'Supprimé!',
                            'L\'utilisateur a été supprimé avec succès.',
                            'success'
                        );
                        // Actualiser la page ou mettre à jour la liste des utilisateurs après la suppression
                        location.reload();
                    },
                    error: function(error) {
                        // En cas d'erreur, afficher un message d'erreur à l'utilisateur
                        Swal.fire(
                            'Erreur!',
                            'Une erreur est survenue lors de la suppression de l\'utilisateur.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
