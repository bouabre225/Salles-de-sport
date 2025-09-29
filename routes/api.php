<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\UserController;
use App\Http\Controllers\Auth\SocialAuthController;


Route::get('/', function () {
    return view('welcome');
});

//Route d'inscription et de connexion 
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

//Route de connexion via les providers
Route::get('/login/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('/login/{provider}/callback', [SocialAuthController::class, 'callback']);

//Route de deconnexion 
Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
});