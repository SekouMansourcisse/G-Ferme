<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Souche extends Model
{
    use HasFactory;
    protected $table = 'souches';
    protected $fillable = ['Denomination', 'type','infos_supp'];
}
