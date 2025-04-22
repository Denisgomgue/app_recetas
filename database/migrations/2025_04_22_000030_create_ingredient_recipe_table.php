<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredient_recipe', function (Blueprint $table) {
            // No necesitamos id autoincremental ni timestamps aquí normalmente
            // $table->id();

            // Clave foránea para ingredients
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');

            // Clave foránea para recipes
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');

            // Podríamos añadir campos extra a la relación si fuese necesario (ej: cantidad del ingrediente)
            // $table->string('quantity')->nullable();

            // Definimos una clave primaria compuesta para evitar duplicados
            $table->primary(['ingredient_id', 'recipe_id']);

            // No necesitamos timestamps para una tabla pivote simple
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_recipe');
    }
};
