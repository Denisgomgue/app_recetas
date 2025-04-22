<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RecipeController extends Controller
{
    /**
     * Muestra la página principal de la aplicación.
     *
     * @return View
     */
    public function index(): View
    {
        // Simplemente devuelve la vista welcome por ahora.
        // Más adelante podríamos pasarle datos (ej: recetas populares, ingredientes)
        return view('welcome');
    }

    // Aquí añadiremos más métodos como search, storePhoto, storeRating, etc.
}
