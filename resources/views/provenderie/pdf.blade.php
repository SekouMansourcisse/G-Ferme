<!DOCTYPE html>
<html>
<head>
    <title>Liste de provenderie</title>
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

    <h3>Liste des Provenderies</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date de Fabrication</th>
                <th>Produits Obtenus</th>
                <th>Ingrédients Consommés</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($provenderies as $provenderie)
            <tr>
                <td>{{ $provenderie->date_f }}</td>
                <td>
                    @foreach(explode(',', $provenderie->Provend_produit) as $produit)
                        @php
                            list($id, $qte) = explode('*', $produit);
                            $produitNom = App\Models\Produit::find($id)->Denomination;
                        @endphp
                        {{ $produitNom }} ({{ $qte }} Kg)<br>
                    @endforeach
                </td>
                <td>
                    @foreach(explode(',', $provenderie->Ingredient) as $ingredient)
                        @php
                            list($id, $qte) = explode('*', $ingredient);
                            $ingredientNom = App\Models\Produit::find($id)->Denomination;
                        @endphp
                        {{ $ingredientNom }} ({{ $qte }} Kg)<br>
                    @endforeach
                </td>
                <td>{{ $provenderie->commentaire }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Nom de l'Entreprise. Tous droits réservés.</p>
    </div>
    @include('partials.script')
</body>
</html>
