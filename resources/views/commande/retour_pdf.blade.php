<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
    <title>Liste de depense</title>
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
    <div class="header">
        <img src="{{ public_path('storage/' . ($settings->logo_facture ?? 'default_logo.png')) }}" alt="Logo Entreprise">
        <h2>{{ $settings->nomFerme }}</h2>
        <p>
            {{ $settings->adresse }}<br>
            Téléphone : {{ $settings->phone_ferme }}<br>
            Email : {{ $settings->email_ferme ?? 'Non spécifié' }}
        </p>
    </div>

    <h3>Liste de Retour vente</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type de Vente</th>
                    <th>Numéro de Vente</th>
                    <th>Quantités Retournées</th>
                    <th>Montant Retour</th>
                    <th>Type Retour</th>
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

                        foreach($group as $operationRetour) {
                            // Ajouter la date, le type de vente et le numéro de vente à la liste
                            $dates[] = $operationRetour->date_op;
                            $typesVente[] = $operationRetour->TypeVenteR;
                            $numerosVente[] = $operationRetour->numero_vente;

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
                        $uniqueNumerosVente = implode(', ', array_unique($numerosVente));

                    @endphp
                    <tr>
                        <td>{{ $uniqueDates }}</td>
                        <td>{{ $uniqueTypesVente }}</td>
                        <td>{{ $uniqueNumerosVente }}</td>
                        <td>{!! $totalQteFormatted !!}</td>
                        <td>{{ $totalMontant }}</td>
                        <td>{{ $typeRetour }}</td>
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
