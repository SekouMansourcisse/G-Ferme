<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-logo img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .client-table {
            width: 100%;
            border-collapse: collapse;
        }
        .client-table th, .client-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .client-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="company-info">
        <div class="company-logo">
            <img src="{{ $logoPath }}" alt="Logo">
        </div>
        <h2>{{ $companyInfo['name'] }}</h2>
        <p>{{ $companyInfo['address'] }}</p>
        <p>{{ $companyInfo['phone'] }} | {{ $companyInfo['email'] }}</p>
    </div>

    <h3>Liste des Clients</h3>
    <table class="client-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>WhatsApp</th>
                <th>Dette Initiale</th>
                <th>Adresse</th>
                <th>Email</th>
                <th>Infos Supp.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->nom }}</td>
                    <td>{{ $client->prenom }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->num_whatsapp }}</td>
                    <td>{{ $client->dette_initiale }}</td>
                    <td>{{ $client->adresse_physique }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->infos_supp }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
