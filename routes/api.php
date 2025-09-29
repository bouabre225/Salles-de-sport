<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\UserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Public\SalleController;
use App\Http\Controllers\Public\CoachController;

/*Route::get('/', function () {
    return view('welcome');
});*/

//Route d'inscription et de connexion de l'utilisateur
Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    //Route de reinitialisation du mot de passe
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
    //Route d'envoi de mail de reinitialisation du mot de passe
    Route::post('/send-reset-password-link', [UserController::class, 'sendResetPasswordLink']);
});

Route::prefix('email')->group(function () {
    //Envoyer l'email de verification 
    Route::post('/verify', [UserController::class, 'sendVerificationEmail'])->middleware(['auth:sanctum']);

    //Verification de l'email
    Route::get('/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])->middleware(['auth:sanctum', 'signed']);
});

//Route d'inscription et de connexion du coach
Route::prefix('coach')->group(function () {
    Route::post('/register', [CoachController::class, 'register']);
    Route::post('/login', [CoachController::class, 'login']);
});

//Route de connexion via les providers
Route::get('/login/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('/login/{provider}/callback', [SocialAuthController::class, 'callback']);

//Route de deconnexion de l'utilisateur
Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
});

//Route de deconnexion du coach
Route::middleware('auth:sanctum')->prefix('coach')->group(function () {
    Route::post('/logout', [CoachController::class, 'logout']);
});

//Route pour creer une salle 
Route::prefix('salles')->group(function () {
    Route::post('/create', [SalleController::class, 'create']);
});