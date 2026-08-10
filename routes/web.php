<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Accueil
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Routes authentifiees (communes)
|--------------------------------------------------------------------------
| 'compte.actif' deconnecte tout utilisateur dont le compte a ete desactive.
*/
Route::middleware(['auth', 'compte.actif'])->group(function () {

    // ---- Dashboard (Membre 1) ----
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---- Profil (Breeze) ----
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |----------------------------------------------------------------------
    | Module Utilisateurs & Statistiques - Membre 1 (admin uniquement)
    |----------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/activer', [UserController::class, 'activate'])->name('users.activate');

        Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques.index');
    });

    /*
    |----------------------------------------------------------------------
    | Module Documents / Recherche / Suivi / Archivage - Membre 2 (Rayen)
    |----------------------------------------------------------------------
    | A decommenter et completer par Membre 2. Ne pas modifier la section
    | Membre 1 ci-dessus pour eviter les conflits Git.
    |
    | Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    | Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    | Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    | Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    | Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    | Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    |
    | Route::get('/recherche', [RechercheController::class, 'index'])->name('recherche.index');
    |
    | Route::get('/documents/{document}/suivi', [SuiviController::class, 'show'])->name('suivi.show');
    | Route::patch('/documents/{document}/suivi', [SuiviController::class, 'update'])->name('suivi.update');
    |
    | Route::get('/archivage', [ArchivageController::class, 'index'])->name('archivage.index');
    */
});

require __DIR__.'/auth.php';
