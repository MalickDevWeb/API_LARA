<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\TransactionController;
use Laravel\Passport\Http\Controllers\AccessTokenController;

// -----------------------------
// Route test API
// -----------------------------
Route::get('/', function () {
    return response()->json(['message' => 'API Laravel fonctionne !']);
});

// -----------------------------
// Passport routes
// -----------------------------
Route::post('oauth/token', [AccessTokenController::class, 'issueToken']);
Route::post('oauth/refresh', [AccessTokenController::class, 'refresh']);

// -----------------------------
// Version 1 de l'API
// -----------------------------
Route::prefix('v1')->group(function () {

    // -----------------------------
    // Routes utilisateurs
    // -----------------------------
    // Use explicit id parameter names to avoid implicit route-model binding
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);                 // Liste tous les users
        Route::get('/{userId}', [UserController::class, 'show']);         // Détail user (id)
        Route::post('/', [UserController::class, 'store'])->middleware('auth:api');               // Créer un user (auth required)
        Route::put('/{userId}', [UserController::class, 'update'])->middleware('auth:api');       // Modifier un user (auth required)
        Route::delete('/{userId}', [UserController::class, 'destroy'])->middleware(['auth:api','role:ADMIN']);   // Supprimer un user (admin)
    });

    // -----------------------------
    // Routes comptes
    // -----------------------------
    Route::prefix('comptes')->group(function () {
        Route::get('/', [CompteController::class, 'index']);                      // Liste tous les comptes
        Route::get('/{compteId}', [CompteController::class, 'show']);            // Détail compte (id)
        Route::post('/', [CompteController::class, 'store']);                    // Créer un compte
        Route::put('/{compteId}', [CompteController::class, 'update'])->middleware('auth:api');          // Modifier un compte
        Route::delete('/{compteId}', [CompteController::class, 'destroy'])->middleware('auth:api');      // Supprimer un compte
        // Actions spéciales sur compte (bloquer/débloquer) sont définies plus bas et protégées par role:ADMIN
    });

    // -----------------------------
    // Routes transactions
    // -----------------------------
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);                  // Liste toutes les transactions
        Route::get('/{transactionId}', [TransactionController::class, 'show']);    // Détail transaction (id)
        Route::post('/', [TransactionController::class, 'store'])->middleware('auth:api');                // Créer une transaction (auth required)
        Route::put('/{transactionId}', [TransactionController::class, 'update'])->middleware('auth:api'); // Modifier une transaction (auth required)
        Route::delete('/{transactionId}', [TransactionController::class, 'destroy'])->middleware('auth:api'); // Supprimer une transaction (auth required)
    });

    // -----------------------------
    // Middleware pour rôles ADMIN
    // -----------------------------
    Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
    // Actions spécifiques sur les comptes
    Route::post('comptes/{compteId}/bloquer', [CompteController::class, 'bloquer']);
    Route::post('comptes/{compteId}/debloquer', [CompteController::class, 'debloquer']);
});


});
