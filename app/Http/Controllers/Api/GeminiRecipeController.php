<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiRecipeController extends Controller
{
    /**
     * Search for recipes using Gemini API based on ingredients
     */
    public function search(Request $request)
    {
        Log::info('Search route hit successfully!'); // Log to confirm route is reached
        $ingredients = $request->input('ingredients');
        Log::info('Received ingredients', ['ingredients' => $ingredients]);

        // --- Remove the temporary test response ---
        /*
        return response()->json([
            ['nombre' => 'Test Recipe 1', 'descripcion' => 'A simple test recipe.', 'ingredientes' => $ingredients, 'instrucciones' => 'Mix and serve.'],
            ['nombre' => 'Test Recipe 2', 'descripcion' => 'Another test recipe.', 'ingredientes' => $ingredients, 'instrucciones' => 'Just a test.'],
        ]);
        */

        // --- Re-enable the Gemini API call logic ---
        try {
            if (empty($ingredients)) {
                return response()->json([
                    'message' => 'No ingredients provided'
                ], 400);
            }

            // Your Gemini API key from .env
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) {
                Log::error('GEMINI_API_KEY not found in .env file.');
                return response()->json(['message' => 'API key configuration error.'], 500);
            }

            // Gemini API endpoint
            $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

            // Format ingredients for the prompt
            $ingredientsList = is_array($ingredients) ? implode(', ', $ingredients) : $ingredients;

            // Create prompt for Gemini
            $prompt = "Dame recetas que puedo hacer con estos ingredientes: $ingredientsList. Formatea la respuesta como JSON con la siguiente estructura: [{\"nombre\": \"Nombre de la Receta\", \"descripcion\": \"Breve descripción\", \"ingredientes\": [\"ingrediente1\", \"ingrediente2\"], \"instrucciones\": \"Instrucciones paso a paso\"}]";

            Log::info('Sending request to Gemini API', ['prompt' => $prompt]);

            // Make request to Gemini API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("$endpoint?key=$apiKey", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            Log::info('Gemini API response', ['status' => $response->status(), 'body' => $response->body()]);

            // Check if request was successful
            if ($response->successful()) {
                $data = $response->json();

                // Extract the text response from Gemini
                // Updated path based on potential Gemini response structure
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($text) {
                    // Try to parse the JSON response (robust parsing)
                    $jsonStart = strpos($text, '[');
                    $jsonEnd = strrpos($text, ']');

                    if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
                        $jsonText = substr($text, $jsonStart, ($jsonEnd - $jsonStart) + 1);
                        // Attempt to clean potential markdown code block fences
                        $jsonText = trim($jsonText);
                        if (strpos($jsonText, '```json') === 0) {
                            $jsonText = substr($jsonText, 7);
                        }
                        if (substr($jsonText, -3) === '```') {
                            $jsonText = substr($jsonText, 0, -3);
                        }
                        $jsonText = trim($jsonText);

                        $recipes = json_decode($jsonText, true);

                        if (json_last_error() === JSON_ERROR_NONE) {
                            Log::info('Successfully parsed recipes from Gemini response.');
                            return response()->json($recipes);
                        } else {
                            Log::error('Failed to parse JSON from Gemini response.', ['json_error' => json_last_error_msg(), 'raw_text' => $text]);
                            return response()->json(['message' => 'Error parsing recipes from AI.', 'raw_response' => $text], 500);
                        }
                    } else {
                         Log::warning('Could not find valid JSON array structure in Gemini response.', ['raw_text' => $text]);
                         return response()->json(['message' => 'Could not extract recipe data from AI response.', 'raw_response' => $text], 500);
                    }
                } else {
                    Log::warning('No text content found in Gemini response candidate.');
                    return response()->json(['message' => 'No content received from AI.'], 500);
                }
            } else {
                 Log::error('Gemini API request failed.', ['status' => $response->status(), 'body' => $response->body()]);
                 return response()->json([
                    'message' => 'Failed to get recipes from Gemini API',
                    'error' => $response->body()
                ], $response->status()); // Use the actual status code from Gemini
            }

        } catch (\Exception $e) {
            Log::error('Exception in GeminiRecipeController', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'An error occurred while processing your request',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}