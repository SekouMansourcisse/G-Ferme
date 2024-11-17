<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamassageOeuf extends Model
{
    use HasFactory;

    protected $table = 'ramassage_oeufs';
    protected $fillable = ['Date', 'poulailler', 'NumRamassage','commentaire', 'Total', 'taux_pointe', 'bande_id'];
}
