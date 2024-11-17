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
        <img src="{{ public_path('assets/img/logo-gferme.png') }}" alt="Logo Entreprise">
        <h2>Banankabougou</h2>
        <p>Adresse de l'entreprise<br>
        Téléphone : +123 456 789<br>
        Email : entreprise@example.com</p>
    </div>

    <h3>Liste de Vignette</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>

                    <th>Voiture Associée</th>
                    <th>Date d'Acquisition</th>
                    <th>Date d'Expiration</th>
                    <th>Coût</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vignettes as $vignette)
                    <tr>

                        <td>{{ $vignette->voiture->plaque_immatriculation }} - {{ $vignette->voiture->modele }}</td>
                        <td>{{ $vignette->date_obtention }}</td>
                        <td>{{ $vignette->date_expiration }}</td>
                        <td>{{ $vignette->montant }}</td>

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
