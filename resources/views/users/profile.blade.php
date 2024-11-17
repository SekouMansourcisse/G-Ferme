@php
    $user = Auth::user();
@endphp
<!DOCTYPE html>
<html lang="en">
@include('partials._head')

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        @include('partials._topbar')
        @include('partials._sidebar_collapsed')
        @include('partials._sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Profile</h4>
                        <h6>Profile Utilisateur</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="edit-photo" enctype="multipart/form-data">
                            @csrf
                            <div class="profile-set">
                                <div class="profile-head">
                                </div>
                                <div class="profile-top">
                                    <div class="profile-content">
                                        <div class="profile-contentimg">
                                            @if (isset($user->profile_photo_path))
                                                <img src="{{ Storage::url($user->profile_photo_path) }}"
                                                    alt="User profile picture" id="blah">
                                            @else
                                                <img src="assets/img/customer/customer5.jpg" alt="img"
                                                    id="blah">
                                            @endif

                                            <div class="profileupload">
                                                <input type="file" id="photo" name="photo">
                                                <a href="javascript:void(0);"><img src="assets/img/icons/edit-set.svg"
                                                        alt="img"></a>
                                            </div>
                                        </div>
                                        <div class="profile-contentname">
                                            <h2>{{ $user->firstname }} {{ $user->name }} </h2>
                                            <h4>Mettez a jour votre Profil.</h4>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                                        <button type="button" class="btn btn-cancel">Annuler</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form method="POST" id="edit-profile" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Prenom</label>
                                        <input type="text" name="firstname" value="{{ $user->firstname }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nom" value="{{ $user->name }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" value="{{ $user->email }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Telephone</label>
                                        <input type="text" name="phone" value="{{ $user->phone }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Adresse</label>
                                        <input type="text" name="adresse" value="{{ $user->adresse }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <div class="pass-group">
                                            <input type="password" class=" pass-input form-control" name="password"
                                                value="{{ $user->password }}" >
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                                    <button type="button" class="btn btn-cancel">Annuler</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#edit-profile').submit(function(e) {
                // Empêcher le comportement par défaut du formulaire (rechargement de la page)
                e.preventDefault();

                // Sérialiser les données du formulaire
                var formData = $(this).serialize();

                // Effectuer une requête AJAX pour soumettre les données
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '/edit-profile', // Remplacez '/edit-profile' par l'URL de votre route de mise à jour du profil
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Afficher un message de succès (par exemple, avec SweetAlert)
                            Swal.fire({
                                icon: 'success',
                                title: 'Succès',
                                text: response.message
                            }).then(() => {
                                // Actualiser la page ou effectuer d'autres actions si nécessaire
                                location.reload();
                            });

                        } else {
                            // Afficher un message d'erreur en cas d'échec
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Gérer les erreurs de requête AJAX
                        console.error(xhr.responseText);
                    }
                });

            });

            $("#edit-photo").on("submit", function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/updatePhoto',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Succès",
                                text: "Photo de profil mise à jour avec succès"
                            }).then(() => {
                                location
                            .reload(); // Recharger la page après la mise à jour de la photo
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Erreur",
                                text: "Erreur lors de la mise à jour de la photo de profil"
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Erreur",
                            text: "Erreur lors de la mise à jour de la photo de profil"
                        });
                    }
                });
            });

        });


        document.getElementById("photo").addEventListener("change", function() {
            var file = this.files[0]; // Obtenez le premier fichier sélectionné

            // Vérifiez si un fichier a été sélectionné
            if (file) {
                var reader = new FileReader(); // Créez un objet FileReader

                // Définissez une fonction de rappel pour être exécutée lorsque la lecture est terminée
                reader.onload = function(e) {
                    // Mettez à jour l'élément <img> pour afficher l'aperçu de l'image
                    document.querySelector(".profile-contentimg img").src = e.target.result;
                }

                // Commencez la lecture du contenu du fichier en tant que URL de données
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>
