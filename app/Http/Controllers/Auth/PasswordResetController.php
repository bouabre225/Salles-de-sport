<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Envoyer le mail de renitialisation du mot de passe
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:utilisateurs,email',
        ]);

        //Envoi du lien de réinitialisation
        $status = Password::sendResetLink(
            $request->only('email')
        );

        //Le lien de réinitialisation a été envoyé par email
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Lien de réinitialisation envoyé par email.']);
        }

        //Impossible d'envoyer le lien
        return response()->json(['message' => "Impossible d'envoyer le lien."], 500);
    }

    /**
     * Reinitialiser le mot de passe
     */
    public function resetPassword(Request $request) {
        //Validation des données
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:8|confirmed',
        ]);

        //Reinitialisation du mot de passe
        $status = Password::reset(
            $request->only('email', 'mot_de_passe', 'token'),
            function ($user, $password) {
                $user->mot_de_passe = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        //Le mot de passe a été réinitialisé avec succès
        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Mot de passe réinitialisé avec succès.']);
        }
        //Le token est invalide ou expiré
        return response()->json(['message' => 'Le token est invalide ou expiré.'], 400);
    }
}
