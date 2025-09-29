<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Public\CoachRegister;
use App\Http\Requests\Public\CoachLogin;
use Illuminate\Support\Facades\Auth;
use App\Models\Coach;
use Illuminate\Support\Facades\Hash;

class CoachController extends Controller
{
    /**
     * Inscription d'un coach
     */
    public function register(CoachRegister $request)
    {
        try {
            //valiser les donnees 
            $validated = $request->validated();

            //hasher le mot de passe
            $validated['mot_de_passe'] = Hash::make($validated['mot_de_passe']);

            //creer le coach
            $coach = Coach::create([
                'email' => $validated['email'],
                'mot_de_passe' => $validated['mot_de_passe'],
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'cip' => $validated['cip'],
            ]);

            //generer un token sanctum
            $token = $coach->createToken('auth-token')->plainTextToken;
            //reponse json
            return response()->json([
                'success' => true,
                'message' => 'Coach inscrit avec succes',
                'coach' => $coach,
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
     * Connexion d'un coach
     */
    public function login(CoachLogin $request) {
        try {
            //valider les donnees
            $validated = $request->validated();

            //verifier si le coach existe
            $coach = Coach::where('email', $validated['email'])->first();
            if (!$coach) {
                return response()->json([
                    'error' => true,
                    'message' => 'Coach non trouvé',
                    'data' => null,
                ], 404);
            }

            //verifier si le mot de passe est correct
            if (!Hash::check($validated['mot_de_passe'], $coach->mot_de_passe)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Mot de passe incorrect',
                    'data' => null,
                ], 401);
            }

            //authentifier le coach
            Auth::login($coach);

            //generer un token sanctum
            $token = $coach->createToken('auth-token')->plainTextToken;

            //retourne la reponse en cas de succes
            return response()->json([
                'error' => false,
                'message' => 'Connexion reussie',
                'data' => $coach,
                'token' => $token,
            ], 200);
            
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
     * Deconnecter le coach
     */
    public function logout(Request $request) {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'Coach non trouvé',
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
