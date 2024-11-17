<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitLaitier extends Model
{
    protected $fillable = ['vache_laitiere_id', 'nom_produit', 'quantite', 'date_production'];

    public function vacheLaitiere()
    {
        return $this->belongsTo(VacheLaitiere::class);
    }
}

