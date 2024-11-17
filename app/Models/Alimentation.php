<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alimentation extends Model
{
    use HasFactory;

    protected $table = 'alimentations';
    protected $fillable = ['Age_en_jour','Poulailler','date','commentaire','bande_id'];
}
