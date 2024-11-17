<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'voiture_id',
        'date_maintenance',
        'type_maintenance',
        'cout',
        'commentaire',
    ];

    // Relation avec la voiture
    public function voiture()
    {
        return $this->belongsTo(Voiture::class);
    }
}
