
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-race-btn').forEach(button => {
        button.addEventListener('click', function() {
            const raceRow = this.closest('tr');
            document.getElementById('race_id').value = raceRow.getAttribute('data-id');
            document.getElementById('Denomination').value = raceRow.getAttribute('data-nom');
            document.getElementById('elevage').value = raceRow.getAttribute('data-type');
            $('#editRaceModal').modal('show'); // Montrer le modal
        });
    });

    $('#editRaceForm').submit(function(e) {
        e.preventDefault();
        const raceId = document.getElementById('race_id').value;

        // Ajouter _method=PUT pour tromper Laravel en mode POST
        const formData = $(this).serialize() + '&_method=PUT';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST", // Utilisation de POST
            url: '/races/' + raceId, // URL dynamique
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#editRaceModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Race modifiée avec succès.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors de la modification.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: errorMessage,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});


function confirmDelete2(id) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible!",
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-cancel',
        },
        confirmButtonText: 'Oui, supprimer !'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/races/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Supprimé !',
                            'race supprimée.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur !', data.message, 'error');
                    }
                })
                .catch(error => Swal.fire('Erreur !', 'Une erreur s\'est produite lors de la suppression.',
                    'error'));
        }
    });
}
