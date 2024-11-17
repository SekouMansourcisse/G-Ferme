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
                        <h4>Liste des Vignettes pour les voitures de service</h4>
                        <h6>Gerer vos vignettes</h6>
                    </div>
                    @can('create vignettes')
                    <div class="page-btn">
                        <a href="{{url('vignettes/create')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            Vignette</a>
                    </div>
                    @endcan
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
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="{{ asset('assets/img/icons/filter.svg')}}" alt="img">
                                        <span><img src="{{ asset('assets/img/icons/closes.svg')}}" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg')}}"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" href="{{ url('listvignettepdf')}}"><img
                                                src="{{ asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="{{ asset('assets/img/icons/excel.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="{{ asset('assets/img/icons/printer.svg')}}" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card" id="filter_inputs">
                            <div class="card-body pb-0">
                                <form action="#" class="dropdown">
                                    <div class="searchinputs" id="dropdownMenuClickable"
                                        data-bs-auto-close="false">
                                        <input type="text" placeholder="Search">
                                        <div class="search-addon">
                                            <span><i data-feather="x-circle" class="feather-14"></i></span>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>

                                        <th>Voiture Associée</th>
                                        <th>Date d'Acquisition</th>
                                        <th>Date d'Expiration</th>
                                        <th>Coût</th>
                                        @can('edit vignettes')
                                        <th>Actions</th>
                                        @endcan

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vignettes as $vignette)
                                        <tr data-id="{{ $vignette->id}}">

                                            <td>{{ $vignette->voiture->plaque_immatriculation }} - {{ $vignette->voiture->modele }}</td>
                                            <td>{{ $vignette->date_obtention }}</td>
                                            <td>{{ $vignette->date_expiration }}</td>
                                            <td>{{ $vignette->montant }}</td>
                                            @can('edit vignettes')
                                            <td>

                                                <a class="me-3 edit-vignette-btn"  href="javascript:void(0);" id="edit-vignette-btn">
                                                    <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="img">
                                                </a>

                                                @can('delete vignettes')
                                                <a class="me-3 delete-vignette-btn" id="delete-vignette-btn" href="javascript:void(0);">
                                                    <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="img">
                                                </a>
                                                @endcan
                                            </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="editVignetteModal" tabindex="-1" aria-labelledby="editVignetteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVignetteModalLabel">Modifier la Vignette</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateVignetteForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="vignetteId">
                        <div class="mb-3">
                            <label for="date_obtention" class="form-label">Date d'Obtention</label>
                            <input type="date" class="form-control" id="date_obtention" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_expiration" class="form-label">Date d'Expiration</label>
                            <input type="date" class="form-control" id="date_expiration" required>
                        </div>
                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant</label>
                            <input type="number" class="form-control" id="montant" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script>
        $(document).ready(function() {
    // Édition d'une vignette
    $('.edit-vignette-btn').click(function() {
        var vignetteId = $(this).closest('tr').data('id');

        $.ajax({
            url: '/vignettes/' + vignetteId + '/edit',
            type: 'GET',
            success: function(response) {
                $('#editVignetteModal #vignetteId').val(response.id);
                $('#editVignetteModal #date_obtention').val(response.date_obtention);
                $('#editVignetteModal #date_expiration').val(response.date_expiration);
                $('#editVignetteModal #montant').val(response.montant);
                $('#editVignetteModal').modal('show');
            },
            error: function() {
                Swal.fire('Erreur!', 'Impossible de récupérer les données.', 'error');
            }
        });
    });

    // Mise à jour de la vignette
    $('#updateVignetteForm').submit(function(e) {
        e.preventDefault();

        var vignetteId = $('#editVignetteModal #vignetteId').val();
        $.ajax({
            url: '/vignettes/' + vignetteId,
            type: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                date_obtention: $('#editVignetteModal #date_obtention').val(),
                date_expiration: $('#editVignetteModal #date_expiration').val(),
                montant: $('#editVignetteModal #montant').val(),
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

    // Suppression d'une vignette
    $('.delete-vignette-btn').click(function() {
        var vignetteId = $(this).closest('tr').data('id');

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
                    url: '/vignettes/' + vignetteId,
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

    </script>
</body>
