<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
    <title>Liste de Type de depense</title>
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
        <img src="{{ public_path('assets/img/logo-gferme.png') }}" alt="Logo Entreprise">
        <h2>Banankabougou</h2>
        <p>Adresse de l'entreprise<br>
        Téléphone : +123 456 789<br>
        Email : entreprise@example.com</p>
    </div>

    <h3>Liste de Type de depense</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>

                    <th>Dénomination</th>
                    <th>Description</th>
                    <th>Date de Création</th>
                </tr>
            </thead>
            <tbody>
                @foreach($type_depenses as $type_depense)
                <tr>

                    <td>{{ $type_depense->Denomination }}</td>
                    <td>{{ $type_depense->description }}</td>
                    <td>{{ $type_depense->created_at }}</td>
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
