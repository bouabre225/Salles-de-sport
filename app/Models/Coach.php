<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $fillable = [
        'email',
        'mot_de_passe',
        'nom',
        'prenom',
        'cip',
    ];
}
