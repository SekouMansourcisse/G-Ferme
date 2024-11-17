<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journalisation extends Model
{
    use HasFactory;

    protected $table = 'journalisations';
    protected $fillable = ['Date', 'Age', 'Poulailler', 'Sujet_Malade', 'Sujet_Mort', 'Sujet_retour_maladie', 'Sujet_Tri','commentaire', 'bande_id'];
}
