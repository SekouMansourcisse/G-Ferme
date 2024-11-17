<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provenderie extends Model
{
    use HasFactory;

    protected $table = 'Provenderie';

    protected $fillable = [
        'date_f',
        'Provend_produit',
        'qte',
        'Ingredient',
        'commentaire'
    ];
}

