document.addEventListener('DOMContentLoaded', function() {
    // Ouvrir le modal pour éditer une souche
    document.querySelectorAll('.edit-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const soucheRow = this.closest('tr');
            document.getElementById('souche_id').value = soucheRow.getAttribute('data-id');
            document.getElementById('Denomination').value = soucheRow.children[0].innerText;
            document.getElementById('type').value = soucheRow.children[1].innerText;
            document.getElementById('infos_supp').value = soucheRow.children[2].innerText;
            $('#editSoucheModal').modal('show');
        });
    });

    // Gestion de la soumission du formulaire d'édition de souche
    $('#editSoucheForm').submit(function(e) {
        e.preventDefault();
        const soucheId = document.getElementById('souche_id').value;
        const formData = $(this).serialize() + '&_method=PUT';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/souches/' + soucheId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#editSoucheModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Souche modifiée avec succès.',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-cancel',
                    },
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errorMessage = 'Erreur lors de la modification.';
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
            fetch(`/souches/${id}`, {
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
                            'souches supprimée.',
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
