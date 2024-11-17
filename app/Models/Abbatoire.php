<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abbatoire extends Model
{
    use HasFactory;

    protected $table = 'abbatoire';
    protected $fillable = ['denomination', 'quantite_sujet', 'adresse'];

    // Relation avec SujetAbbatu
    public function sujetsAbbatus()
    {
        return $this->hasMany(SujetAbbatu::class);
    }
}
