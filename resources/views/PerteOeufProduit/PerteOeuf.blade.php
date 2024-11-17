@include('partials._head')
<style>
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table i,
    .table img {
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
                        <h4>Liste des pertes d'Oeufs</h4>
                        <h6>Gerer vos Declaration de pertes</h6>
                    </div>
                    @can('create Perte Oeufs')
                        <div class="page-btn">
                            <a href="{{ url('perte-eufs/create') }}" class="btn btn-added"><img
                                    src="{{asset('assets/img/icons/plus.svg') }}" alt="img">Declarer
                                une Perte</a>
                        </div>
                    @endcan
                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="{{asset('assets/img/icons/filter.svg') }}" alt="img">
                                        <span><img src="{{asset('assets/img/icons/closes.svg') }}" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="{{asset('assets/img/icons/search-white.svg') }}"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                            href="{{ url('perte_oeufs/export-pdf') }}" title="pdf"><img
                                                src="{{asset('assets/img/icons/pdf.svg') }}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="{{asset('assets/img/icons/excel.svg') }}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="{{asset('assets/img/icons/printer.svg') }}" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card" id="filter_inputs">
                            <div class="card-body pb-0">
                                <form action="#" class="dropdown">
                                    <div class="searchinputs" id="dropdownMenuClickable" data-bs-auto-close="false">
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
                                        <th>Date de declaration</th>
                                        <th>Description</th>

                                        <th>Œufs Perdus</th>
                                        @can('edit Perte Oeufs')
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pertes as $perte)
                                        @if ($perte->type_perte == 'Oeufs')
                                            <tr data-id="{{ $perte->id }}">

                                                <td>{{ $perte->date_p }}</td>
                                                <td>{{ $perte->description }}</td>

                                                <td>
                                                    @foreach (explode(',', $perte->Oeuf) as $oeuf)
                                                        @php
                                                            [$id, $qte] = explode('*', $oeuf);
                                                            $oeufNom = App\Models\CategorieOeuf::find($id)
                                                                ->Denomination;
                                                        @endphp
                                                        {{ $oeufNom }} ({{ $qte }} Oeufs)<br>
                                                    @endforeach
                                                </td>

                                                @can('edit Perte Oeufs')
                                                    <td>

                                                        <a class="me-3 edit-item-btn" href="javascript:void(0);"
                                                            id="edit-item-btn">
                                                            <img src="{{ asset('assets/img/icons/edit.svg') }}"
                                                                alt="img">
                                                        </a>

                                                        @can('delete Perte Oeufs')
                                                            <a class="me-3 delete-item-btn" id="delete-item-btn"
                                                                href="javascript:void(0);">
                                                                <img src="{{ asset('assets/img/icons/delete.svg') }}"
                                                                    alt="img">
                                                            </a>
                                                        @endcan
                                                    </td>
                                                @endcan

                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune perte d'œufs enregistrée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editPerteForm" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Modifier la perte d'œufs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="type_perte" id="type_perte" value="Oeufs">
                    <input type="hidden" name="perte_id" id="perte_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="date">Date de déclaration</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div id="oeufList"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Handle form submission for updating the perte
            $('#editPerteForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                // Get the perte ID from the form action attribute
                var perteId = $('#perte_id').val();

                var formData = $(this).serialize();

                $.ajax({
                    url: '/pertes-oeufs_update/' + perteId, // The route for the update action
                    type: 'POST', // Use POST method instead of PUT
                    data: formData, // Add _method=PUT to simulate PUT request
                    success: function(response) {
                        if (response.success) {
                            // Close the modal and reload the page or update the row
                            $('#editModal').modal('hide');
                            location.reload(); // You can also update the row without reloading
                        } else {
                            alert('Failed to update: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });

        });

        // Suppression
        $('.delete-item-btn').click(function() {
            var perteId = $(this).closest('tr').data('id');

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
                        url: '/pertes-oeufs/' + perteId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Supprimé!', 'La perte d\'œufs a été supprimée.',
                                        'success')
                                    .then(() => location.reload());
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

        // Édition
        $('.edit-item-btn').click(function() {
            var perteId = $(this).closest('tr').data('id');

            $.ajax({
                url: '/pertes-oeufs/' + perteId + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#editModal').find('input[name="perte_id"]').val(perteId);
                    $('#editModal').find('input[name="date"]').val(response.date_p);
                    $('#editModal').find('textarea[name="description"]').val(response.description);

                    // Si vous avez des champs pour les oeufs perdus, initialisez-les ici
                    $('#oeufList').empty();
                    response.oeufs.forEach(function(oeuf) {
                        $('#oeufList').append(
                            `<div class="form-group">
                        <label>${oeuf.nom}</label>
                        <input type="number" class="form-control" name="qte_perdu[${oeuf.id}]" value="${oeuf.qte}">
                    </div>`
                        );
                    });

                    $('#editModal').modal('show');
                }
            });
        });
    </script>
</body>
