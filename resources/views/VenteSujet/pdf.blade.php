<!DOCTYPE html>
<html>
<head>
    <title>Liste de vente de Poulets</title>
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

    <h3>Liste des ventes de Poulets</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date Vente</th>
                    <th>Client</th>
                    <th>Infos Vente</th>
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
                        <td>Nom&Prenom:{{ $vente->NomPrenomClient }}</td>
                        <td>
                            @foreach(explode(',', $vente->sujetInfos) as $sujet)
                            @php
                            list($id, $qte,$prix,$total) = explode('*', $sujet);
                            $sujetNom = App\Models\Bande::find($id)->nom_bande;
                            @endphp
                         Bande: {{ $sujetNom }}<br> Quantite vendu:({{ $qte }} Ind) <br> Prix Unitaire: {{ $prix }} <br> Montant:{{ $total }}
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
