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
                        <h4>Liste des Comptes</h4>
                        <h6>Gerer vos Comptes</h6>
                    </div>
                    @if (session('success'))
                    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @can('create Comptes')
                    <div class="page-btn">
                        <a href="{{url('compte')}}" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Ajouter
                            Compte</a>
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
                                        <a data-bs-toggle="tooltip" onclick="window.print()"  data-bs-placement="top" title="print"><img
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

                                        <th>Denomination </th>
                                        <th>Solde Actuelle</th>

                                        <th>Infos suppls</th>
                                        @can('edit Comptes')
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comptes as $compte)
                                    <tr data-id="{{ $compte->id }}">
                                        <td>{{ $compte->Denomination }}</td>
                                        <td>{{ $compte->solde_actuel }}</td>
                                        <td>{{ $compte->infos_supp }}</td>
                                        @can('edit Comptes')
                                            <td>

                                                <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                    <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="edit">
                                                </a>

                                                @can('delete Comptes')
                                                <a class="me-3 delete-item-btn" href="javascript:void(0);" id="delete-item-btn">
                                                    <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="delete">
                                                </a>
                                                @endcan

                                                    @if ($compte->Denomination=="Caisse")
                                                    <!-- Approvisionnement du compte -->
                                                        <a class="me-3 supply-item-btn" href="javascript:void(0);" id="supply-item-btn">
                                                            <img src="{{ asset('assets/img/icons/dash2.svg') }}" alt="img"> <!-- Icone pour approvisionnement -->
                                                        </a>
                                                    @endif

                                                    <!-- Icône d'historique -->
                                                    <a class="me-3 history-item-btn" href="{{ route('comptes.historique', $compte->id) }}" id="history-item-btn">
                                                    <i data-feather="clock"></i>
                                                    </a>



                                            </td>
                                        @endcan
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Liens de pagination -->

                        </div>
                        <br>
                        <div class="pagination-wrapper">
                            {{ $comptes->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
  <!-- Modal -->
  <div class="modal fade" id="editCompteModal" tabindex="-1" aria-labelledby="editCompteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompteModalLabel">Modifier le compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for editing compte -->
                <form method="POST" id="edit-compte-form">
                    @csrf
                    <div class="row">
                        <input type="hidden" class="form-control" name="edit-id" id="edit-id">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Denomination</label>
                                <input type="text" class="form-control" name="edit-denomination" id="edit-denomination">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Solde actuel</label>
                                <input type="text" class="form-control" name="edit-solde-actuel" id="edit-solde-actuel">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Informations supplémentaires</label>
                                <textarea class="form-control" name="edit-infos-supplementaires" id="edit-infos-supplementaires" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="approCompteModal" tabindex="-1" aria-labelledby="approCompteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aproCompteModalLabel">Approvisionnement de compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for editing compte -->
                <form method="POST" id="appro-compte-form" action="{{ route('appro_caisse')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" class="form-control" name="compte_id" id="compte_id">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Denomination</label>
                                <input type="text" class="form-control" name="denomination" id="denomination" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Solde actuel</label>
                                <input type="text" class="form-control" name="solde-actuel" id="solde-actuel" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Montant d'approvisionnement</label>
                                <input type="text" class="form-control" name="montant_appro" id="montant_appro">
                                <input type="hidden" name="type" id="type" value="approvisionnement">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <label for="logo">Justificatif d'approvisionnement</label>
                            <div class="image-upload">
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*,.pdf">
                                <div class="image-uploads">
                                    <!-- Aperçu de l'image ou du fichier PDF -->
                                    <div id="file-preview" style="margin-top: 10px;">
                                        <img id="preview-image" src="{{asset('assets/img/icons/upload.svg')}}" alt="Aperçu" style="max-width: 200px; display: none;">
                                        <a id="preview-pdf" href="#" target="_blank" style="display: none; font-size: 16px; color: blue;">Voir le fichier PDF</a>
                                    </div>
                                    <h4>Ajouter le justificatif d'approvisionnement</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
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
    <script src="{{ asset('js/backend/listcompte.js')}}"></script>
    <script>
        document.getElementById('upload-item-btn').addEventListener('click', function () {
            var myModal = new bootstrap.Modal(document.getElementById('paymentModal'), {
                keyboard: false
            });
            myModal.show();
        });
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
