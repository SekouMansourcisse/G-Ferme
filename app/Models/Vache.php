<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Vache extends Model
{
    protected $fillable = ['nom', 'race_id', 'date_naissance', 'etat_sante', 'etat','type_elevage', 'ferme_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vache) {
            // Obtenir le nombre de vaches dans la base de données
            $nombreVaches = Vache::count() + 1; // Auto-incrémenté

            // Récupérer le mois et l'année courants
            $moisAnnee = Carbon::now()->format('my'); // Par exemple : '1024' pour Octobre 2024

            // Générer le nom avec le format désiré
            $vache->nom = 'v-' . str_pad($nombreVaches, 2, '0', STR_PAD_LEFT) . $moisAnnee;
        });
    }
    public function vachesLaitieres()
    {
        return $this->hasMany(VacheLaitiere::class);
    }
    public function race()
    {
        return $this->belongsTo(Race::class,'race_id');
    }
    public function bovins()
    {
        return $this->hasMany(Bovin::class);
    }

    public function reproductions()
    {
        return $this->hasMany(Reproduction::class);
    }
}

