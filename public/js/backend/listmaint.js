$(document).ready(function() {
    // Édition d'une maintenance
    $('.edit-maintenance-btn').click(function() {
        var maintenanceId = $(this).closest('tr').data('id');

        $.ajax({
            url: '/maintenances/' + maintenanceId + '/edit',
            type: 'GET',
            success: function(response) {
                $('#editMaintenanceModal #maintenanceId').val(response.id);
                $('#editMaintenanceModal #date_maintenance').val(response.date_maintenance);
                $('#editMaintenanceModal #type_maintenance').val(response.type_maintenance);
                $('#editMaintenanceModal #cout').val(response.cout);
                $('#editMaintenanceModal #commentaire').val(response.commentaire);
                $('#editMaintenanceModal').modal('show');
            },
            error: function() {
                Swal.fire('Erreur!', 'Impossible de récupérer les données.', 'error');
            }
        });
    });

    // Mise à jour de la maintenance
    $('#updateMaintenanceForm').submit(function(e) {
        e.preventDefault();

        var maintenanceId = $('#editMaintenanceModal #maintenanceId').val();
        $.ajax({
            url: '/maintenances/' + maintenanceId,
            type: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                date_maintenance: $('#editMaintenanceModal #date_maintenance').val(),
                type_maintenance: $('#editMaintenanceModal #type_maintenance').val(),
                cout: $('#editMaintenanceModal #cout').val(),
                commentaire: $('#editMaintenanceModal #commentaire').val(),
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

    // Suppression d'une maintenance
    $('.delete-maintenance-btn').click(function() {
        var maintenanceId = $(this).closest('tr').data('id');

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
                    url: '/maintenances/' + maintenanceId,
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
