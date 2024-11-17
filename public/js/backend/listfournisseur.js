$(document).ready(function() {
    // Détection du clic sur l'icône d'édition
    $('.edit-item-btn').click(function() {
        // Récupération des données du fournisseur à partir de la ligne correspondante dans le tableau
        var row = $(this).closest('tr');
        var nom = row.find('td:eq(0)').text(); // Colonne 'Nom'
        var prenom = row.find('td:eq(1)').text(); // Colonne 'Prénom'
        var telephone = row.find('td:eq(2)').text(); // Colonne 'Téléphone'
        var numWhatsApp = row.find('td:eq(3)').text(); // Colonne 'N° WhatsApp'
        var redevanceInitiale = row.find('td:eq(4)').text(); // Colonne 'Redevance Initiale'
        var adresse = row.find('td:eq(5)').text(); // Colonne 'Adresse'
        var produit = row.find('td:eq(6)').text(); // Colonne 'Produit'
        var infosSupplementaires = row.find('td:eq(7)').text(); // Colonne 'Infos supplémentaires'
        var Id = row.data('id');

        // Pré-remplissage des données dans le formulaire de la modal
        $('#edit-id').val(Id);
        $('#edit-nom').val(nom);
        $('#edit-prenom').val(prenom);
        $('#edit-telephone').val(telephone);
        $('#edit-num-whatsapp').val(numWhatsApp);
        $('#edit-redevance-initiale').val(redevanceInitiale);
        $('#edit-adresse').val(adresse);
        $('#edit-infos-supplementaires').val(infosSupplementaires);

        // Affichage de la modal
        $('#editFournisseurModal').modal('show');
    });


    // Soumission du formulaire d'édition
    $('#edit-fournisseur-form').submit(function(e) {
        e.preventDefault();
        var fournisseurId = $("#edit-fournisseur-form input[name=edit-id]").val();

        // Récupération des données du formulaire
        var formData = $(this).serialize();

        // Vous pouvez envoyer les données du formulaire pour l'édition du fournisseur ici en utilisant AJAX
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/fournisseur/' + fournisseurId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Gérer la réponse ici
                // Par exemple, fermer la modal, afficher un message de succès, etc.
                $('#editFournisseurModal').modal('hide');
                // Afficher un message de succès (optionnel)
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Le fournisseur a été modifié avec succès.'
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
                    text: 'Une erreur est survenue lors de la modification du fournisseur.'
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
                // Récupérer l'ID du fournisseur à supprimer
                var fournisseurId = deleteBtn.closest('tr').data('id');

                // Envoyer une requête AJAX pour supprimer le fournisseur
                $.ajax({
                    type: "DELETE",
                    url: '/deletefournisseur/' + fournisseurId,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Si la suppression est réussie, vous pouvez afficher un message de succès
                        Swal.fire(
                            'Supprimé!',
                            'Le fournisseur a été supprimé avec succès.',
                            'success'
                        );
                        // Actualiser la page ou mettre à jour la liste des fournisseurs après la suppression
                        location.reload();
                    },
                    error: function(error) {
                        // En cas d'erreur, afficher un message d'erreur à l'utilisateur
                        Swal.fire(
                            'Erreur!',
                            'Une erreur est survenue lors de la suppression du fournisseur.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
