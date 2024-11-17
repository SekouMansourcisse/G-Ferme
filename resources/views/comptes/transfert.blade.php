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
                        <h4>Liste des Transfert de fond</h4>
                        <h6>Gerer vos Transfert de fond </h6>
                    </div>
                    @can('create Comptes')
                    <div class="page-btn">
                        <button class="btn btn-added" data-toggle="modal" id="show">
                            <img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter Transfert
                        </button>
                    </div>
                    @endcan


                </div>

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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
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
                                        <th>Compte Source</th>
                                        <th>Compte de Destination</th>
                                        <th>Montant</th>
                                        @can('edit Comptes')
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transferts as $transfert)
                                    <tr data-id="{{ $transfert->id }}">
                                        <td>{{ $transfert->compteSource->Denomination }}</td>
                                        <td>{{ $transfert->compteDestination->Denomination }}</td>
                                        <td>{{ $transfert->montant }}</td>
                                        @can('edit Comptes')
                                        <td>

                                            <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="img">
                                            </a>

                                            @can('delete Comptes')
                                            <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
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
  <!-- Modal -->
  <div class="modal fade" id="editTransfertModal" tabindex="-1" aria-labelledby="editTransfertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTransfertModalLabel">Effectuer un transfert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for performing a transfer -->
                <form method="POST" id="edit-transfert-form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Compte source</label>
                                <input type="text" class="form-control" name="edit-compte-source" id="edit-compte-source">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Compte destination</label>
                                <input type="text" class="form-control" name="edit-compte-destination" id="edit-compte-destination">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Montant</label>
                                <input type="text" class="form-control" name="montant" id="montant">
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-submit me-2">Transférer</button>
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addTransfertModal" tabindex="-1" aria-labelledby="addTransfertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTransfertModalLabel">Ajouter un transfert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for adding a transfer -->
                <form method="POST" id="add-transfert-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Compte source</label>
                                <select class="form-control" name="compte_source" id="compte_source">
                                    @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}">{{ $compte->Denomination }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Compte destination</label>
                                <select class="form-control" name="compte_destination" id="compte_destination">
                                    @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}">{{ $compte->Denomination }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Montant</label>
                                <input type="text" class="form-control" name="montant" id="montant">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <label for="logo">Justificatif de transfert de fond</label>
                            <div class="image-upload">
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*,.pdf">
                                <div class="image-uploads">
                                    <!-- Aperçu de l'image ou du fichier PDF -->
                                    <div id="file-preview" style="margin-top: 10px;">
                                        <img id="preview-image" src="{{asset('assets/img/icons/upload.svg')}}" alt="Aperçu" style="max-width: 200px; display: none;">
                                        <a id="preview-pdf" href="#" target="_blank" style="display: none; font-size: 16px; color: blue;">Voir le fichier PDF</a>
                                    </div>
                                    <h4>Ajouter le justificatif</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit me-2">Transferer</button>
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{ asset('js/backend/listtransfert.js')}}"></script>
    <script>
$(document).ready(function() {

    $('#show').click(function(e) {
        $('#addTransfertModal').modal('show');

    });
});


    </script>
        <script>
            document.getElementById('logo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewImage = document.getElementById('preview-image');
            const previewPDF = document.getElementById('preview-pdf');
            const filePreview = document.getElementById('file-preview');

            if (file) {
                const fileType = file.type;

                if (fileType.startsWith('image/')) {
                    // Cacher l'aperçu PDF et afficher l'aperçu de l'image
                    previewPDF.style.display = 'none';
                    previewImage.style.display = 'block';

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                    }
                    reader.readAsDataURL(file);

                } else if (fileType === 'application/pdf') {
                    // Cacher l'aperçu de l'image et afficher le lien du PDF
                    previewImage.style.display = 'none';
                    previewPDF.style.display = 'block';

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewPDF.href = e.target.result;
                    }
                    reader.readAsDataURL(file);
                } else {
                    // Si ce n'est ni une image ni un PDF, masquer les deux aperçus
                    previewImage.style.display = 'none';
                    previewPDF.style.display = 'none';
                }
            }
        });
        </script>
</body>
