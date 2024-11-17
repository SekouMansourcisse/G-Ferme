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
                        <h4>Ajout Un Type de Depense</h4>
                        <h6>Creer un nouveau Type de Depense</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('typesDepense.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="Denomination">Dénomination</label>
                                <input type="text" class="form-control" id="Denomination" name="Denomination" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description">
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                                <button type="button" class="btn btn-cancel" onclick="window.history.back();">Annuler</button>
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
