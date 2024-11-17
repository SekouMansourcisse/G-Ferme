<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDepense extends Model
{
    use HasFactory;
    protected $table = 'typedepense';
    protected $fillable = [
        'Denomination',
        'description',
    ];

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}

