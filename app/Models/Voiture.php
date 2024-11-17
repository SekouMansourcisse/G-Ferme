<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque',
        'modele',
        'plaque_immatriculation',
        'annee_fabrication',
        'kilometrage',
        'etat',
        'commentaire',
    ];

    // Relation avec l'assurance
    public function assurances()
    {
        return $this->hasMany(Assurance::class);
    }

    // Relation avec la vignette
    public function vignettes()
    {
        return $this->hasMany(Vignette::class);
    }

    // Relation avec la maintenance
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
    public function delete()
    {
        // Supprimer les assurances, vignettes, et maintenances associées
        $this->assurances()->delete();
        $this->vignettes()->delete();
        $this->maintenances()->delete();

        // Appeler la méthode delete parent
        return parent::delete();
    }
}
