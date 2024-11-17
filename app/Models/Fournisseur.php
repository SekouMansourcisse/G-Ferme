<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'fournisseur';
    protected $fillable = [
        'nom',
        'prenom',
        'redevance_initiale',
        'phone',
        'num_whatsapp',
        'adresse_physique',
        'infos_supp',
        'produit_id', // Supposons que cela soit l'ID du produit fourni par ce fournisseur
    ];

    /**
     * Définition de la relation vers le produit fourni par le fournisseur
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
