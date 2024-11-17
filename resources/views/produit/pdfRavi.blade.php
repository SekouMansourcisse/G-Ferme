<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Ravitaillements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .header h1 {
            margin: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
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

    <h2>Liste des Ravitaillements</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Fournisseur</th>
                <th>Produit</th>
                <th>Montant Ravitaillement</th>
                <th>Remise</th>
                <th>Montant payé</th>
                <th>Reste à payer</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ravitaillements as $ravitaillement)
            <tr>
                <td>{{ $ravitaillement->Date_op }}</td>
                <td>{{ $ravitaillement->NomPrenomFournisseur }}</td>
                <td>
                    @foreach(explode(',', $ravitaillement->Produit) as $produit)
                        @php
                            $produitDetails = explode('*', $produit);
                            if(count($produitDetails) == 4) {
                                list($id, $quantite, $prix_unitaire, $prix_total) = $produitDetails;
                                $produitNom = App\Models\Produit::find($id)->Denomination;
                            } else {
                                $produitNom = 'Produit inconnu';
                            }
                        @endphp
                        {{ $produitNom }} ({{ $quantite }}Kg)
                    @endforeach
                </td>
                <td>{{ $ravitaillement->TotalRavitaillement }}</td>
                <td>{{ $ravitaillement->totalRemise }}</td>
                <td>{{ $ravitaillement->montant_payé }}</td>
                <td>{{ $ravitaillement->montantDette }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
