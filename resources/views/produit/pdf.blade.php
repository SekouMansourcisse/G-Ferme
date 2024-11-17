<!DOCTYPE html>
<html>
<head>
    <title>Liste des Produits</title>
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

    <h3>Liste des Produits</h3>

    <table>
        <thead>
            <tr>
                <th>Dénomination</th>
                <th>Référence</th>
                <th>Notifier par Seuil</th>
                <th>Quantité en Stock</th>
                <th>Prix Unitaire</th>
                <th>Infos Supplémentaires</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
            <tr>
                <td>{{ $produit->Denomination }}</td>
                <td>{{ $produit->reference_produit }}</td>
                <td>{{ $produit->stock_seuil }}</td>
                <td>{{ $produit->qte_stock }}</td>
                <td>{{ $produit->prix_unitaire }}</td>
                <td>{{ $produit->infos_supp }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Nom de l'Entreprise. Tous droits réservés.</p>
    </div>
</body>
</html>
