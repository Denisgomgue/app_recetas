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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            // Clave foránea opcional para el usuario creador
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title'); // Título de la receta
            $table->text('description')->nullable(); // Descripción breve
            $table->text('instructions'); // Pasos de la preparación
            $table->string('preparation_time')->nullable(); // Ej: "30 minutos"
            $table->string('difficulty')->nullable(); // Ej: "Fácil", "Media", "Difícil"
            // Podríamos añadir más campos como porciones, origen (API, usuario), etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
