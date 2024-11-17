<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bande extends Model
{
    use HasFactory;

    protected $table = 'bande';

    protected $fillable = [
        'responsable',
        'nom_bande',
        'cheptel_depart',
        'cheptel_actuel',
        'type_elevage',
        'souche_bande',
        'date_demarrage',
        'date_fin',
        'age_arrive',
        'poid_moyen_depart',
        'cout_acquisition',
        'montant_paye',
        'fournisseur',
        'nomFournisseur',
        'observation',
        'type',
        'TotalOeuf',
        'poulailler',
        'etat'
    ];

}
