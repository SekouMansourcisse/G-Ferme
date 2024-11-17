<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
    <title>Liste de Remboursements des clients</title>
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

    <h3>Liste de Remboursements des clients</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Dette</th>
                    <th>Montant payé</th>
                    <th>Client</th>
                </tr>
            </thead>
            <tbody>
                @foreach($remboursements as $remboursement)
                @if($remboursement->client!=null)
                <tr>
                    <td>{{ $remboursement->date_r }}</td>
                    <td>{{ $remboursement->Dette }}</td>
                    <td>{{ $remboursement->montant_paye }}</td>
                    <td>{{ $remboursement->NomPrenomClient }}</td>
                </tr>
                @endif
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
