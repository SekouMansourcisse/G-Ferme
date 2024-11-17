<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
    <title>Liste de vente d'autres produits</title>
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

    <h3>Liste des ventes d'autres produits</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date Vente</th>
                    <th>Client</th>

                    <th>Quantité vendu</th>
                    <th>Montant Vente</th>
                    <th>Total Remise</th>
                    <th>Net à Payer</th>
                    <th>Montant Payé</th>
                    <th>Dette à Payer</th>
                    <th>Payé Par</th>

                </tr>
            </thead>
            <tbody>
                @foreach($ventes as $vente)
                    <tr>
                        <td>{{ $vente->Date_op }}</td>
                        <td>Type de client: {{ $vente->type_client }} <br> Nom&Prenom:{{ $vente->NomPrenomClient }} <br> N°Tel: {{ $vente->phone }}</td>
                        <td>
                            @foreach(explode(',', $vente->AutresInfos) as $produit)
                            @php
                            list($id, $qte) = explode('*', $produit);
                            $produitNom = App\Models\Produit::find($id)->Denomination;
                            @endphp
                        {{ $produitNom }} ({{ $qte }} Kg)<br>
                            @endforeach
                        </td>
                        <td>{{ $vente->TotalRavitaillement }}</td>
                        <td>{{ $vente->totalRemise }}</td>
                        <td>{{ $vente->Montant_facture }}</td>
                        <td>{{ $vente->montant_payé }}</td>
                        <td>{{ $vente->montantDette }}</td>
                        <td>
                            @php
                              $compte = App\Models\Compte::find($vente->Payer_par)->Denomination;
                            @endphp
                            {{ $compte }}
                        </td>
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
