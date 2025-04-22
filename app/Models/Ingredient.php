<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    // use HasFactory; // Descomentar si se usan factories

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Define la relación muchos a muchos con Recipe.
     */
    public function recipes(): BelongsToMany
    {
        // Eloquent asume la tabla pivote 'ingredient_recipe' por convención
        // y las claves foráneas 'ingredient_id' y 'recipe_id'.
        return $this->belongsToMany(Recipe::class);
    }
}
