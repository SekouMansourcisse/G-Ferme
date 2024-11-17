<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $fillable = ['nomFerme', 'SigleFerme','adresse','email_ferme','phone_ferme','devise','facture_message','logo_titre','logo_facture'];
}
