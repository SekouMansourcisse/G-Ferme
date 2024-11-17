<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'logo', 'adresse', 'phone', 'email'];

    public function fermes()
    {
        return $this->hasMany(Ferme::class);
    }
}
