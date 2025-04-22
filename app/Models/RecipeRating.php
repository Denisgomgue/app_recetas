<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeRating extends Model
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
        'rating',
        'comment',
    ];

    /**
     * Define la relación inversa uno a muchos con Recipe.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Define la relación inversa uno a muchos con User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
