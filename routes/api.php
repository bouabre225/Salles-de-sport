<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\UserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Public\SalleController;
use App\Http\Controllers\Public\CoachController;

Route::get('/', function () {
    return view('welcome');
});

//Route d'inscription et de connexion de l'utilisateur
Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
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