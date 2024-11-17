document.addEventListener('DOMContentLoaded', function() {
    // Ouverture du modal d'édition
    document.querySelectorAll('.edit-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const abattoireRow = this.closest('tr');
            const abattoireId = abattoireRow.getAttribute('data-id');

            // Récupération des valeurs de la ligne du tableau
            document.getElementById('abattoire_id').value = abattoireId;
            document.getElementById('denomination').value = abattoireRow.children[0].innerText;
            document.getElementById('quantite_sujet').value = abattoireRow.children[1].innerText;
            document.getElementById('adresse').value = abattoireRow.children[2].innerText;

            $('#editAbattoireModal').modal('show');
        });
    });

    // Soumission du formulaire d'édition
    $('#editAbattoireForm').submit(function(e) {
        e.preventDefault();
        const abattoireId = document.getElementById('abattoire_id').value;
        const formData = $(this).serialize() + '&_method=PUT';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/abbatoires/' + abattoireId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#editAbattoireModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Abattoir modifié avec succès.',
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
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        url: '/abbatoires/' + abattoireId,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Supprimé',
                                text: 'L\'abattoir a été supprimé avec succès.',
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
