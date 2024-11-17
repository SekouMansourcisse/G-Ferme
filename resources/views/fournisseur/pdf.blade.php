<!DOCTYPE html>
<html>
<head>
    <title>Liste des Fournisseurs</title>
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

    <h3>Liste des Fournisseurs</h3>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>N°Téléphone</th>
                <th>N°WhatsApp</th>
                <th>Redevance Initiale</th>
                <th>Adresse</th>
                <th>Produit</th>
                <th>Infos Supplémentaires</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fournisseurs as $fournisseur)
            <tr>
                <td>{{ $fournisseur->nom }}</td>
                <td>{{ $fournisseur->prenom }}</td>
                <td>{{ $fournisseur->phone }}</td>
                <td>{{ $fournisseur->num_whatsapp }}</td>
                <td>{{ $fournisseur->redevance_initiale }}</td>
                <td>{{ $fournisseur->adresse_physique }}</td>
                <td>{{ $fournisseur->produit->Denomination }}</td>
                <td>{{ $fournisseur->infos_supp }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} G-ferme. Tous droits réservés.</p>
    </div>
</body>
</html>
