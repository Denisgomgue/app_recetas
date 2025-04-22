<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecetaRequest;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;

class RecetaController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Obtiene sugerencias de recetas basadas en los ingredientes proporcionados
     *
     * @param RecetaRequest $request
     * @return JsonResponse
     */
    public function sugerencias(RecetaRequest $request): JsonResponse
    {
        // Obtener ingredientes validados
        $ingredientes = $request->ingredientes;
        
        // Llamar al servicio de Gemini para obtener recetas
        $recetas = $this->geminiService->obtenerRecetas($ingredientes);
        
        // Devolver respuesta JSON
        return response()->json([
            'success' => true,
            'recetas' => $recetas
        ]);
    }
}
