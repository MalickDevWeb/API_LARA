<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\TransactionController;

// -----------------------------
// Route test API
// -----------------------------
Route::get('/', function () {
    return response()->json(['message' => 'API Laravel fonctionne !']);
});

// -----------------------------
// Version 1 de l'API
// -----------------------------
Route::prefix('v1')->group(function () {

    // -----------------------------
    // Routes utilisateurs
    // -----------------------------
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);                 // Liste tous les users
        Route::get('/{user}', [UserController::class, 'show']);           // Détail user (model binding)
        Route::post('/', [UserController::class, 'store']);               // Créer un user
        Route::put('/{user}', [UserController::class, 'update']);         // Modifier un user
        Route::delete('/{user}', [UserController::class, 'destroy']);     // Supprimer un user
    });

    // -----------------------------
    // Routes comptes
    // -----------------------------
    Route::prefix('comptes')->group(function () {
        Route::get('/', [CompteController::class, 'index']);                 // Liste tous les comptes
        Route::get('/{compte}', [CompteController::class, 'show']);         // Détail compte (model binding)
        Route::post('/', [CompteController::class, 'store']);               // Créer un compte
        Route::put('/{compte}', [CompteController::class, 'update']);       // Modifier un compte
        Route::delete('/{compte}', [CompteController::class, 'destroy']);   // Supprimer un compte

        // Actions spéciales sur compte
        Route::post('/{compte}/bloquer', [CompteController::class, 'bloquer']);
        Route::post('/{compte}/debloquer', [CompteController::class, 'debloquer']);
    });

    // -----------------------------
    // Routes transactions
    // -----------------------------
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);               // Liste toutes les transactions
        Route::get('/{transaction}', [TransactionController::class, 'show']);  // Détail transaction (model binding)
        Route::post('/', [TransactionController::class, 'store']);             // Créer une transaction
    });

    // -----------------------------
    // Middleware pour rôles ADMIN
    // -----------------------------
   Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
    // Gestion des utilisateurs par ADMIN
    Route::delete('users/{user}', [UserController::class, 'destroy']);
    
    // Actions spécifiques sur les comptes
    Route::post('comptes/{compte}/bloquer', [CompteController::class, 'bloquer']);
    Route::post('comptes/{compte}/debloquer', [CompteController::class, 'debloquer']);
});


});
