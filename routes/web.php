<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
// Comenta o elimina la importación incorrecta si existe
// use App\Http\Controllers\Api\GeminiRecipeController; 

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Corregir esta ruta para que apunte a RecipeController
Route::post('/recipes/search', [RecipeController::class, 'search'])->name('recipes.search');

require __DIR__ . '/auth.php';
