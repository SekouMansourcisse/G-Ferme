<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
    <title>Liste de retour ventes</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('storage/' . ($settings->logo_facture ?? 'default_logo.png')) }}" alt="Logo Entreprise">
        <h2>{{ $settings->nomFerme }}</h2>
        <p>
            {{ $settings->adresse }}<br>
            Téléphone : {{ $settings->phone_ferme }}<br>
            Email : {{ $settings->email_ferme ?? 'Non spécifié' }}
        </p>
    </div>

    <h3>Liste de retour ventes</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type de Vente</th>
                    <th>Numéro de Vente</th>
                    <th>Quantités Retournées</th>
                    <th>Montant Retour</th>
                    <th>Beneficiaire</th>
                </tr>
            </thead>
            <tbody>
                @foreach($operationsRetour as $operationRetour)
                    <tr>
                        <td>{{ $operationRetour->date_op }}</td>
                        <td>{{ $operationRetour->TypeVenteR }}</td>
                        <td>{{ $operationRetour->numero_vente }}</td>
                        <td>
                            @php
                                $qteRetourFormatted = '';
                                $qteRetour = explode(';', $operationRetour->qteR);

                                foreach ($qteRetour as $item) {
                                    list($elementId, $qte) = explode('*', $item);

                                    switch ($operationRetour->TypeVenteR) {
                                        case 'vente-oeuf':
                                            $categorie =App\Models\CategorieOeuf::find($elementId);
                                            $label = $categorie ? $categorie->Denomination : 'Inconnu';
                                            $qteRetourFormatted .= "{$label}: ({$qte} plateaux)<br>";
                                            break;

                                        case 'vente-sujet':
                                            $bande = App\Models\Bande::find($elementId);
                                            $label = $bande ? $bande->nom_bande : 'Inconnu';
                                            $qteRetourFormatted .= "{$label}: ({$qte} Ind) <br>";
                                            break;

                                        case 'vente-autre':
                                            $produit = App\Models\Produit::find($elementId);
                                            $label = $produit ? $produit->Denomination : 'Inconnu';
                                            $qteRetourFormatted .= "{$label}:({$qte}kg) <br>";
                                            break;
                                    }
                                }
                            @endphp
                            {!! $qteRetourFormatted !!}
                        </td>
                        <td>{{ $operationRetour->Montant_R }}</td>
                        <td>{{ $operationRetour->beneficiaire }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Nom de l'Entreprise. Tous droits réservés.</p>
    </div>
    @include('partials.script')
</body>
</html>
