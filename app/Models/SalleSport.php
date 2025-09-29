<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalleSport extends Model
{
    protected $fillable = [
        'email',
        'mot_de_passe',
        'nom',
        'nom_proprietaire',
        'rccm',
        'adresse',
        'horaires',
        'type_sport',
    ];
}
