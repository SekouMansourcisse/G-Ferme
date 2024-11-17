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

    <h3>Liste de Bovins</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Race</th>
                    <th>Type d'elevage</th>
                    <th>Date de Naissance</th>
                    <th>État de Santé</th>
                    <th>Poids</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vaches as $vache)
                <tr>
                    <td>{{ $vache->nom }}</td>
                    <td>{{ $vache->race }}</td>
                    <td>{{ $vache->type_elevage}}</td>
                    <td>{{ $vache->date_naissance }}</td>
                    <td>{{ $vache->etat_sante }}</td>
                    <td>{{ $vache->poids }}</td>
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
