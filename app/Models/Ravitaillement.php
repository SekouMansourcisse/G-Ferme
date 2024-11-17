<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ravitaillement extends Model
{
    use HasFactory;

    protected $table = 'ravitaillements';

    protected $fillable = [
        'date',
        'fournisseur_id',
        'produit_id',
        'qte_produit',
        'prix_unitaire_p',
        'prix_total_p',
        'total_ravitaillement',
        'total_remise',
        'net_payer',
        'montant_paye',
        'dette_a_paye',
        'ref_facture',
        'payer_par'
    ];

    public static function ajouterRavitaillement($data)
    {
        return self::create($data);
    }
}
