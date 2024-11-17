$(document).ready(function() {
    // Détection du clic sur l'icône d'édition
    $('.edit-item-btn').click(function() {
        // Récupération des données du produit à partir de la ligne correspondante dans le tableau
        var row = $(this).closest('tr');
        var referenceProduit = row.find('td:eq(0)').text(); // Colonne 'Référence Produit'
        var denomination = row.find('td:eq(1)').text(); // Colonne 'Dénomination'
        var stockSeuil = row.find('td:eq(2)').text(); // Colonne 'Seuil de Stock'
        var qteStock = row.find('td:eq(3)').text(); // Colonne 'Quantité de Stock'
        var prixUnitaire = row.find('td:eq(4)').text(); // Colonne 'Prix Unitaire'
        var infosSupplementaires = row.find('td:eq(5)').text(); // Colonne 'Informations Supplémentaires'
        var Id = $(this).closest('tr').data('id');

        // Pré-remplissage des données dans le formulaire de la modal
        $('#edit-id').val(Id);
        $('#edit-reference_produit').val(referenceProduit);
        $('#edit-denomination').val(denomination);
        $('#edit-qte_stock').val(qteStock);
        $('#edit-stock_seuil').val(stockSeuil);
        $('#edit-prix_unitaire').val(prixUnitaire);
        $('#edit-infos-supplementaires').val(infosSupplementaires);

        // Affichage de la modal
        $('#editProduitModal').modal('show');
    });

    // Soumission du formulaire d'édition
    $('#edit-produit-form').submit(function(e) {
        e.preventDefault();
        var produitId = $("#edit-produit-form input[name=edit-id]").val();

        // Récupération des données du formulaire
        var formData = $(this).serialize();

        // Vous pouvez envoyer les données du formulaire pour l'édition du produit ici en utilisant AJAX
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/produit/' + produitId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Gérer la réponse ici
                // Par exemple, fermer la modal, afficher un message de succès, etc.
                $('#editProduitModal').modal('hide');
                // Afficher un message de succès (optionnel)
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Le produit a été modifié avec succès.',
                    confirmButtonColor: '#d33', // Couleur du bouton "OK"
                    confirmButtonText: 'OK', // Texte du bouton "OK"
                    customClass: {
                        confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
                    }
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';

                // Parcourez les erreurs et construisez un message
                $.each(errors, function(key, value) {
                    errorMessage += value[0] + '\n'; // Ajoutez chaque erreur à un message
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: errorMessage || 'Une erreur est survenue lors de la modification du produit.',
                    confirmButtonColor: '#d33',
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
            // Récupérer l'ID du produit à supprimer
            var produitId = deleteBtn.closest('tr').data('id');

            // Envoyer une requête AJAX pour supprimer le produit
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "DELETE",
                url: '/deleteproduit/' + produitId,
                success: function(response) {
                    console.log(response); // Log de la réponse
                    Swal.fire(
                        'Supprimé!',
                        'Le produit a été supprimé avec succès.',
                        'success'
                    );
                    location.reload();
                },
                error: function(error) {
                    console.log(error); // Log de l'erreur
                    Swal.fire(
                        'Erreur!',
                        'Une erreur est survenue lors de la suppression du produit.',
                        'error'
                    );
                }
            });
        }
    });
});

});
