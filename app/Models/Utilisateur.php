<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Model implements Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'utilisateurs';
    protected $table = 'utilisateurs';

    protected $fillable = [
        'email',
        'mot_de_passe',
        'nom',
        'prenom',
        'numero',
        'provider',
        'provider_id',
    ];
}
