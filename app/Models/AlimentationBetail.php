<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlimentationBetail extends Model
{
    use HasFactory;

    protected $table = 'alimentation_betail';

    protected $fillable = [
        'vache_id',
        'date_alimentation',
        'produit_id',
        'quantite',
        'periode',
        'commentaire',
    ];

    // Relation avec le modèle Vache
    public function vache()
    {
        return $this->belongsTo(Vache::class);
    }
    public function Produit()
    {
        return $this->belongsTo(Produit::class,'produit_id');
    }
}

