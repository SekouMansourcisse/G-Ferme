<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'client';
    protected $fillable = [
        'nom',
        'prenom',
        'dette_initiale',
        'phone',
        'num_whatsapp',
        'email',
        'adresse_physique',
        'infos_supp',
    ];
}
