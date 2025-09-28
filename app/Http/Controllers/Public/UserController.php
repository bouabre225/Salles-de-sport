<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\UserRegister;
use App\Http\Requests\Public\UserLogin;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Inscription de l'utilisateur
     */
    public function register (UserRegister $request){
        try {
            //validation des données
            $validated = $request->validated();

            //creation du l'utilisateur
            $user = Utilisateur::create([
                'email' => $validated['email'],
                'mot_de_passe' => bcrypt($validated['mot_de_passe']),
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'numero' => $validated['numero'],
            ]);

            //authentification de l'utilisateur
            Auth::guard('utilisateurs')->login($user);

            //retourne la reponse en cas de succes
            return response()->json([
                'error' => false,
                'message' => 'Inscription reussie',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            //retourne la reponse en cas d'erreur
            return response()->json([
                'error' => true,
                'message' => 'Une erreur est survenue lors de l\'inscription'. $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Connexion de l'utilisateur
     */
    public function login (UserLogin $request){
        try {
            //validation des données
            $validated = $request->validated();

            //authentification de l'utilisateur
            if (Auth::guard('utilisateurs')->attempt(['email' => $validated['email'], 'mot_de_passe' => $validated['mot_de_passe']])) {
                //retourne la reponse en cas de succes
                return response()->json([
                    'error' => false,
                    'message' => 'Connexion reussie',
                    'data' => Auth::guard('utilisateurs')->user(),
                ], 200);
            } else {
                //retourne la reponse en cas d'erreur
                return response()->json([
                    'error' => true,
                    'message' => 'Une erreur est survenue lors de la connexion',
                    'data' => null,
                ], 500);
            }
        } catch (\Exception $e) {
            //retourne la reponse en cas d'erreur
            return response()->json([
                'error' => true,
                'message' => 'Une erreur est survenue lors de la connexion'. $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
