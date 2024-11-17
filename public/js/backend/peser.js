document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll(".edit-pesage-btn");

    document.querySelectorAll('.edit-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const pesageId = row.getAttribute("data-id");
            const date = row.getAttribute("data-date");
            const semaineP = row.getAttribute("data-semaine");
            const poulaillerData = row.getAttribute("data-poulailler").split(',');

            // Remplir les champs du formulaire d'édition
            document.getElementById("pesage_id").value = pesageId;
            document.getElementById("editDate").value = date;
            document.getElementById("editSemaineP").value = semaineP;
            $("#editPesageForm input[name=Date]").val(date);
            // Remplir le tableau des poulaillers
            const tbody = document.getElementById("editPesageTableBody");
            tbody.innerHTML = ''; // Vider le tableau avant de le remplir

            poulaillerData.forEach(entry => {
                const [id, poidsMoyen, cheptel, poidsTotal, photoPath] = entry.split('*');
                const poulaillerName = getPoulaillerName(id); // Fonction pour obtenir le nom du poulailler

                const photoUrl = `{{ asset('storage') }}/${photoPath}`; // Construire l'URL complète

                tbody.innerHTML += `
                    <tr>
                        <td>${poulaillerName}</td>
                        <td><input type="text" class="form-control" name="poids_moyen[]" value="${poidsMoyen}" required></td>
                        <td>
                            <div class="profileupload">
                                <input type="file" id="photo_${id}" name="photo[]" style="display: none;">
                                <a href="javascript:void(0);" onclick="document.getElementById('photo_${id}').click();"><i class="fas fa-camera"></i></a>
                            </div>
                        </td>
                        <td><img src="${photoUrl}" alt="Aperçu de l'image" style="width: 100px;"></td>
                    </tr>`;
            });


            // Afficher le modal
            const editModal = new bootstrap.Modal(document.getElementById("editPesageModal"));
            editModal.show();
        });
    });

    function getPoulaillerName(id) {
        let poulaillerName = `Poulailler ${id}`;

        $.ajax({
            url: `/poulailler/${id}/nom`,
            method: 'GET',
            async: false, // pour garantir le retour immédiat (peut être lent sur des appels successifs)
            success: function(data) {
                if (data.nom) {
                    poulaillerName = data.nom;
                }
            },
            error: function() {
                console.error('Erreur lors de la récupération du nom du poulailler');
            }
        });

        return poulaillerName;
    }

});
// Gestion de la soumission du formulaire d'édition de pesage
$('#editPesageForm').submit(function(e) {
    e.preventDefault();
    const pesageId = document.getElementById('pesage_id').value;
    const formData = $(this).serialize() + '&_method=PUT';

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: '/pesages/' + pesageId,
        data: formData,
        dataType: 'json',
        success: function(response) {
            $('#editPesageModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: 'pesage modifiée avec succès.',
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

function confirmDelete4(id) {
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
            fetch(`/pesages/${id}`, {
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
                            'pesages supprimée.',
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

