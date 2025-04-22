<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\BelongsTo; // Para la relación con User si se añade

class Recipe extends Model
{
    // use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'instructions',
        'preparation_time',
        'difficulty',
        // 'user_id' // Añadir si se implementa la relación con User
    ];

    /**
     * Define la relación muchos a muchos con Ingredient.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class);
    }

    /**
     * Define la relación uno a muchos con RecipePhoto.
     */
    public function photos(): HasMany
    {
        // Eloquent asume la clave foránea 'recipe_id' en la tabla 'recipe_photos'
        return $this->hasMany(RecipePhoto::class);
    }

    /**
     * Define la relación uno a muchos con RecipeRating.
     */
    public function ratings(): HasMany
    {
        // Eloquent asume la clave foránea 'recipe_id' en la tabla 'recipe_ratings'
        return $this->hasMany(RecipeRating::class);
    }

    /**
     * Define la relación inversa uno a uno/muchos con User (Opcional).
     */
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }

    // Podríamos añadir un método accesor para calcular la valoración media
    // public function getAverageRatingAttribute(): ?float
    // {
    //     return $this->ratings()->avg('rating');
    // }
}
