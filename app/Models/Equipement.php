<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    protected $table = 'equipements';
    protected $fillable = [
        'Denomination',
        'ferme_id',
        'Emplacement',
        'PrixAchat',
        'responsable',
        'commentaire',
    ];

    public function mouvements()
    {
        return $this->hasMany(MouvementEquipement::class);
    }

    public function ferme()
    {
        return $this->belongsTo(Ferme::class,'ferme_id');
    }
    public function delete()
    {

        $this->mouvements()->delete();

        // Appeler la méthode delete parent
        return parent::delete();
    }
}

