<?php

use App\Http\Controllers\DashboardController; // Ton nouveau contrôleur
use App\Http\Controllers\ProfileController;   // Le contrôleur de Breeze
use Illuminate\Support\Facades\Route;

// 1. Page d'accueil publique (L'Observatoire public)
Route::get('/', [DashboardController::class, 'index'])->name('home');

// 2. L'espace connecté (Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Les routes de profil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. Les routes d'authentification (Breeze)
require __DIR__.'/auth.php';