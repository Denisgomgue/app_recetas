<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'instructions',
        // Add other fillable fields
    ];
    
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }
}
