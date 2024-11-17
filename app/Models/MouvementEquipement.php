<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementEquipement extends Model
{
    use HasFactory;
    protected $table = 'mouvement_equipement';
    protected $fillable = [
        'Equipement_id',
        'Origine',
        'Destination',
        'Statut',
        'Date_mouvement',
        'commentaire',
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'Equipement_id');
    }
}

