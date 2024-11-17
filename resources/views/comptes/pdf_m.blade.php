<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
    <title>Liste de mouvement Compte à Compte</title>
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

    <h3>Liste de mouvement Compte à Compte</h3>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Compte Source</th>
                    <th>Compte de Destination</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transferts as $transfert)
                <tr data-id="{{ $transfert->id }}">
                    <td>{{ $transfert->compteSource->Denomination }}</td>
                    <td>{{ $transfert->compteDestination->Denomination }}</td>
                    <td>{{ $transfert->montant }}</td>
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
