@props(['recipe', 'recipeId']) {{-- Espera un array/objeto $recipe y un ID único --}}

<div class="recipe-card-wrapper">
    <div class="recipe-card">
        {{-- Avatar con la inicial del título o 'R' por defecto --}}
        <div class="recipe-avatar">{{ $recipe['titulo'] ? strtoupper(substr($recipe['titulo'], 0, 1)) : 'R' }}</div>
        <div class="recipe-content">
            <h3 class="recipe-title">{{ $recipe['titulo'] ?? 'Receta sin título' }}</h3>
            <p class="recipe-subtitle">{{ $recipe['descripcion'] ?? '' }}</p>
        </div>
        <div class="recipe-actions">
            {{-- Botón para mostrar/ocultar detalles --}}
            <button class="action-button toggle-details" data-recipe="{{ $recipeId }}">
                <i class="fas fa-chevron-down"></i>
            </button>
            {{-- Botón de Like (funcionalidad pendiente) --}}
            <button class="action-button">
                <i class="far fa-heart"></i>
            </button>
            {{-- Botón de Compartir (funcionalidad pendiente) --}}
            <button class="action-button">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>
    </div>
    <!-- Detalles expandibles de la receta -->
    <div class="recipe-details" id="{{ $recipeId }}">
        {{-- Mostrar ingredientes si existen --}}
        @if (!empty($recipe['ingredientes']))
        <div class="detail-section">
            <h4 class="detail-title">Ingredientes:</h4>
            <ul class="ingredients-list">
                @foreach ($recipe['ingredientes'] as $ingredient)
                <li>{{ $ingredient ?? '(Ingrediente no especificado)' }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Mostrar instrucciones si existen --}}
        @if (!empty($recipe['instrucciones']))
        <div class="detail-section">
            <h4 class="detail-title">Instrucciones:</h4>
            <ol class="instructions-list">
                @foreach ($recipe['instrucciones'] as $step)
                <li>{{ $step ?? '(Paso no especificado)' }}</li>
                @endforeach
            </ol>
        </div>
        @endif

        {{-- Mostrar metadatos (tiempo, dificultad) --}}
        <div class="recipe-meta">
            @if (!empty($recipe['tiempo_preparacion']))
            <div class="recipe-meta-item"><i class="far fa-clock"></i><span>{{ $recipe['tiempo_preparacion'] }}</span></div>
            @endif
            @if (!empty($recipe['dificultad']))
            <div class="recipe-meta-item"><i class="fas fa-fire"></i><span>{{ $recipe['dificultad'] }}</span></div>
            @endif
            {{-- Podríamos añadir porciones si Gemini lo devuelve --}}
            {{-- <div class="recipe-meta-item"><i class="fas fa-utensils"></i><span>Placeholder porciones</span></div> --}}
        </div>
    </div>
</div> 