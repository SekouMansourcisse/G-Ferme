<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    protected $table = 'compte';
    protected $fillable = ['Denomination', 'solde_actuel','infos_supp'];

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'payer_par');
    }

    // Relation avec la table 'depenses'
    public function depenses()
    {
        return $this->hasMany(Depense::class, 'payer_par');
    }

    // Relation avec la table 'Operation'
    public function operations()
    {
        return $this->hasMany(Operation::class, 'Payer_par');
    }

    // Relation avec la table 'operationretour'
    public function operationsRetour()
    {
        return $this->hasMany(OperationRetour::class, 'payer_par');
    }

    // Relation avec la table 'remboursements'
    public function remboursements()
    {
        return $this->hasMany(Remboursement::class, 'payer_par');
    }
}
