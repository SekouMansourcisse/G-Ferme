<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesage extends Model
{
    use HasFactory;

    protected $table = 'pesage';
    protected $fillable = ['date', 'semaine_p', 'poulailler','commentaire', 'bande_id'];
}
