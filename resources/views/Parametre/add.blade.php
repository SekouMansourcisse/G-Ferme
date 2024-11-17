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
                        <h4>Parametres du systèmes</h4>
                        <h6>Gerer les parametres generaux</h6>
                    </div>
                </div>
                @if (session('success'))
                <div id="success-message" class="alert alert-success alert-dismissible fade show"
                    role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="id" value="{{ $settings->id ?? '' }}">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Nom de votre ferme <span class="manitory">*</span></label>
                                        <input type="text" placeholder="Entrez le nom de la ferme" id="ferme_name" name="ferme_name" class="form-control"
                                               value="{{ old('ferme_name', $settings->nomFerme ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Sigle<span class="manitory">*</span></label>
                                        <input type="text" placeholder="Entrez le Sigle de votre ferme" id="sigle" name="sigle" class="form-control"
                                               value="{{ old('sigle', $settings->SigleFerme ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Adresse <span class="manitory">*</span></label>
                                        <input type="text" placeholder="Entrez l'adresse de la ferme" id="adresse" name="adresse" class="form-control"
                                               value="{{ old('adresse', $settings->adresse ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Telephone<span class="manitory">*</span></label>
                                        <input type="text" placeholder="Entrez le numero de telephone" id="phone" name="phone" class="form-control"
                                               value="{{ old('phone', $settings->phone_ferme ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Email de la ferme<span class="manitory"></span></label>
                                        <input type="text" placeholder="Entrez l'email de la ferme" id="email" name="email" class="form-control"
                                               value="{{ old('email', $settings->email_ferme ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Devise<span class="manitory">*</span></label>
                                        <input type="text" placeholder="Entrez le nom de la devise à utiliser" id="devise" name="devise" class="form-control"
                                               value="{{ old('devise', $settings->devise ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Message de pied de page de la facture <span class="manitory"></span></label>
                                        <textarea class="form-control" id="Resume" name="Resume" required>{{ old('Resume', $settings->facture_message ?? '') }}</textarea>
                                    </div>
                                </div>
                                <br>

                                <!-- Images d'aperçu pour les logos -->
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Logo du Titre</label>
                                            <div class="image-upload" style="position: relative; width: 100px; height: 100px;">
                                                <input type="file" id="titrelogo" name="titrelogo" onchange="previewImage(this, 'titrelogoPreview')" style="display: none;">
                                                <div class="image-uploads" onclick="document.getElementById('titrelogo').click();" style="cursor: pointer;">
                                                    <img id="titrelogoPreview"
                                                         src="{{ $settings->logo_titre ? asset('storage/' . $settings->logo_titre) : asset('assets/img/icons/upload.svg') }}"
                                                         alt="Aperçu du Logo Titre" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Logo de la Facture</label>
                                            <div class="image-upload" style="position: relative; width: 100px; height: 100px;">
                                                <input type="file" id="facturelogo" name="facturelogo" onchange="previewImage(this, 'facturelogoPreview')" style="display: none;">
                                                <div class="image-uploads" onclick="document.getElementById('facturelogo').click();" style="cursor: pointer;">
                                                    <img id="facturelogoPreview"
                                                         src="{{ $settings->logo_facture ? asset('storage/' . $settings->logo_facture) : asset('assets/img/icons/upload.svg') }}"
                                                         alt="Aperçu du Logo Facture" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons -->
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
        // Stockez le chemin de l'icône par défaut dans une variable
        const defaultIconPath = "{{ asset('assets/img/icons/upload.svg') }}";

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = defaultIconPath; // Utiliser le chemin stocké
            }
        }
    </script>

</body>
