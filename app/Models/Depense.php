<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;
    protected $table = 'depenses';
    protected $fillable = [
        'Date_depense',
        'Beneficiaire',
        'Categorie_depense',
        'TypeDepense_id',
        'Objet',
        'Montant_d',
        'Montant_paye',
        'payer_par',
        'Fournisseur_id',
        'Description',
        'dette',
    ];

    public function typeDepense()
    {
        return $this->belongsTo(TypeDepense::class,'TypeDepense_id');
    }
    public function compte()
    {
        return $this->belongsTo(Compte::class, 'payer_par');
    }
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'Fournisseur_id');
    }
}

