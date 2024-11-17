@include('partials._head')
<style>
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }
    .table i,.table img {
    width: 24px;
    height: 24px;
    cursor: pointer;
}
</style>
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
                        <h4>Ajout de Categorie d'Oeuf</h4>
                        <h6>Creer un nouveau Categorie</h6>
                    </div>
                </div>
                @if (session('success'))
                <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('operationCategorisations.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Date de l'Opération</label>
                                        <input type="date" name="date_op" id="date_op" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Informations sur la Bande</th>
                                            <th>Total à Catégoriser</th>
                                            @foreach($categories as $categorie)
                                                <th>{{ $categorie->Denomination }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($classifications as $classification)
                                            <tr data-id="{{ $classification->bande_id }}">
                                                @php
                                                    $bandes = App\Models\Bande::find($classification->bande_id);
                                                @endphp
                                                <td>{{ $bandes->nom_bande}} <br> {{ $bandes->date_demarrage}} au {{ $bandes->date_fin}}</td>
                                                <td>{{ $classification->Total_a_categoriser }}</td>
                                                @foreach($categories as $categorie)
                                                    <td>
                                                        <input type="number" name="categories[{{ $classification->bande_id }}][{{ $categorie->id }}]" class="form-control">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class="row">
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
