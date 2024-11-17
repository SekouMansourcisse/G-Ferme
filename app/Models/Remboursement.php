<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remboursement extends Model
{
    use HasFactory;

    protected $table = 'remboursement';

    protected $fillable = [
        'client',
        'NomPrenomClient',
        'Dette',
        'operation',
        'montant_paye',
        'virement_par',
        'fournisseur',
        'NomPrenomFournisseur',
        'payer_par',
        'date_r'
    ];
    public function compte()
    {
        return $this->belongsTo(Compte::class, 'payer_par');
    }
    public function OperationR()
    {
        return $this->belongsTo(Operation::class, 'operation');
    }
    public $timestamps = true; // Laravel gère automatiquement les colonnes created_at et updated_at
}
