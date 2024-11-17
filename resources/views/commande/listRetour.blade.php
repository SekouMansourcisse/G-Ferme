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
                        <h4>Liste des Retours ventes</h4>
                        <h6>visualiser vos  Retours ventes </h6>
                    </div>
                    @can('create retour_ventes_commande')
                    <div class="page-btn">
                        <a href="{{url('RetourV')}}" class="btn btn-added"><img src="{{asset('assets/img/icons/plus.svg')}}" alt="img">Enregister
                           une Retour Vente</a>
                    </div>
                    @endcan

                </div>
                @if(session('success'))
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
                                        <img src="{{asset('assets/img/icons/filter.svg')}}" alt="img">
                                        <span><img src="{{asset('assets/img/icons/closes.svg')}}" alt="img"></span>
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
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" href="{{ url('ListRetourexportPdf')}}" title="pdf"><img
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

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Commande</th>
                                        <th>Date</th>
                                        <th>Type de Vente</th>

                                        <th>Quantités Retournées</th>
                                        <th>Montant Retour</th>
                                        <th>Type Retour</th>
                                        @can('edit retour_ventes_commande')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($operationsRetour as $key => $group)
                                        @php
                                            list($commandeId, $typeRetour) = explode('-', $key);

                                            // Calculer les quantités et les montants agrégés pour le groupe
                                            $totalQteFormatted = '';
                                            $totalMontant = 0;
                                            $dates = [];
                                            $typesVente = [];
                                            $numerosVente = [];
                                            $id=[];

                                            foreach($group as $operationRetour) {
                                                // Ajouter la date, le type de vente et le numéro de vente à la liste
                                                $dates[] = $operationRetour->date_op;
                                                $typesVente[] = $operationRetour->TypeVenteR;
                                                $numerosVente[] = $operationRetour->numero_vente;
                                                $id[]=$operationRetour->commande_id;

                                                // Additionner les montants
                                                $totalMontant += $operationRetour->Montant_R;

                                                // Formater et additionner les quantités retournées
                                                $qteRetour = explode(';', $operationRetour->qteR);
                                                foreach ($qteRetour as $item) {
                                                    list($elementId, $qte) = explode('*', $item);

                                                    switch ($operationRetour->TypeVenteR) {
                                                        case 'vente-oeuf':
                                                            $categorie = App\Models\CategorieOeuf::find($elementId);
                                                            $label = $categorie ? $categorie->Denomination : 'Inconnu';
                                                            $totalQteFormatted .= "{$label}: ({$qte} plateaux)<br>";
                                                            break;

                                                        case 'vente-sujet':
                                                            $bande = App\Models\Bande::find($elementId);
                                                            $label = $bande ? $bande->nom_bande : 'Inconnu';
                                                            $totalQteFormatted .= "{$label}: ({$qte} Ind)<br>";
                                                            break;

                                                        case 'vente-autre':
                                                            $produit = App\Models\Produit::find($elementId);
                                                            $label = $produit ? $produit->Denomination : 'Inconnu';
                                                            $totalQteFormatted .= "{$label}: ({$qte}kg)<br>";
                                                            break;
                                                    }
                                                }
                                            }

                                            // Supprimer les doublons dans les dates, types de vente, et numéros de vente
                                            $uniqueDates = implode(', ', array_unique($dates));
                                            $uniqueTypesVente = implode(', ', array_unique($typesVente));

                                            $uniquecommandeId = implode(', ', array_unique($id));
                                        @endphp
                                        <tr>
                                            <td>{{ $uniquecommandeId}}</td>
                                            <td>{{ $uniqueDates }}</td>
                                            <td>{{ $uniqueTypesVente }}</td>

                                            <td>{!! $totalQteFormatted !!}</td>
                                            <td>{{ $totalMontant }}</td>
                                            <td>{{ $typeRetour }}</td>
                                            @can('edit retour_ventes_commande')
                                            <td>

                                                <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                    <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="img">
                                                </a>

                                                @can('delete retour_ventes_commande')
                                                <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                                                    <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="img">
                                                </a>
                                                @endcan



                                                    @if ($typeRetour=="Remboursement")

                                                    <a class="me-3 pay-item-btn" href="{{ route('Remboursement', $commandeId) }}" id="pay-item-btn">
                                                        <img src="{{ asset('assets/img/icons/dash2.svg') }}" alt="img"title="Rembourser" >
                                                    </a>
                                                @endif
                                                @if ($typeRetour=="Remplacement")
                                                    <a class="btn btn-success btn-sm invoicebutton mt-1" title="Facture details" href="{{ route('Remplacement', $commandeId) }}"><i class="fa fa-file-invoice"></i></a>
                                                @endif



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


    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{ asset('js/backend/listproduit.js')}}"></script>
</body>
