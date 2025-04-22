<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\RecipeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Now create something great!
|
*/

// Ruta principal que muestra la vista welcome a través del RecipeController
Route::get('/', [RecipeController::class, 'index'])->name('home');

// Ruta para obtener sugerencias de recetas
Route::post('/sugerencias', [RecetaController::class, 'sugerencias'])->name('sugerencias');

// Ejemplo de ruta de autenticación (si se usa Sanctum u otro)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
