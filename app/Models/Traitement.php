<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traitement extends Model
{
    use HasFactory;

    protected $table = 'traitement';
    protected $fillable = ['date', 'denomination', 'description', 'Produit','qte_utilise','etat','bande_id','vache_id'];

    public function vache()
    {
        return $this->belongsTo(Vache::class,'vache_id');
    }
    public function bande()
    {
        return $this->belongsTo(Bande::class);
    }
}
