<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poulailler extends Model
{
    use HasFactory;
    protected $table = 'poulailler';
    protected $fillable = ['Denomination', 'contenance_normale', 'Dimension','etat', 'infos_supp'];
}
