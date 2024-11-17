<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'voiture_id',
        'assureur',
        'date_debut',
        'date_fin',
        'montant',
    ];

    // Relation avec la voiture
    public function voiture()
    {
        return $this->belongsTo(Voiture::class);
    }
}
