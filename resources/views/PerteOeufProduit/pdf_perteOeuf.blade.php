<!DOCTYPE html>
<html>
<head>
    <title>Liste de perte d'Oeufs</title>
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

    <h3>Liste des pertes d'Oeufs</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date de declaration</th>
                    <th>Description</th>
                    <th>Œufs Perdus</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pertes as $perte)
                @if($perte->type_perte=="Oeufs")
                    <tr>

                        <td>{{ $perte->date_p }}</td>
                        <td>{{ $perte->description }}</td>

                        <td>
                            @foreach(explode(',', $perte->Oeuf) as $oeuf)
                            @php
                            list($id, $qte) = explode('*', $oeuf);
                            $oeufNom = App\Models\CategorieOeuf::find($id)->Denomination;
                            @endphp
                        {{ $oeufNom }} ({{ $qte }} Oeufs)<br>
                            @endforeach
                        </td>
                    </tr>
                @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune perte d'œufs enregistrée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Nom de l'Entreprise. Tous droits réservés.</p>
    </div>
    @include('partials.script')
</body>
</html>
