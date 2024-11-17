<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;
    protected $table = 'Classification';
    protected $fillable = [
        'OeufTotal',
        'qteCasse',
        'qte_nonCommerciale',
        'Total_a_categoriser',
        'date',
        'bande_id',
    ];
}

