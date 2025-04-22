<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipePhoto extends Model
{
    // use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'recipe_id',
        'user_id',
        'path',
    ];

    /**
     * Define la relación inversa uno a muchos con Recipe.
     */
    public function recipe(): BelongsTo
    {
        // Eloquent asume la clave foránea 'recipe_id' por convención.
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Define la relación inversa uno a muchos con User.
     */
    public function user(): BelongsTo
    {
        // Eloquent asume la clave foránea 'user_id' por convención.
        return $this->belongsTo(User::class);
    }
}
