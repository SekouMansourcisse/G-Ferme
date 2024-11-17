
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="page-title" id="titre">
                    <h4>Ramassages de Litières</h4>
                    <h6>Liste Ramassages</h6>
                </div>
                @can('create operation sur bande')
                @if ($bande->etat==1)
                <div class="page-btn">
                    <button id="addRamassageLBtn" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Démarrer la Ramassage
                        </button>
                </div>
                @endif
                @endcan

            </div>
            <div class="card">

                <div class="card-body" id="RamassageLList">
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
                            <div class="row">
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter User Name">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter Email">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" class="datetimepicker cal-icon"
                                            placeholder="Choose Date">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <select class="select">
                                            <option>Disable</option>
                                            <option>Enable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                                    <div class="form-group">
                                        <a class="btn btn-filters ms-auto"><img
                                                src="{{ asset('assets/img/icons/search-whites.svg')}}" alt="img"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date Ramassage</th>
                                    <th>Poulailler</th>
                                    <th>Qte Ramassé (Kg)</th>
                                    <th>Qte Total (Kg)</th>
                                    @can('edit operation sur bande')
                                    <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($RamassagesL as $Ramassage)
                                    @php
                                        $poulaillerData = explode(',', $Ramassage->poulailler);
                                        $totalQuantite = $Ramassage->qte_ramasser;
                                    @endphp

                                    @foreach($poulaillerData as $poulaillerInfo)
                                        @php
                                            list($poulaillerId, $qteRamassee) = explode('*', $poulaillerInfo);
                                            $poulaillerName = App\Models\Poulailler::find($poulaillerId)->Denomination;
                                        @endphp
                                        <tr>
                                            @if ($loop->first)
                                                <td rowspan="{{ count($poulaillerData) }}">{{ $Ramassage->date }}</td>
                                            @endif
                                            <td>{{ $poulaillerName }}</td>
                                            <td>{{ $qteRamassee }}</td>
                                            @if ($loop->first)
                                                <td rowspan="{{ count($poulaillerData) }}">{{ $totalQuantite }}</td>
                                                @can('edit operation sur bande')
                                                <td rowspan="{{ count($poulaillerData) }}">

                                                    <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                        <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="edit" >
                                                    </a>

                                                    @can('delete operation sur bande')
                                                    <a class="me-3 delete-item-btn" href="javascript:void(0);" id="delete-item-btn">
                                                        <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="delete" >
                                                    </a>
                                                    @endcan
                                                </td>
                                                @endcan
                                            @endif
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
            <div class="card">
             <div class="card-body" id="addRamassageLForm" style="display:none;">

                <form action="{{ route('ramassagelitières.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="bande_id" value="{{ $bande->id }}">
                        <div class="col-lg-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="Date" class="form-label">Date Ramassage</label>
                                <input type="date" class="form-control" id="Date" name="Date" required>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Poulailler</th>
                                        <th>quantité ramassé(Kg)</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(explode(',', $bande->poulailler) as $poulailler)
                                        @php
                                            list($id, $cheptel) = explode('*', $poulailler);
                                            $poulailler_n = App\Models\Poulailler::find($id)->Denomination;
                                        @endphp
                                        <tr>
                                            <td>{{ $poulailler_n }} ({{ $cheptel }} M)</td>
                                            <td>
                                                <input type="text" class="form-control" name="qte[]" required>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="Resume" class="form-label">Résumé incident/évènement</label>
                                <textarea class="form-control" id="Resume" name="Resume" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <button type="submit" class="btn btn-primary me-2">Enregistrer</button>
                            <button type="button" id="cancelAddRamassageLBtn" class="btn btn-secondary" onclick="window.history.back();">Annuler</button>
                        </div>
                    </div>
                </form>

             </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/moment.min.js')}}"></script>
<script>
    document.getElementById('addRamassageLBtn').addEventListener('click', function() {
        // Affiche le formulaire et cache la liste
        document.getElementById('RamassageLList').style.display = 'none';
        document.getElementById('addRamassageLForm').style.display = 'block';
        document.getElementById('addRamassageLBtn').style.display = 'none';
        // Met à jour le texte du titre
        var titreH4 = document.querySelector('#titre h4');
        var titreH6 = document.querySelector('#titre h6');
        titreH4.textContent = 'Ramassages';
        titreH6.textContent = 'Ajouter une nouvelle Ramassage';


    });

    document.getElementById('cancelAddRamassageLBtn').addEventListener('click', function() {
        // Cache le formulaire et affiche la liste
        document.getElementById('addRamassageLForm').style.display = 'none';
        document.getElementById('RamassageLList').style.display = 'block';

        // Réinitialise le texte du titre
        var titreH4 = document.querySelector('#titre h4');
        var titreH6 = document.querySelector('#titre h6');
        titreH4.textContent = 'Ramassages';
        titreH6.textContent = 'Liste des Ramassages';

            });
</script>

