<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationRetour extends Model
{
    use HasFactory;

    protected $table = 'operationretour';

    protected $fillable = [
        'date_op',
        'TypeVenteR',
        'numero_vente',
        'qteR',
        'Montant_R',
        'TotalR',
        'TypeRetour',
        'commande_id',
        'payer_par',
        'beneficiaire',
        'BonRemp_signer',
        'etat'
    ];

    public function MemeEtat()
    {
        $reponse = true;
        $operations = OperationRetour::where('commande_id', $this->id)
            ->where('TypeRetour', 'Remboursement')
            ->get();

        foreach ($operations as $operation) {
            if ($operation->etat != 1) {
                $reponse = false;
                break;
            }
        }

        return $reponse;
    }
}

