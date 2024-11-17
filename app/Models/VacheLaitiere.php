<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacheLaitiere extends Model
{
    protected $table='vacheslaitieres';
    protected $fillable = ['vache_id', 'production_matin', 'production_soir', 'date_production'];

    public function vache()
    {
        return $this->belongsTo(Vache::class);
    }

    public function produitsLaitiers()
    {
        return $this->hasMany(ProduitLaitier::class);
    }
}

