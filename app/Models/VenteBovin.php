<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenteBovin extends Model
{
    protected $table ='ventesbovins';
    protected $fillable = ['vaches', 'date_vente','montant_paye','prix_vente', 'acheteur', 'total_remise','montantDette','payer_par'];

    public function bovin()
    {
        return $this->belongsTo(Vache::class);
    }
}

