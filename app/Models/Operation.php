<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'Operation';

    protected $fillable = [
        'NomPrenomClient', 'client', 'infosOeuf', 'Montant_facture', 'Totalvente','AutresInfos',
        'montant_payé', 'montantDette', 'totalRemise', 'typeOperation', 'Fournisseur','sujetInfos',
        'NomPrenomFournisseur', 'Produit', 'TotalRavitaillement', 'Payer_par','Date_op','typevente','commande_id'
    ];

    // Insère une nouvelle opération
    public static function insertOperation($data)
    {
        return self::create($data);
    }
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'Fournisseur');
    }
    public function compte()
    {
        return $this->belongsTo(Compte::class, 'Payer_par');
    }
    public function client()
    {
        return $this->belongsTo(Compte::class, 'client');
    }
    public function remboursement()
    {
        return $this->belongsTo(Remboursement::class);
    }
    public function OperationR()
    {
        return $this->belongsTo(Operation::class, 'operation');
    }

    // Récupère une opération par ID
    public static function getOperation($id)
    {
        return self::find($id);
    }

    // Met à jour une opération par ID
    public static function updateOperation($id, $data)
    {
        $operation = self::find($id);
        if ($operation) {
            $operation->update($data);
            return $operation;
        }
        return null;
    }

    // Supprime une opération par ID
    public static function deleteOperation($id)
    {
        $operation = self::find($id);
        if ($operation) {
            $operation->delete();
            return true;
        }
        return false;
    }
}
