<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bovin extends Model
{
    protected $fillable = ['vache_id', 'date_achat', 'prix_achat', 'etat_sante', 'poids', 'ferme_id'];

    public function vache()
    {
        return $this->belongsTo(Vache::class);
    }
}

