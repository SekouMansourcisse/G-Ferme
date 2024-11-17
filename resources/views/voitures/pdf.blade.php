<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
    <title>Liste de voiture</title>
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
        <img src="{{ public_path('assets/img/logo-gferme.png') }}" alt="Logo Entreprise">
        <h2>Banankabougou</h2>
        <p>Adresse de l'entreprise<br>
        Téléphone : +123 456 789<br>
        Email : entreprise@example.com</p>
    </div>

    <h3>Liste de Voiture</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Numéro de Plaque</th>
                    <th>Modèle</th>
                    <th>Marque</th>
                    <th>Année</th>
                    <th>Commentaires</th>
                </tr>
            </thead>
            <tbody>
                @foreach($voitures as $voiture)
                    <tr>
                        <td>{{ $voiture->plaque_immatriculation }}</td>
                        <td>{{ $voiture->modele }}</td>
                        <td>{{ $voiture->marque }}</td>
                        <td>{{ $voiture->annee_fabrication }}</td>
                        <td>{{ $voiture->commentaire }}</td>

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
