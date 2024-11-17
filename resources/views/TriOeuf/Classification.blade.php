@include('partials._head')
<head>
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
</head>
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
                        <h4>Liste Classification Oeuf</h4>
                        <h6>Gerer vos Classification</h6>
                    </div>

                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="{{asset('assets/img/icons/filter.svg')}}" alt="img">
                                        <span><img src="{{asset('assets/img/icons/closes.sv')}}g" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="{{asset('assets/img/icons/search-white.svg')}}"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                src="{{asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="{{asset('assets/img/icons/excel.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="{{asset('assets/img/icons/printer.svg')}}" alt="img"></a>
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

                        <!-- Votre tableau existant -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Oeuf Total</th>
                                        <th>Quantité Cassée</th>
                                        <th>Quantité non Commerciale</th>
                                        <th>Total à Catégoriser</th>
                                        @can('edit Tri des Oeufs')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classifications as $classification)
                                    <tr data-id="{{ $classification->id }}">
                                        <td>{{ $classification->date }}</td>
                                        <td>{{ $classification->OeufTotal }}</td>
                                        <td>{{ $classification->qteCasse }}</td>
                                        <td>{{ $classification->qte_nonCommerciale }}</td>
                                        <td>{{ $classification->Total_a_categoriser }}</td>
                                        @can('edit Tri des Oeufs')
                                        <td>

                                            <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>

                                            @can('delete Tri des Oeufs')
                                            <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                                                <img src="{{asset('assets/img/icons/delete.svg')}}" alt="img">
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
<!-- Modal pour modifier les quantités -->
<div class="modal fade" id="editClassificationModal" tabindex="-1" role="dialog" aria-labelledby="editClassificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="edit-classification-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassificationModalLabel">Modifier Classification</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="classification_id" id="classification-id">
                    <div class="form-group">
                        <label for="qte-casse">Date du Ramassage</label>
                        <input type="text" class="form-control" name="date_r" id="date_r" readonly>
                    </div>
                    <div class="form-group">
                        <label for="qte-casse">Quantité Oeufs Ramassé</label>
                        <input type="number" class="form-control" name="qte_r" id="qte-r" readonly>
                    </div>
                    <div class="form-group">
                        <label for="qte-casse">Quantité Cassée</label>
                        <input type="number" class="form-control" name="qte_casse" id="qte-casse" required>
                    </div>
                    <div class="form-group">
                        <label for="qte-non-commerciale">Quantité non Commerciale</label>
                        <input type="number" class="form-control" name="qte_non_commerciale" id="qte-non-commerciale" required>
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
        $(document).ready(function () {
            $('.edit-item-btn').on('click', function () {
                var classificationId = $(this).closest('tr').data('id');
                var date_r = $(this).closest('tr').find('td').eq(0).text();
                var qte_r = $(this).closest('tr').find('td').eq(1).text();
                var qteCasse = $(this).closest('tr').find('td').eq(2).text();
                var qteNonCommerciale = $(this).closest('tr').find('td').eq(3).text();
                $('#date_r').val(date_r);
                $('#qte-r').val(qte_r);
                $('#classification-id').val(classificationId);
                $('#qte-casse').val(qteCasse);
                $('#qte-non-commerciale').val(qteNonCommerciale);

                $('#editClassificationModal').modal('show');
            });
                    // Enregistrer les modifications via AJAX
        $('#edit-classification-form').on('submit', function (e) {
            e.preventDefault();

            var classificationId = $('#classification-id').val();
            var qteCasse = $('#qte-casse').val();
            var qteNonCommerciale = $('#qte-non-commerciale').val();

            $.ajax({
                url: '/classifications/' + classificationId,
                type: 'PUT',
                data: {
                    _token: $('input[name="_token"]').val(),
                    qte_casse: qteCasse,
                    qte_non_commerciale: qteNonCommerciale
                },
                success: function (response) {
                    // Fermer le modal
                    $('#editClassificationModal').modal('hide');

                    // Mettre à jour la ligne du tableau avec les nouvelles valeurs
                    var row = $('tr[data-id="' + classificationId + '"]');
                    row.find('td').eq(2).text(qteCasse);
                    row.find('td').eq(3).text(qteNonCommerciale);
                    row.find('td').eq(4).text(response.Total_a_categoriser);
                    Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Modification effectuée avec succès.',

                    confirmButtonText: 'OK', // Texte du bouton "OK"
                    customClass: {
                        confirmButton: 'btn btn-primary' // Classe CSS personnalisée pour le bouton "OK"
                    }
                }).then(() => {
                    location.reload();
                });
                },
                error: function (xhr) {
                    // Gérer les erreurs
                    alert('Une erreur est survenue lors de la mise à jour.');
                }
            });
        });
        });
    </script>

</body>
