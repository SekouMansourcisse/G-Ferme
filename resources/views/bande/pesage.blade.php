<style>
    .profileupload {
        display: flex;
        align-items: center;
    }
    .profileupload i {
        font-size: 24px; /* Ajuster la taille de l'icône */
        cursor: pointer;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="page-title" id="titre">
                    <h4>Pesages</h4>
                    <h6>Liste Pesages</h6>
                </div>
                @can('create operation sur bande')
                @if ($bande->etat==1)
                <div class="page-btn">
                    <button id="addPesageLBtn" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Démarrer le Pesage
                        </button>
                </div>
                @endif
                @endcan


            </div>
            <div class="card">

                <div class="card-body" id="PesageLList">
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
                                    <th>Date</th>
                                    <th>Poulailler</th>
                                    <th>Poids moyen (g)</th>
                                    <th>Nbre.Ind</th>
                                    <th>Poids Total (g)</th>
                                    <th>Photo Sujet</th>
                                    @can('edit operation sur bande')
                                    <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($PesagesL as $Pesage)
                                    @php
                                        $poulaillerData = explode(',', $Pesage->poulailler);
                                    @endphp

                                    @foreach($poulaillerData as $poulaillerInfo)
                                        @php
                                            list($poulaillerId, $poidsMoyen, $cheptel, $poidsTotal, $photo) = explode('*', $poulaillerInfo);
                                            $poulaillerName = App\Models\Poulailler::find($poulaillerId)->Denomination;
                                        @endphp
                                        <tr data-id="{{ $Pesage->id }}" data-date="{{ $Pesage->date }}" data-semaine="{{ $Pesage->semaine_p }}" data-poulailler="{{ $Pesage->poulailler }}">

                                            @if ($loop->first)
                                                <td rowspan="{{ count($poulaillerData) }}">{{ $Pesage->date }}</td>
                                            @endif
                                            <td>{{ $poulaillerName }}</td>
                                            <td>{{ $poidsMoyen }}g</td>
                                            <td>{{ number_format($cheptel, 0, '', ' ') }}</td>
                                            <td>{{ number_format( $poidsTotal, 0, '', ' ') }}g</td>
                                            <td><img src="{{ asset('storage/' . $photo) }}" alt="Photo Sujet" style="width: 100px;"></td>
                                            @if ($loop->first)
                                                @can('edit operation sur bande')
                                                    <td rowspan="{{ count($poulaillerData) }}">

                                                        <a class="me-3 edit-item-btn" href="javascript:void(0);" id="edit-item-btn">
                                                            <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="edit" >
                                                        </a>

                                                        @can('delete operation sur bande')
                                                        <a class="me-3 delete-item-btn" href="javascript:void(0);" onclick="confirmDelete4({{ $Pesage->id }})">
                                                            <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="Delete">
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
             <div class="card-body" id="addPesageLForm" style="display:none;">

                <form action="{{ route('pesages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="bande_id" value="{{ $bande->id }}">
                        <div class="col-lg-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="Date" class="form-label">Date Pesage</label>
                                <input type="date" class="form-control" id="Date" name="Date" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="SemaineP" class="form-label">Semaine Pesage</label>
                                <input type="text" class="form-control" id="SemaineP" name="SemaineP" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Poulailler</th>
                                        <th>Poids moyen (g)</th>
                                        <th>Photo Sujet</th>
                                        <th>Aperçu</th>
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
                                                <input type="text" class="form-control" name="poids_moyen[]" required>
                                            </td>
                                            <td>
                                                <div class="profileupload">
                                                    <input type="file" id="photo_{{ $id }}" name="photo[]" style="display: none;" onchange="previewImage(this, 'preview_{{ $id }}')">
                                                    <a href="javascript:void(0);" onclick="document.getElementById('photo_{{ $id }}').click();"><i class="fas fa-camera"></i></a>
                                                </div>
                                            </td>
                                            <td>
                                                <img id="preview_{{ $id }}" src="#" alt="Aperçu de l'image" style="display: none; width: 100px;">
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
                            <button type="button" id="cancelPesageBtn" class="btn btn-secondary" onclick="window.history.back();">Annuler</button>
                        </div>
                    </div>
                </form>



             </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal d'édition -->
<div class="modal fade" id="editPesageModal" tabindex="-1" aria-labelledby="editPesageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPesageModalLabel">Modifier le Pesage</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPesageForm"  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="pesage_id" id="pesage_id">
                    <div class="mb-3">
                        <label for="editDate" class="form-label">Date Pesage</label>
                        <input type="date" class="form-control" id="editDate" name="Date" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSemaineP" class="form-label">Semaine Pesage</label>
                        <input type="text" class="form-control" id="editSemaineP" name="SemaineP" required>
                    </div>
                    <div class="mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Poulailler</th>
                                    <th>Poids moyen (g)</th>
                                    <th>Photo Sujet</th>
                                    <th>Aperçu</th>
                                </tr>
                            </thead>
                            <tbody id="editPesageTableBody">
                                <!-- Les lignes de poulailler seront ajoutées ici -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="{{ asset('assets/js/moment.min.js')}}"></script>
<script src="{{ asset('js/backend/pesage.js')}}"></script>
<script src="{{ asset('js/backend/peser.js')}}"></script>


