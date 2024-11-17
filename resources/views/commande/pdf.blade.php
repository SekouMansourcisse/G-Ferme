<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
    <title>Liste de Commande en attente de paiement</title>
<!-- CSS supplémentaire pour embellir la facture -->
<style>
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background-color: #f9f9f9;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }



h4 {
    background-color: #b38526; /* Couleur de fond dorée */
    padding: 10px;
    color: white;
}

table th, table td {
    text-align: left;
}

.text-right {
    text-align: right;
}

</style>
</head>
<body>
    <div class="row">
        <div class="col-md-12 text-center">
            <!-- Logo et informations de l'entreprise -->
                <img src="{{ public_path('storage/' . ($settings->logo_facture ?? 'default_logo.png')) }}" alt="Logo Entreprise">
                <h2>{{ $settings->nomFerme }}</h2>
                <p>
                    {{ $settings->adresse }}<br>
                    Téléphone : {{ $settings->phone_ferme }}<br>
                    Email : {{ $settings->email_ferme ?? 'Non spécifié' }}
                </p>

        </div>
    </div>
    <!-- Informations du client -->
    <div class="row mt-4">
        <div class="col-md-3">
            <label><strong>client :</strong></label>
            <input type="text" class="form-control" value="{{ $client->prenom }} {{ $client->nom }}"
                readonly>
        </div>
        <div class="col-md-3">
            <label><strong>Adresse :</strong></label>
            <input type="text" class="form-control" value="{{ $client->adresse_physique }}" readonly>
        </div>
        <div class="col-md-3">
            <label><strong>N°Tel :</strong></label>
            <input type="text" class="form-control" value="{{ $client->phone }}" readonly>
        </div>
        <div class="col-md-3">
            <label><strong>Email:</strong></label>
            <input type="text" class="form-control" value="{{ $client->email }}" readonly>
        </div>
        <!-- Détails de la facture -->
        <div class="row mt-4">
            <div class="col-md-6">
                <p><strong>Date de facture :</strong> {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}
                </p>
                <p><strong>Numéro de commande :</strong> #{{ $commande->id }}</p>
            </div>
            <div class="col-md-6 text-right">
                <p><strong>Statut :</strong> {{ $commande->etat == 1 ? 'Non payé' : 'Payé' }}</p>
                <p><strong>Date d'échéance :</strong>
                    {{ \Carbon\Carbon::parse($commande->date_echeance)->format('d/m/Y') }}</p>
            </div>
        </div>


            <!-- Tableau des services -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Type de Vente</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Montant Vente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Affichage des produits, œufs et poulets -->
                            @if (!empty($commande->produit))
                                @foreach (explode(',', $commande->produit) as $produit)
                                    @php
                                        [$produitId, $quantite, $prixUnitaire, $montantTotal] = explode('*', $produit);
                                    @endphp
                                    <tr>
                                        <td>Produit</td>
                                        <td>{{ $quantite }}</td>
                                        <td>{{ $prixUnitaire }}</td>
                                        <td>{{ $montantTotal }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            @if (!empty($commande->oeufs))
                                @foreach (explode(',', $commande->oeufs) as $oeuf)
                                    @php
                                        [$categorieId, $quantite, $montantTotal] = explode('*', $oeuf);
                                        $prixUnitaire = $montantTotal / $quantite;
                                    @endphp
                                    <tr>
                                        <td>Œuf</td>
                                        <td>{{ $quantite }}</td>
                                        <td>{{ $prixUnitaire }}</td>
                                        <td>{{ $montantTotal }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            @if (!empty($commande->poulets))
                                @foreach (explode(',', $commande->poulets) as $poulet)
                                    @php
                                        [$bandeId, $quantite, $prixUnitaire, $montantTotal] = explode('*', $poulet);
                                    @endphp
                                    <tr>
                                        <td>Poulet</td>
                                        <td>{{ $quantite }}</td>
                                        <td>{{ $prixUnitaire }}</td>
                                        <td>{{ $montantTotal }}</td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-4 justify-content-end">
                <div class="col-md-4">
                    <p><strong>Montant Total :</strong> {{ $commande->TotalVente }} X0F</p>
                    <p><strong>Montant Remise :</strong> {{ $commande->TotalRemise }} X0F</p>
                    <p><strong>Montant Net a payer :</strong> {{ $commande->Net_a_payer }} X0F</p>
                    <p><strong>Montant Payé :</strong> {{ $commande->Montant_paye }} X0F</p>
                    <p><strong>Montant Dette :</strong> {{ $commande->MontantDette }} X0F</p>
                    <h4 class="text-right" style="background-color: #b38526; padding: 10px; color: white;">Total :
                        {{ $commande->Net_a_payer }} X0F</h4>
                </div>
            </div>
            <div class="invoice-signature">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Signature:</strong></p>
                        <p>Comptable</p>
                        <br>
                        <p>Magasinier</p>
                    </div>
                </div>
            </div>
    <div class="footer">
        <p>© {{ date('Y') }} Nom de l'Entreprise. Tous droits réservés.</p>
    </div>
    @include('partials.script')
</body>
</html>
