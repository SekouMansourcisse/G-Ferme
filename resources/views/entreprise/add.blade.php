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
                        <h4>Enregistrer Une entreprise</h4>
                        <h6>Formulaire d'ajout Entreprise</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="add-form" action="{{ route('entreprises.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Champ pour le nom de l'entreprise -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Nom de l'Entreprise</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Champ pour l'adresse de l'entreprise -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="adresse">Adresse</label>
                                        <input type="text" name="adresse" id="adresse" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Champ pour le numéro de téléphone de l'entreprise -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Numéro de Téléphone</label>
                                        <input type="text" name="phone" id="phone" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Champ pour l'email de l'entreprise -->
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Champ pour le logo de l'entreprise -->

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="logo">Logo de l'Entreprise</label>
                                        <div class="image-upload">
                                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                                            <div class="image-uploads">
                                                <img src="{{asset('assets/img/icons/upload.svg')}}" alt="img">
                                                <h4>Drag and drop a file to upload</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Boutons pour soumettre ou annuler -->
                                <div class="col-lg-12">
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>

</body>
