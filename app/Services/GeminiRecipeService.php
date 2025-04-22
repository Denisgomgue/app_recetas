<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\Content;
use Gemini\Enums\Role;
use Exception;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class GeminiRecipeService
{
    protected string $apiKey;

    public function __construct()
    {
        // Obtenemos la API Key desde la configuración de servicios
        $this->apiKey = config('services.gemini.api_key');
        if (empty($this->apiKey)) {
            // Log de error si la API Key no está configurada
            Log::error('Gemini API Key no está configurada en config/services.php o .env');
            throw new Exception('Gemini API Key no configurada.');
        }
        // Configurar la API Key para el cliente de Gemini (asumiendo que el Facade lo maneja o se configura globalmente)
        // Si no, podríamos necesitar instanciar el cliente aquí:
        // $client = Gemini::client($this->apiKey);
        // $this->geminiClient = $client; // Guardar el cliente si es necesario
    }

    /**
     * Busca recetas en Gemini basadas en una lista de ingredientes.
     *
     * @param array $ingredients Array de strings con los nombres de los ingredientes.
     * @return array Array de recetas encontradas o un array vacío en caso de error.
     */
    public function findRecipesByIngredients(array $ingredients): array
    {
        if (empty($ingredients)) {
            return [];
        }

        $ingredientList = implode(', ', $ingredients);

        // Crear el prompt para Gemini
        // Pedimos explícitamente una respuesta en formato JSON para facilitar el parseo.
        $prompt = <<<PROMPT
        Eres un asistente experto en cocina. Dada la siguiente lista de ingredientes: {$ingredientList}.
        Sugiere 3 recetas que usen principalmente estos ingredientes.
        Para cada receta, proporciona:
        1.  Un título corto y atractivo (campo: "titulo").
        2.  Una descripción muy breve (1 frase) (campo: "descripcion").
        3.  Una lista de los ingredientes principales necesarios (campo: "ingredientes", array de strings).
        4.  Los pasos de la preparación (campo: "instrucciones", array de strings).
        5.  Tiempo estimado de preparación (campo: "tiempo_preparacion", string, ej: "30 minutos").
        6.  Nivel de dificultad (campo: "dificultad", string, ej: "Fácil", "Media", "Difícil").

        IMPORTANTE: Responde únicamente con un array JSON válido que contenga los objetos de las recetas, sin ningún texto introductorio o final. Ejemplo de formato esperado:
        [
          {
            "titulo": "...",
            "descripcion": "...",
            "ingredientes": ["...", "..."],
            "instrucciones": ["...", "..."],
            "tiempo_preparacion": "...",
            "dificultad": "..."
          },
          { ... receta 2 ... },
          { ... receta 3 ... }
        ]
        PROMPT;


        try {
            // Usamos el Facade de Gemini instalado por el paquete google-gemini-php/client
            // El método generateContent es comúnmente usado para prompts de texto.
            // Usamos el modelo gemini-pro por defecto, podrías especificar otro si es necesario.
            $result = Gemini::geminiPro()->generateContent($prompt);

            $rawResponse = $result->text();

            // Intentar decodificar la respuesta JSON
            // Quitamos posibles bloques de código markdown ```json ... ``` que Gemini a veces añade
            $jsonResponse = preg_replace('/^```json\n?(.*)\n?```$/is', '$1', trim($rawResponse));
            $recipes = json_decode($jsonResponse, true);

            // Verificar si la decodificación fue exitosa y es un array
            if (json_last_error() === JSON_ERROR_NONE && is_array($recipes)) {
                // Podríamos añadir validación extra aquí para asegurar que las recetas tienen los campos esperados
                return $recipes;
            } else {
                // Log del error de parseo y la respuesta cruda si falla la decodificación
                Log::error('Error al decodificar JSON de Gemini.', [
                    'json_error' => json_last_error_msg(),
                    'raw_response' => $rawResponse
                ]);
                // Intentar un parseo más robusto o devolver un array vacío
                // TODO: Implementar parseo alternativo si el JSON falla (ej: buscar marcadores en el texto)
                return []; // Devolver vacío si falla el parseo JSON
            }
        } catch (Exception $e) {
            // Loggear cualquier excepción durante la llamada a la API
            Log::error('Error al llamar a la API de Gemini: ' . $e->getMessage());
            return []; // Devolver array vacío en caso de excepción
        }
    }
}
