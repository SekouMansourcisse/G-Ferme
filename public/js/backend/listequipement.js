$(document).ready(function() {
    // Édition d'un équipement
    $('.edit-item-btn').click(function() {
        var equipementId = $(this).closest('tr').data('id');

        $.ajax({
            url: '/equipements/' + equipementId + '/edit',
            type: 'GET',
            success: function(response) {
                $('#editEquipementModal #equipementId').val(response.id);
                $('#editEquipementModal #denomination').val(response.Denomination);
                $('#editEquipementModal #ferme_id').val(response.ferme_id); // Assure-toi d'avoir un select pour les fermes
                $('#editEquipementModal #emplacement').val(response.Emplacement);
                $('#editEquipementModal #prix_achat').val(response.PrixAchat);
                $('#editEquipementModal #responsable').val(response.responsable);
                $('#editEquipementModal').modal('show');
            },
            error: function() {
                Swal.fire('Erreur!', 'Impossible de récupérer les données.', 'error');
            }
        });
    });

    // Mise à jour de l'équipement
    $('#updateEquipementForm').submit(function(e) {
        e.preventDefault();

        var equipementId = $('#editEquipementModal #equipementId').val();
        $.ajax({
            url: '/equipements/' + equipementId,
            type: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                Denomination: $('#editEquipementModal #denomination').val(),
                ferme_id: $('#editEquipementModal #ferme_id').val(),
                Emplacement: $('#editEquipementModal #emplacement').val(),
                PrixAchat: $('#editEquipementModal #prix_achat').val(),
                responsable: $('#editEquipementModal #responsable').val(),
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Succès', response.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                }
            },
            error: function() {
                Swal.fire('Erreur!', 'La mise à jour a échoué.', 'error');
            }
        });
    });

    // Suppression d'un équipement
    $('.delete-item-btn').click(function() {
        var equipementId = $(this).closest('tr').data('id');

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/equipements/' + equipementId,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Supprimé!', response.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Erreur!', 'Une erreur est survenue.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Erreur!', 'Impossible de supprimer.', 'error');
                    }
                });
            }
        });
    });
});
