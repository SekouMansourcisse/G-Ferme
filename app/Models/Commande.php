<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = [
        'type_vente',
        'date',
        'NomPrenomClient',
        'client',
        'produit',
        'oeufs',
        'poulets',
        'TotalVente',
        'TotalRemise',
        'Net_a_payer',
        'etat',
        'Montant_paye',
        'MontantDette',
        'payer_par',
        'document',
    ];
}

