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
                        <h4>Liste des Commandes</h4>
                        <h6>Visualiser vos Commandes</h6>
                    </div>
                </div>

                @if (session('success'))
                <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                    <div class="card-body">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="nav-item">
                                <a class="nav-link {{ session('active_tab', 'non-paye') == 'non-paye' ? 'active' : '' }}" href="#non-paye" data-bs-toggle="tab">Non Payé</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ session('active_tab') == 'paye' ? 'active' : '' }}" href="#paye" data-bs-toggle="tab">Payé</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ session('active_tab') == 'livre' ? 'active' : '' }}" href="#livre" data-bs-toggle="tab">Livré</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Onglet Non Payé -->
                            <div class="tab-pane {{ session('active_tab', 'non-paye') == 'non-paye' ? 'show active' : '' }}" id="non-paye">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="page-header">
                                            <div class="page-title" id="titre">
                                                <h4>Commande en Attente de paiements</h4>
                                                <h6>Liste Commande en Attente de paiements</h6>
                                            </div>
                                        </div>
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

                                                        <th>Type de Vente</th>
                                                        <th>Nom du Client</th>
                                                        <th>Total Vente</th>
                                                        <th>Total Remise</th>
                                                        <th>Net à Payer</th>
                                                        <th>Date</th>
                                                        <th>Facture Statut</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($commandes as $commande)
                                                        @if($commande->etat == 1)
                                                        <tr>

                                                            <td>{{ implode(', ', json_decode($commande->type_vente, true)) }}</td>
                                                            <td>{{ $commande->NomPrenomClient }}</td>
                                                            <td>{{ $commande->TotalVente }}</td>
                                                            <td>{{ $commande->TotalRemise }}</td>
                                                            <td>{{ $commande->Net_a_payer }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</td>
                                                            <td><span class="text-secondary">Non payé</span></td>
                                                            <td>
                                                                @can('edit commandes')
                                                                <a class="me-3 edit-item-btn"  href="javascript:void(0);" id="edit-item-btn">
                                                                    <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="img">
                                                                </a>
                                                                @endcan
                                                                @can('delete commandes')
                                                                <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                                                                    <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="img">
                                                                </a>
                                                                @endcan
                                                                <a class="me-3 pay-item-btn" href="{{ route('detailscommande', $commande->id) }}" id="pay-item-btn">
                                                                    <img src="{{ asset('assets/img/icons/dash2.svg') }}" alt="img">
                                                                </a>


                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Onglet Payé -->
                            <div class="tab-pane {{ session('active_tab') == 'paye' ? 'show active' : '' }}" id="paye">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="page-header">
                                            <div class="page-title" id="titre">
                                                <h4>Commandes en Attente de livraison</h4>
                                                <h6>Liste commande payé</h6>
                                            </div>
                                        </div>
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

                                                        <th>Type de Vente</th>
                                                        <th>Nom du Client</th>
                                                        <th>Total Vente</th>
                                                        <th>Total Remise</th>
                                                        <th>Net à Payer</th>
                                                        <th>Date</th>
                                                        <th>Facture Statut</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($commandes as $commande)
                                                        @if($commande->etat == 2)
                                                        <tr data-id="{{ $commande->id}}">

                                                            <td>{{ implode(', ', json_decode($commande->type_vente, true)) }}</td>
                                                            <td>{{ $commande->NomPrenomClient }}</td>
                                                            <td>{{ $commande->TotalVente }}</td>
                                                            <td>{{ $commande->TotalRemise }}</td>
                                                            <td>{{ $commande->Net_a_payer }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</td>
                                                            <td><span class="text-secondary">Non livré</span></td>
                                                            <td>
                                                                @can('delete commandes')
                                                                <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                                                                    <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="img">
                                                                </a>
                                                                @endcan
                                                                @can('edit commandes')
                                                                <a class="me-3 upload-item-btn" href="javascript:void(0);">
                                                                    <img src="{{ asset('assets/img/icons/upload.svg')}}" alt="upload" title="Finaliser">
                                                                </a>
                                                                <a class="btn btn-success btn-sm invoicebutton mt-1" title="Facture details" href="{{ route('invoicepay', $commande->id) }}"><i class="fa fa-file-invoice"></i>Facture</a>
                                                                @endcan
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Onglet Livré -->
                            <div class="tab-pane {{ session('active_tab') == 'livre' ? 'show active' : '' }}" id="livre">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="page-header">
                                            <div class="page-title" id="titre">
                                                <h4>Commandes payés et livrés</h4>
                                                <h6>Liste Commandes livrés</h6>
                                            </div>
                                        </div>
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

                                                        <th>Type de Vente</th>
                                                        <th>Nom du Client</th>
                                                        <th>Total Vente</th>
                                                        <th>Total Remise</th>
                                                        <th>Net à Payer</th>
                                                        <th>Date</th>
                                                        <th>Facture Statut</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($commandes as $commande)
                                                        @if($commande->etat == 3)
                                                        <tr>

                                                            <td>{{ implode(', ', json_decode($commande->type_vente, true)) }}</td>
                                                            <td>{{ $commande->NomPrenomClient }}</td>
                                                            <td>{{ $commande->TotalVente }}</td>
                                                            <td>{{ $commande->TotalRemise }}</td>
                                                            <td>{{ $commande->Net_a_payer }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</td>
                                                            <td><span ><span class="text-secondary">Payé et Livré</span></span></td>
                                                            <td>
                                                                @can('delete commandes')
                                                                <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                                                                    <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="img">
                                                                </a>
                                                                @endcan

                                                                <a class="me-3 view-item-btn" href="{{ asset('storage/' . $commande->document) }}" target="_blank">
                                                                    <img src="{{ asset('assets/img/icons/eye1.svg') }}" alt="Voir le fichier">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Importer la facture signé</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire de paiement -->
                    <form action="{{ route('uploadInvoice')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="commande_id" id="commande_id">
                        <div class="form-group">
                            <label for="logo">Bon de Sortie signé</label>
                            <div class="image-upload">
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*,.pdf">
                                <div class="image-uploads">
                                    <!-- Aperçu de l'image ou du fichier PDF -->
                                    <div id="file-preview" style="margin-top: 10px;">
                                        <img id="preview-image" src="{{asset('assets/img/icons/upload.svg')}}" alt="Aperçu" style="max-width: 200px; display: none;">
                                        <a id="preview-pdf" href="#" target="_blank" style="display: none; font-size: 16px; color: blue;">Voir le fichier PDF</a>
                                    </div>
                                    <h4>Ajouter le Bon de Sortie Signé</h4>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
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

    <script>
        document.querySelectorAll('.upload-item-btn').forEach(function(button) {
            button.addEventListener('click', function () {
                var commande_id = this.closest('tr').getAttribute('data-id'); // Récupère le data-id de la ligne

                // Assigner l'ID de commande au champ caché dans le modal
                document.getElementById('commande_id').value = commande_id;

                var myModal = new bootstrap.Modal(document.getElementById('paymentModal'), {
                    keyboard: false
                });
                myModal.show();
            });
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
