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

    <h3>Liste de depense</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>

                    <th>Denomination </th>
                    <th>Solde Actuelle</th>

                    <th>Infos suppls</th>

                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comptes as $compte)
                <tr data-id="{{ $compte->id }}">
                    <td>{{ $compte->Denomination }}</td>
                    <td>{{ $compte->solde_actuel }}</td>
                    <td>{{ $compte->infos_supp }}</td>
                    <td>
                        <a class="me-3 edit-item-btn"  href="javascript:void(0);" id="edit-item-btn">
                            <img src="assets/img/icons/edit.svg" alt="img">
                        </a>
                        <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                            <img src="assets/img/icons/delete.svg" alt="img">
                        </a>
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
