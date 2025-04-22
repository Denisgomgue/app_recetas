<div class="loading-indicator" id="loadingIndicator" {{ $attributes->merge(['style' => 'display: none;']) }}>
    <div class="spinner"></div>
    <p>{{ $message ?? 'Buscando recetas...' }}</p>
</div> 