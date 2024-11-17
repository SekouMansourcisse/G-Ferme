document.addEventListener('DOMContentLoaded', function() {
    // Ouverture du modal d'édition
    document.querySelectorAll('.edit-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const depenseRow = this.closest('tr');

            // Récupération des valeurs de la ligne du tableau
            document.getElementById('depense_id').value = depenseRow.getAttribute('data-id');
            document.getElementById('Date_depense').value = depenseRow.children[0].innerText;
            document.getElementById('Beneficiaire').value = depenseRow.children[1].innerText;
            document.getElementById('Categorie_depense').value = depenseRow.children[2].innerText;
            document.getElementById('TypeDepense_id').value = depenseRow.getAttribute('data-type-depense-id');
            document.getElementById('fournisseur_id').value = depenseRow.getAttribute('data-fournisseur');
            document.getElementById('payer_par').value = depenseRow.getAttribute('data-compte');
            document.getElementById('Objet').value = depenseRow.children[4].innerText;
            document.getElementById('Montant_d').value = depenseRow.children[5].innerText;
            document.getElementById('Montant_paye').value = depenseRow.children[6].innerText;

            // Ouvrir la modale
            $('#editExpenseModal').modal('show');
        });
    });


    // Soumission du formulaire d'édition
    $('#editExpenseForm').submit(function(e) {
        e.preventDefault();
        const depenseId = document.getElementById('depense_id').value;
        const formData = $(this).serialize() + '&_method=PUT';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/depenses/' + depenseId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#editExpenseModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Depense modifié avec succès.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Erreur lors de la modification.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Suppression de l'abattoir
    document.querySelectorAll('.delete-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const depenseId = this.closest('tr').getAttribute('data-id');

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible.",
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-cancel',
                },
                confirmButtonText: 'Oui, supprimer'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        url: '/depenses/' + depenseId,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Supprimé',
                                text: 'Depense supprimé avec succès.',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Erreur lors de la suppression de l\'abattoir.',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
});
