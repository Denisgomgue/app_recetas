<!-- Botón flotante de agregar -->
{{-- El ID es importante para que el JS pueda encontrar este botón --}}
<button {{ $attributes->merge(['class' => 'add-button', 'id' => 'addButton']) }}>
    <i class="fas fa-plus"></i>
    {{ $slot }} {{-- Permitir pasar contenido adicional si es necesario --}}
</button> 