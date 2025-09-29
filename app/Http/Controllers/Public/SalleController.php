<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SalleSport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SalleController extends Controller
{
    /**
     * Creer une salle de sport
     */
    public function create(Request $request) {
        try {
            //valider les donnees
            $validated = $request->validate([
                'email' => 'required|email|unique:salles_sports,email',
                'mot_de_passe' => 'required|string|min:8',
                'nom' => 'required|string',
                'nom_proprietaire' => 'required|string',
                'rccm' => 'required|numeric',
                'adresse' => 'required|string',
                'horaires' => 'required|string',
                'type_sport' => 'required|string',
            ]);

            //hasher le mot de passe
            $validated['mot_de_passe'] = Hash::make($validated['mot_de_passe']);

            //creer la salle de sport
            $salle = SalleSport::create($validated);

            //retourne la reponse en cas de succes
            return response()->json([
                'success' => true,
                'message' => 'Salle de sport creer avec succes',
                'salle' => $salle,
            ], 200);
        } catch (\Exception $e) {
            //retourne la reponse en cas d'erreur
            return response()->json([
                'error' => true,
                'message' => 'Une erreur est survenue lors de la creation de la salle de sport'. $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
