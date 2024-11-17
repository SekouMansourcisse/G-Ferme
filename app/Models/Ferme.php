<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ferme extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'typeFerme', 'adresse', 'entreprise_id'];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_ferme', 'ferme_id', 'user_id');
    }

}
