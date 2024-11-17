<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['produit_id', 'message', 'lu'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}

