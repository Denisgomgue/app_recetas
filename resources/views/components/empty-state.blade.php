<div class="empty-state" id="emptyState" {{ $attributes->merge(['style' => 'display: none;']) }}>
    <div class="empty-icon">
        <i class="fas fa-utensils"></i>
    </div>
    <p class="empty-text">{{ $message ?? 'Ingresa algunos ingredientes para obtener sugerencias de recetas' }}</p>
</div> 