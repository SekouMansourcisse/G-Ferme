<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;
    protected $table = 'depot';
    protected $fillable = [
        'client_id',
        'solde_depot_actuel',
        'montant_depot',
        'versement_fait',
        'compte_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    // Méthode pour ajouter un dépôt
    public static function ajouterDepot($data)
    {
        return self::create($data);
    }

    // Méthode pour récupérer un dépôt par son ID
    public static function getDepotById($depotId)
    {
        return self::find($depotId);
    }

    // Méthode pour récupérer tous les dépôts
    public static function getDepots()
    {
        return self::all();
    }

    // Méthode pour récupérer le client associé à un dépôt
    public function getClientByDepot()
    {
        return $this->client;
    }

    // Méthode pour supprimer un dépôt
    public function deleteDepot()
    {
        return $this->delete();
    }
}
