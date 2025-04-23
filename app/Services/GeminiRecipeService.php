<?php

namespace App\Services;

// Quitar o comentar el Facade que no funciona
// use Gemini\Laravel\Facades\Gemini;
use Gemini\Client; // <--- Importar la clase cliente directamente (ASUNCIÓN)
use Gemini\Data\Content;
use Gemini\Enums\Role;
use Exception;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Gemini\Enums\ModelType; // <-- Necesario para el modelo?

class GeminiRecipeService
{
    protected string $apiKey;
    protected Client $geminiClient; // <--- Propiedad para guardar el cliente

    public function __construct()
    {
        // Obtenemos la API Key desde la configuración de servicios
        $this->apiKey = config('services.gemini.api_key');
        if (empty($this->apiKey)) {
            // Log de error si la API Key no está configurada
            Log::error('Gemini API Key no está configurada en config/services.php o .env');
            throw new Exception('Gemini API Key no configurada.');
        }

        // Instanciar el cliente directamente
        try {
            $this->geminiClient = \Gemini::client($this->apiKey); // <--- Crear instancia aquí
        } catch (\Throwable $th) {
            Log::error('Error al instanciar el cliente Gemini: ' . $th->getMessage());
            throw new Exception('No se pudo inicializar el cliente Gemini.');
        }
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
            // Log para ver el prompt enviado
            Log::debug('Enviando prompt a Gemini:', ['prompt' => $prompt]);

            // Usar la instancia del cliente con la sintaxis correcta (esperada)
            $result = $this->geminiClient->generativeModel('gemini-1.0-pro')->generateContent($prompt);
            // Líneas anteriores incorrectas comentadas:
            // $result = $this->geminiClient->geminiPro()->generateContent($prompt); // ASUNCIÓN basada en uso anterior
            // $result = Gemini::generativeModel('gemini-1.0-pro')->generateContent($prompt); // Usando Facade

            $rawResponse = $result->text();

            // Log para ver la respuesta cruda recibida
            Log::debug('Respuesta cruda de Gemini:', ['response' => $rawResponse]);

            // Intentar decodificar la respuesta JSON
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
