<?php

namespace App\Http\Controllers;

use App\Services\GeminiRecipeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RecipeController extends Controller
{
    protected GeminiRecipeService $geminiService;

    public function __construct(GeminiRecipeService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

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

    /**
     * Busca recetas basadas en ingredientes usando Gemini.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        // Validar la entrada
        $validated = $request->validate([
            'ingredients' => 'required|array',
            'ingredients.*' => 'string|max:100' // Validar cada ingrediente como string
        ]);

        $ingredients = $validated['ingredients'];

        try {
            // Llamar al servicio para obtener las recetas
            $recipes = $this->geminiService->findRecipesByIngredients($ingredients);

            // Log temporal para depuración
            Log::debug('Recetas devueltas por GeminiService:', ['recipes' => $recipes]);

            // Devolver las recetas encontradas como JSON
            return response()->json(['recipes' => $recipes]);
        } catch (\Exception $e) {
            // Registrar el error si el servicio falla (además del log dentro del servicio)
            Log::error('Error en RecipeController@search: ' . $e->getMessage());

            // Devolver una respuesta de error JSON
            return response()->json(['error' => 'No se pudieron obtener las recetas.'], 500);
        }
    }

    // Aquí añadiremos más métodos como storePhoto, storeRating, etc.
}
