<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerteProduitOEuf extends Model
{
    use HasFactory;

    protected $table = 'PerteProduitOEuf';

    protected $fillable = [
        'date_p',
        'description',
        'type_perte',
        'produit',
        'Oeuf'
    ];
}

