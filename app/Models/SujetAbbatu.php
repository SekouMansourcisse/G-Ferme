<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SujetAbbatu extends Model
{
    use HasFactory;

    protected $table = 'sujet_abbatu';
    protected $fillable = ['abbatoire_id', 'nombre_sujet', 'poids_abbatu', 'date_abbatage'];

    // Relation avec Abbatoire
    public function abbatoire()
    {
        return $this->belongsTo(Abbatoire::class,'abbatoire_id');
    }
}

