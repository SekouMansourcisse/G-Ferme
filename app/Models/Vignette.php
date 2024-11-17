<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vignette extends Model
{
    use HasFactory;

    protected $fillable = [
        'voiture_id',
        'date_obtention',
        'date_expiration',
        'montant',
    ];

    // Relation avec la voiture
    public function voiture()
    {
        return $this->belongsTo(Voiture::class);
    }
}
