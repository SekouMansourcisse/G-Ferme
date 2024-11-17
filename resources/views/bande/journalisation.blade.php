<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="page-title" id="titre">
                    <h4>Journalisations</h4>
                    <h6>Liste Journalisations</h6>
                </div>
                @can('create operation sur bande')
                    @if ($bande->etat == 1)
                        <div class="page-btn">
                            <button id="addJournalisationBtn" class="btn btn-added"><img
                                    src="{{ asset('assets/img/icons/plus.svg') }}" alt="img">Démarrer la journalisation
                            </button>
                        </div>
                    @endif
                @endcan


            </div>
            <div class="card col-md-12">

                <div class="card-body" id="journalisationList">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-path">
                                <a class="btn btn-filter" id="filter_search">
                                    <img src="{{ asset('assets/img/icons/filter.svg') }}" alt="img">
                                    <span><img src="{{ asset('assets/img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                            </div>
                            <div class="search-input">
                                <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}"
                                        alt="img"></a>
                            </div>
                        </div>
                        <div class="wordset">
                            <ul>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                            src="{{ asset('assets/img/icons/pdf.svg') }}" alt="img"></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                            src="{{ asset('assets/img/icons/excel.svg') }}" alt="img"></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                            src="{{ asset('assets/img/icons/printer.svg') }}" alt="img"></a>
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
                                        <input type="text" class="datetimepicker cal-icon" placeholder="Choose Date">
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
                                                src="{{ asset('assets/img/icons/search-whites.svg') }}"
                                                alt="img"></a>
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
                                    <th>Âge</th>
                                    <th>Poulailler</th>
                                    <th>S.Tri</th>
                                    <th>S.Malade</th>
                                    <th>S.Mort</th>
                                    <th>R.Maladie</th>
                                    <th>Résumé</th>
                                    @can('edit operation sur bande')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($journalisations as $journalisation)
                                    @php
                                        $poulaillers = explode(',', $journalisation->Poulailler);
                                    @endphp
                                    @foreach ($poulaillers as $index => $poulaillerEntry)
                                        @php
                                            [$id, $tri, $malade, $mort, $retour_maladie] = explode(
                                                '*',
                                                $poulaillerEntry,
                                            );
                                            $poulaillerName = App\Models\Poulailler::find($id)->Denomination;
                                        @endphp
                                        <tr data-id="{{ $journalisation->id }}" data-date="{{ $journalisation->Date }}" data-age="{{ $journalisation->Age }}" data-commentaire="{{ $journalisation->commentaire }}" data-poulailler="{{ $journalisation->Poulailler }}">
                                            @if ($index == 0)
                                                <td rowspan="{{ count($poulaillers) }}">{{ $journalisation->Date }}
                                                </td>
                                                <td rowspan="{{ count($poulaillers) }}">{{ $journalisation->Age }}
                                                </td>
                                            @endif
                                            <td>{{ $poulaillerName }}</td>
                                            <td>{{ $tri }}</td>
                                            <td>{{ $malade }}</td>
                                            <td>{{ $mort }}</td>
                                            <td>{{ $retour_maladie }}</td>
                                            @if ($index == 0)
                                                <td rowspan="{{ count($poulaillers) }}">
                                                    {{ $journalisation->commentaire }}</td>
                                                @can('edit operation sur bande')
                                                    <td rowspan="{{ count($poulaillers) }}">

                                                        <a class="me-3 edit-journal-btn" href="javascript:void(0);">
                                                            <img src="{{ asset('assets/img/icons/edit.svg') }}"
                                                                alt="edit">
                                                        </a>


                                                        @can('delete operation sur bande')
                                                        <a class="me-3 delete-item-btn" href="javascript:void(0);" onclick="confirmDelete3({{ $journalisation->id }})">
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
                <div class="card-body" id="addJournalisationForm" style="display:none;">

                    <form action="{{ route('journalisations.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="bande_id" value="{{ $bande->id }}">
                            <div class="col-lg-6 col-sm-8 col-12">
                                <div class="form-group">
                                    <label for="Date" class="form-label">Date journalisation</label>
                                    <input type="date" class="form-control" id="Date" name="Date"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-8 col-12">
                                <div class="form-group">
                                    <label for="Age" class="form-label">Âge en Jours</label>
                                    <input type="text" class="form-control" id="Age" name="Age"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="Poulailler" class="form-label">Poulaillers</label>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Poulailler</th>
                                            <th>Tri</th>
                                            <th>Malade</th>
                                            <th>Mort</th>
                                            <th>Retour malade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (explode(',', $bande->poulailler) as $poulailler)
                                            @php
                                                [$id, $cheptel] = explode('*', $poulailler);
                                                $poulailler_n = App\Models\Poulailler::find($id)->Denomination;
                                            @endphp
                                            <tr>
                                                <td>{{ $poulailler_n }} ({{ $cheptel }} M)</td>
                                                <td>
                                                    <input type="text" class="form-control" name="Tri[]"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="Malade[]"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="Mort[]"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="Retour_malade[]"
                                                        required>
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
                                <button type="button" id="cancelAddJournalisationBtn" class="btn btn-secondary"
                                    onclick="window.history.back();">Annuler</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editJournalisationModal" tabindex="-1" role="dialog"
    aria-labelledby="editJournalisationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJournalisationModalLabel">Éditer Journalisation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editJournalisationForm" method="POST">
                    @csrf

                    <input type="hidden" name="bande_id" id="editBandeId" value="{{ $bande->id }}">
                    <input type="hidden" name="journal_id" id="journal_id">
                    <div class="row">
                        <input type="hidden" name="bande_id" value="{{ $bande->id }}">
                        <div class="col-lg-6 col-sm-8 col-12">
                            <div class="form-group">
                                <label for="Date" class="form-label">Date journalisation</label>
                                <input type="date" class="form-control" id="editDate" name="Date" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-8 col-12">
                            <div class="form-group">
                                <label for="Age" class="form-label">Âge en Jours</label>
                                <input type="text" class="form-control" id="editAge" name="Age" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="Poulailler" class="form-label">Poulaillers</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Poulailler</th>
                                        <th>Tri</th>
                                        <th>Malade</th>
                                        <th>Mort</th>
                                        <th>Retour malade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (explode(',', $bande->poulailler) as $poulailler)
                                        @php
                                            [$id, $cheptel] = explode('*', $poulailler);
                                            $poulailler_n = App\Models\Poulailler::find($id)->Denomination;
                                        @endphp
                                        <tr>
                                            <td>{{ $poulailler_n }} ({{ $cheptel }} M)</td>
                                            <td>
                                                <input type="text" class="form-control" name="Tri[]" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="Malade[]" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="Mort[]" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="Retour_malade[]"
                                                    required>
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
                                <textarea class="form-control" id="editResume" name="Resume" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="editJournalisationModal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('js/backend/journal.js')}}"></script>

