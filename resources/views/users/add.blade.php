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
                        <h4>User Management</h4>
                        <h6>Add/Update User</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nom" id="nom" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Prenom</label>
                                        <input type="text" name="firstnom" id="firstnom" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" id="email" class="form-control">
                                        <div id="email_error" style="color:red;"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <div class="pass-group">
                                            <input type="password"  name="password_confirmation" id="password_confirmation" class="form-control pass-input">
                                            <span class="fas toggle-passworda fa-eye-slash"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>N°Telephone</label>
                                        <input type="text" name="phone" id="phone" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Adresse</label>
                                        <input type="text" name="adresse" id="adresse" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <div class="pass-group">
                                            <input type="password"  name="password" id="password" class="form-control pass-input">
                                            <span class="fas toggle-password fa-eye-slash"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Photo Profile</label>
                                        <div class="image-upload image-upload-new">
                                            <input type="file" name="photo" id="photo" class="form-control">

                                            <div class="image-uploads">
                                                <img src="assets/img/icons/upload.svg" alt="img">
                                                <h4>Ajouter une photo de profile</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Dénomination de la ferme</th>
                                            <th>Profil de l'administrateur dans les fermes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fermes as $ferme)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="fermes[]" value="{{ $ferme->id}}"> {{ $ferme->name}}
                                                </td>
                                                <td>
                                                    <select class="form-select" name="profil">
                                                        <option value="1">Sélectionnez le profil de l'administrateur</option>
                                                        <!-- Options for administrator profiles can be added here -->
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                                    <button type="button" class="btn btn-cancel" onclick="window.history.back();">Annuler</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials.script')

    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script>
        document.getElementById("photo").addEventListener("change", function() {
            var file = this.files[0]; // Obtenez le premier fichier sélectionné

            // Vérifiez si un fichier a été sélectionné
            if (file) {
                var reader = new FileReader(); // Créez un objet FileReader

                // Définissez une fonction de rappel pour être exécutée lorsque la lecture est terminée
                reader.onload = function(e) {
                    // Mettez à jour l'élément <img> pour afficher l'aperçu de l'image
                    document.querySelector(".image-uploads img").src = e.target.result;
                }

                // Commencez la lecture du contenu du fichier en tant que URL de données
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script src="{{ asset('js/backend/adduser.js') }}"></script>
</body>
