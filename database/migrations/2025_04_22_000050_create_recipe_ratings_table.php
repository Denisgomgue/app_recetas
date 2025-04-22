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
        Schema::create('recipe_ratings', function (Blueprint $table) {
            $table->id();
            // Clave foránea para la receta valorada
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            // Clave foránea para el usuario que valora
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Valoración (ej. 1 a 5)
            $table->unsignedTinyInteger('rating');
            // Comentario opcional
            $table->text('comment')->nullable();
            $table->timestamps();

            // Restricción única: un usuario solo puede valorar una receta una vez
            $table->unique(['recipe_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ratings');
    }
};
