<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $table = 'utilisateurs';

    protected $fillable = [
        'email',
        'mot_de_passe',
        'nom',
        'prenom',
        'numero',
    ];
}
