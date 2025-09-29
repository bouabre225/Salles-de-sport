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

            //valider les conditions generales d'utilisation obligatoire
            $user->cgu = true;
            $user->save();

            // Générer un token Sanctum
            $token = $user->createToken('auth-token')->plainTextToken;

            //retourne la reponse en cas de succes
            return response()->json([
                'error' => false,
                'message' => 'Inscription reussie',
                'data' => $user,
                'token' => $token,
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

            
            if (!Auth::attempt($validated)) {
                return response()->json(['message' => 'Identifiants incorrects'], 401);
            }

            //verifier si l'utilisateur existe
            $user = Utilisateur::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'Utilisateur non trouvé',
                    'data' => null,
                ], 404);
            }
            //authentification de l'utilisateur
            if (Auth::attempt($validated)) {
                // Générer un token Sanctum
                $token = $user->createToken('auth-token')->plainTextToken;

                //retourne la reponse en cas de succes
                return response()->json([
                    'error' => false,
                    'message' => 'Connexion reussie',
                    'data' => Auth::user(),
                    'token' => $token,
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

    /**
     * Deconnexion de l'utilisateur
     */
    public function Logout (Request $request) {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'Utilisateur non trouvé',
                    'data' => null,
                ], 404);
            } else {
                //deconnexion de l'utilisateur avec le token
                $user->currentAccessToken()->delete();
            }

            //retourne la reponse en cas de succes
            return response()->json([
                'error' => false,
                'message' => 'Deconnexion reussie',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            //retourne la reponse en cas d'erreur
            return response()->json([
                'error' => true,
                'message' => 'Une erreur est survenue lors de la deconnexion'. $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

}
