<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeApiController extends Controller
{
    /**
     * Display a listing of the recipes.
     */
    public function index()
    {
        $recipes = Recipe::all();
        return response()->json($recipes);
    }

    /**
     * Search for recipes by ingredients.
     */
    public function search(Request $request)
    {
        $ingredients = $request->input('ingredients');
        
        // For debugging
        \Log::info('Search request received', ['ingredients' => $ingredients]);
        
        if (!$ingredients) {
            return response()->json([]);
        }
        
        // Convert to array if it's a string
        if (is_string($ingredients)) {
            $ingredients = explode(',', $ingredients);
        }
        
        // For now, just return all recipes to test the endpoint
        $recipes = Recipe::all();
        
        return response()->json($recipes);
    }

    /**
     * Display the specified recipe.
     */
    public function show($id)
    {
        $recipe = Recipe::findOrFail($id);
        return response()->json($recipe);
    }
}