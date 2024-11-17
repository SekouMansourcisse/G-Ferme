<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCategorisation extends Model
{
    use HasFactory;
    protected $table = 'OperationCategorisation';
    protected $fillable = [
        'date_op',
        'TableCategorie',
    ];
}
