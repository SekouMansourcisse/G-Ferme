document.addEventListener('DOMContentLoaded', function() {
    // Ouverture du modal d'édition
    document.querySelectorAll('.edit-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const abattoireRow = this.closest('tr');
            const abattoireId = abattoireRow.getAttribute('data-id');

            // Récupération des valeurs de la ligne du tableau
            document.getElementById('abattu_id').value = abattoireId;
            document.getElementById('abbatoire_id').value = abattoireRow.getAttribute('data-ab_id');
            document.getElementById('nombre_sujet').value = abattoireRow.children[1].innerText;
            document.getElementById('poids_abbatu').value = abattoireRow.children[2].innerText;
            document.getElementById('date_abbatage').value = abattoireRow.children[3].innerText;
            $('#editAbattoireModal').modal('show');
        });
    });

    // Soumission du formulaire d'édition
    $('#editAbattoireForm').submit(function(e) {
        e.preventDefault();
        const abattoireId = document.getElementById('abattu_id').value;
        const formData = $(this).serialize() + '&_method=PUT';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/sujetsAbbatus/' + abattoireId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#editAbattoireModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Abattage modifié avec succès.',
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
                    text: 'Erreur lors de la modification de l\'abattoir.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Suppression de l'abattoir
    document.querySelectorAll('.delete-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const abattoireId = this.closest('tr').getAttribute('data-id');

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
                        url: '/sujetsAbbatus/' + abattoireId,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Supprimé',
                                text: 'abattage supprimé avec succès.',
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
