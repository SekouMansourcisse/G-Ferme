
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="page-title" id="titre">
                    <h4>alimentations</h4>
                    <h6>Liste alimentations</h6>
                </div>
                @can('create operation sur bande')
                @if ($bande->etat == 1)
                <div class="page-btn">
                    <button id="addalimentationBtn" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg')}}" alt="img">Démarrer la alimentation
                        </button>
                </div>
                @endif
                @endcan


            </div>
            <div class="card">
                <div class="card-body" id="alimentationList">
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
                                    <th>Âge</th>
                                    <th>Poulailler</th>
                                    <th>Produit</th>
                                    <th>Qte consommée</th>
                                    <th>Commentaire</th>
                                    @can('edit operation sur bande')
                                    <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alimentations as $alimentation)
                                    @php
                                        $poulaillerData = explode(',', $alimentation->Poulailler);
                                    @endphp

                                    @foreach($poulaillerData as $poulaillerInfo)
                                        @php
                                            list($poulaillerId, $produitId, $qteConsommee) = explode('*', $poulaillerInfo);
                                            $poulaillerName = App\Models\Poulailler::find($poulaillerId)->Denomination;
                                            $produitName = App\Models\Produit::find($produitId)->Denomination;
                                        @endphp
                                        <tr data-id="{{$alimentation->id}}">
                                            @if ($loop->first)
                                                <td rowspan="{{ count($poulaillerData) }}">{{ $alimentation->created_at->format('Y-m-d') }}</td>
                                                <td rowspan="{{ count($poulaillerData) }}">{{ $alimentation->Age_en_jour }}jours</td>
                                            @endif
                                            <td>{{ $poulaillerName }}</td>
                                            <td>{{ $produitName }}</td>
                                            <td>{{ number_format($qteConsommee, 0, '', ' ') }} Kg</td>
                                            @if ($loop->first)
                                                <td rowspan="{{ count($poulaillerData) }}">{{ $alimentation->commentaire }}</td>
                                                @can('edit operation sur bande')
                                                <td rowspan="{{ count($poulaillerData) }}">

                                                    <a class="me-3 edit-aliment-btn" href="javascript:void(0);" id="edit-aliment-btn">
                                                        <img src="{{ asset('assets/img/icons/edit.svg')}}" alt="edit" >
                                                    </a>

                                                    @can('delete operation sur bande')
                                                    <a class="me-3 delete-item-btn" href="javascript:void(0);" onclick="confirmDelete2({{ $alimentation->id }})">
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
             <div class="card-body" id="addalimentationForm" style="display:none;">

                <form action="{{ route('alimentations.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="bande_id" value="{{ $bande->id }}" >
                        <div class="col-lg-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="Date" class="form-label">Date alimentation</label>
                                <input type="date" class="form-control" id="Date" name="Date" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="Age" class="form-label">Âge</label>
                                <input type="text" class="form-control" id="Age" name="Age_en_jour" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#produitModal">
                                Ajouter la liste des produits
                            </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Dénomination produit</th>
                                            <th rowspan="2">Quantité stock</th>
                                            <th colspan="{{ count(explode(',', $bande->poulailler)) }}">Quantité utilisée par poulailler</th>
                                            <th rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            @foreach(explode(',', $bande->poulailler) as $poulailler)
                                                @php
                                                    list($id, $cheptel) = explode('*', $poulailler);
                                                    $poulailler_n = App\Models\Poulailler::find($id)->Denomination;
                                                @endphp
                                                <th>{{ $poulailler_n }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="produitTableBody">
                                        <!-- Les produits sélectionnés seront ajoutés ici -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <br>
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
                            <button type="button" id="cancelAddalimentationBtn" class="btn btn-secondary" onclick="window.history.back();">Annuler</button>
                        </div>
                    </div>
                </form>

             </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal pour la liste des produits -->
<div class="modal fade" id="produitModal" tabindex="-1" aria-labelledby="produitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="produitModalLabel">Liste des produits</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Produit</th>
                            <th>Quantité stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produits as $produit)
                            <tr>
                                <td>
                                    <input type="checkbox" class="produit-checkbox" data-id="{{ $produit->id }}" data-nom="{{ $produit->Denomination }}" data-stock="{{ $produit->qte_stock }}">
                                </td>
                                <td>{{ $produit->Denomination }}</td>
                                <td>{{ $produit->qte_stock }}Kg</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="ajouterProduitsBtn">Ajouter</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal pour l'édition de l'alimentation -->
<div class="modal fade" id="editAlimentationModal" tabindex="-1" role="dialog" aria-labelledby="editAlimentationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAlimentationModalLabel">Éditer Alimentation</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAlimentationForm" method="POST" >
                    @csrf

                    <input type="hidden" name="bande_id" id="editBandeId" value="{{$bande->id}}">
                    <input type="hidden" name="aliment_id" id="aliment_id">
                    <div class="form-group">
                        <label for="editDate">Date</label>
                        <input type="date" class="form-control" id="editDate" name="Date" required>
                    </div>
                    <div class="form-group">
                        <label for="editAge">Âge</label>
                        <input type="number" class="form-control" id="editAge" name="Age" required>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Poulailler</th>
                                    <th>Produit</th>
                                    <th>quantité consommée</th>
                                </tr>
                            </thead>
                            <tbody id="editProduitTableBody">
                                <!-- Les produits seront ajoutés ici -->
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <label for="editResume">Commentaire</label>
                        <textarea class="form-control" id="editResume" name="Resume" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('assets/version/bootstrap.min.js')}}"></script>

<script>
    document.getElementById('addalimentationBtn').addEventListener('click', function() {
        // Affiche le formulaire et cache la liste
        document.getElementById('alimentationList').style.display = 'none';
        document.getElementById('addalimentationForm').style.display = 'block';
        document.getElementById('addalimentationBtn').style.display = 'none';
        // Met à jour le texte du titre
        var titreH4 = document.querySelector('#titre h4');
        var titreH6 = document.querySelector('#titre h6');
        titreH4.textContent = 'alimentations';
        titreH6.textContent = 'Ajouter une nouvelle alimentation';


    });

    document.getElementById('cancelAddalimentationBtn').addEventListener('click', function() {
        // Cache le formulaire et affiche la liste
        document.getElementById('addalimentationForm').style.display = 'none';
        document.getElementById('alimentationList').style.display = 'block';

        // Réinitialise le texte du titre
        var titreH4 = document.querySelector('#titre h4');
        var titreH6 = document.querySelector('#titre h6');
        titreH4.textContent = 'alimentations';
        titreH6.textContent = 'Liste des alimentations';

            });
</script>
<script>
$(document).ready(function() {
    $('#ajouterProduitsBtn').click(function() {
        $('.produit-checkbox:checked').each(function() {
            var produitId = $(this).data('id');
            var produitNom = $(this).data('nom');
            var produitStock = $(this).data('stock');

            var row = '<tr>' +
                '<td>' + produitNom + '</td>' +
                '<td>' + produitStock + ' Kg</td>';

            @foreach(explode(',', $bande->poulailler) as $poulailler)
                @php
                    list($id, $cheptel) = explode('*', $poulailler);
                    $poulailler_n = App\Models\Poulailler::find($id)->Denomination;
                @endphp
                row += '<td><input type="text" class="form-control" name="qte_consomme[' + produitId + '][{{ $id }}]" required></td>';
            @endforeach

            row += '<td><button type="button" class="btn btn-danger btn-sm remove-product-btn">X</button></td>' +
                '</tr>';

            $('#produitTableBody').append(row);
        });

        $('#produitModal').modal('hide');
    });

    $(document).on('click', '.remove-product-btn', function() {
        $(this).closest('tr').remove();
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Événement de clic pour le bouton d'édition
    document.querySelectorAll('.edit-aliment-btn').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr'); // Obtenir la ligne parente
            const date = row.cells[0].innerText;
            const age = row.cells[1].innerText.replace(' jours', '');
            const commentaire = row.cells[5].innerText; // Index 5 pour le commentaire
            const bandeId = document.querySelector('input[name="bande_id"]').value; // ID de la bande

            // Mettre à jour le modal avec les valeurs de la ligne
            document.getElementById('aliment_id').value = row.getAttribute('data-id');
            document.getElementById('editDate').value = date;
            document.getElementById('editAge').value = age;
            document.getElementById('editResume').value = commentaire;
            document.getElementById('editBandeId').value = bandeId;

            // Effacer le tableau des produits existant dans le modal
            const editProduitTableBody = document.getElementById('editProduitTableBody');
            editProduitTableBody.innerHTML = ''; // Vider le tableau avant de remplir

            // Récupérer les données des alimentations en PHP
            const poulaillerData = <?= json_encode($alimentations); ?>;

            // Remplir le tableau des produits à partir de la ligne sélectionnée
            poulaillerData.forEach(alimentation => {
                const poulaillerInfo = alimentation.Poulailler.split(',');

                // Vérifier si la date et l'âge correspondent
                if (date === alimentation.date) {
                    poulaillerInfo.forEach(info => {
                        const [poulaillerId, produitId, qteConsommee] = info.split('*');

                        // Récupérer les noms des poulaillers et des produits
                        const poulaillerName = <?= json_encode(App\Models\Poulailler::all()->keyBy('id')->toArray()); ?>[poulaillerId]?.Denomination || 'Inconnu';
                        const produitName = <?= json_encode(App\Models\Produit::all()->keyBy('id')->toArray()); ?>[produitId]?.Denomination || 'Inconnu';

                        // Créer une nouvelle ligne dans le tableau
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${poulaillerName}</td>
                            <td>${produitName}</td>

                            <td>
                                <input type="number" name="qte_consomme[${produitId}]" class="form-control" value="${qteConsommee}" required>
                            </td>
                        `;
                        editProduitTableBody.appendChild(row);
                    });
                }
            });

            // Ouvrir le modal
            $('#editAlimentationModal').modal('show');
        });
    });
});

    // Gestion de la soumission du formulaire d'édition de aliment
    $('#editAlimentationForm').submit(function(e) {
        e.preventDefault();
        const alimentId = document.getElementById('aliment_id').value;
        const formData = $(this).serialize() + '&_method=PUT';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/alimentations/' + alimentId,
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#editalimentModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'aliment modifiée avec succès.',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-cancel',
                    },
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errorMessage = 'Erreur lors de la modification.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: errorMessage,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
function confirmDelete2(id) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible!",
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-cancel',
        },
        confirmButtonText: 'Oui, supprimer !'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/alimentations/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Supprimé !',
                            'aliments supprimée.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur !', data.message, 'error');
                    }
                })
                .catch(error => Swal.fire('Erreur !', 'Une erreur s\'est produite lors de la suppression.',
                    'error'));
        }
    });
}
</script>

