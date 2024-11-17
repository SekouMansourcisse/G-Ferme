<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    use HasFactory;

    protected $table = 'transferts';

    protected $fillable = [
        'compte_source_id',
        'compte_destination_id',
        'montant',
        'typetransfert',
        'justificatif',
    ];

    public function compteSource()
    {
        return $this->belongsTo(Compte::class, 'compte_source_id');
    }

    public function compteDestination()
    {
        return $this->belongsTo(Compte::class, 'compte_destination_id');
    }
}
