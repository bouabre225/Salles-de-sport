<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;


class SocialAuthController extends Controller
{
    public function redirect ($provider) {
        return response()->json(Socialite::driver($provider)->redirect());
    }

    public function callback ($provider) {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Vérifier si un user existe déjà avec ce provider_id
            $user = Utilisateur::where('provider', $provider)
                        ->where('provider_id', $socialUser->getId())
                        ->first();

            if (!$user) {
                // Si l’email existe déjà mais sans provider_id, on peut le lier
                $user = Utilisateur::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                    ]);
                } else {
                    // Créer un nouveau user
                    $user = Utilisateur::create([
                        'nom' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'mot_de_passe' => bcrypt(str()->random(16)), // mot de passe random
                    ]);
                }
            }

                Auth::login($user);

            return response()->json([
                'message' => "Connecté via $provider",
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Une erreur est survenue lors de la connexion'. $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
