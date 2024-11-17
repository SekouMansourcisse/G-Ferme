document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll(".edit-journal-btn");

    document.querySelectorAll('.edit-journal-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const journalId = row.getAttribute("data-id");
            const date = row.getAttribute("data-date");
            const age = row.getAttribute("data-age");
            const commentaire = row.getAttribute("data-commentaire");
            const poulaillerData = row.getAttribute("data-poulailler").split(',');

            $("#editJournalisationForm input[name=journal_id]").val(journalId);
            $("#editJournalisationForm input[name=Date]").val(date);

            $("#editJournalisationForm input[name=Age]").val(age);
            $("#editJournalisationForm input[name=Resume]").val(commentaire);


            // Remplir les données des poulaillers dans le tableau
            const tbody = document.querySelector("#editJournalisationModal tbody");
            tbody.innerHTML = ''; // Vider le tableau avant de le remplir

            poulaillerData.forEach(entry => {
                const [id, tri, malade, mort, retour_maladie] = entry.split('*');
                const poulaillerName = getPoulaillerName(id); // Fonction pour obtenir le nom du poulailler

                tbody.innerHTML += `
                    <tr>
                        <td>${poulaillerName}</td>
                        <td><input type="text" class="form-control" name="Tri[]" value="${tri}" required></td>
                        <td><input type="text" class="form-control" name="Malade[]" value="${malade}" required></td>
                        <td><input type="text" class="form-control" name="Mort[]" value="${mort}" required></td>
                        <td><input type="text" class="form-control" name="Retour_malade[]" value="${retour_maladie}" required></td>
                    </tr>`;
            });

            // Afficher le modal
            setTimeout(() => {
                const editModal = new bootstrap.Modal(document.getElementById("editJournalisationModal"));
                editModal.show();
            }, 100);
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
// Gestion de la soumission du formulaire d'édition de journal
$('#editJournalisationForm').submit(function(e) {
    e.preventDefault();
    const journalId = document.getElementById('journal_id').value;
    const formData = $(this).serialize() + '&_method=PUT';

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: '/journalisations/' + journalId,
        data: formData,
        dataType: 'json',
        success: function(response) {
            $('#editjournalModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: 'journal modifiée avec succès.',
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

function confirmDelete3(id) {
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
            fetch(`/journalisations/${id}`, {
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
                            'journals supprimée.',
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
document.getElementById('addJournalisationBtn').addEventListener('click', function() {
    // Affiche le formulaire et cache la liste
    document.getElementById('journalisationList').style.display = 'none';
    document.getElementById('addJournalisationForm').style.display = 'block';
    document.getElementById('addJournalisationBtn').style.display = 'none';
    // Met à jour le texte du titre
    var titreH4 = document.querySelector('#titre h4');
    var titreH6 = document.querySelector('#titre h6');
    titreH4.textContent = 'Journalisations';
    titreH6.textContent = 'Ajouter une nouvelle journalisation';


});

document.getElementById('cancelAddJournalisationBtn').addEventListener('click', function() {
    // Cache le formulaire et affiche la liste
    document.getElementById('addJournalisationForm').style.display = 'none';
    document.getElementById('journalisationList').style.display = 'block';

    // Réinitialise le texte du titre
    var titreH4 = document.querySelector('#titre h4');
    var titreH6 = document.querySelector('#titre h6');
    titreH4.textContent = 'Journalisations';
    titreH6.textContent = 'Liste des Journalisations';

});
