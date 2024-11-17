<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reproduction extends Model
{
    protected $fillable = ['vache_id', 'bovin_id', 'date_reproduction', 'date_naissance', 'etat'];

    public function vache()
    {
        return $this->belongsTo(Vache::class);
    }

    public function bovin()
    {
        return $this->belongsTo(Bovin::class);
    }
}

