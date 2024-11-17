$(document).ready(function() {

    //js pour verifier si l'email existe deja
    $('#email').on('keyup', function () {
        var email = $(this).val();
        $.post({
            url: '/verifEmail',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // Ajoutez le jeton CSRF dans les données
                email: email
            },
            success: function (response) {
                $('#email_error').html(response);
            }
        });
    });


    $("#add-form").on("submit", function(e) {
        e.preventDefault();

        // Créer un nouvel objet FormData
        var formData = new FormData(this);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/ajoutUser',
            type: "POST",
            data: formData,
            contentType: false, // Nécessaire pour envoyer correctement les fichiers
            processData: false, // Nécessaire pour envoyer correctement les fichiers
            dataType: "json",
            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Succès",
                        text: "L'utilisateur a été ajouté avec succès",
                        confirmButtonColor: '#3085d6', // Couleur du bouton "OK"
                        confirmButtonText: 'OK', // Texte du bouton "OK"
                        customClass: {
                            confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
                        }
                    }).then(() => {
                        $('#add-form').trigger("reset");
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text: "Erreur lors de l'ajout de l'utilisateur",
                        confirmButtonColor: '#d33', // Couleur du bouton "OK"
                        confirmButtonText: 'OK', // Texte du bouton "OK"
                        customClass: {
                            confirmButton: 'btn btn-danger' // Classe CSS personnalisée pour le bouton "OK"
                        }

                    });
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(errors, function(key, value) {
                        errorMessage += value + "\n";
                    });

                    Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text: errorMessage,  // Afficher les erreurs renvoyées par Laravel
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text: "Erreur lors de l'ajout de l'utilisateur",
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            }

        });
    });




    })
