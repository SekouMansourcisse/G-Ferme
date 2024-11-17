<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamassageLitiere extends Model
{
    use HasFactory;

    protected $table = 'ramassage_litières';
    protected $fillable = ['poulailler', 'qte_ramasser', 'date','commentaire', 'bande_id'];
}
