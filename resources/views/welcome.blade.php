@extends('layouts.app')

{{-- Título específico para esta página --}}
@section('title', 'Buscar Recetas')

@section('content')
<div class="container">
    {{-- Barra de búsqueda --}}
    <x-search-bar />

    {{-- Sección de ingredientes (Ahora usa el componente) --}}
    <x-ingredients-section />

    {{-- Indicador de carga (oculto por defecto) --}}
    <x-loading-indicator />

    {{-- Estado vacío (se muestra cuando no hay recetas) --}}
    <x-empty-state />

    <!-- Contenedor de recetas -->
    <div class="recipes-container" id="recipesContainer">
        {{-- Las recetas se cargarán aquí dinámicamente mediante JavaScript --}}
    </div>

    {{-- Botón flotante de agregar (Ahora usa el componente) --}}
    <x-add-button />
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Referencias a elementos del DOM --- 
        const searchInput = document.getElementById('searchInput'); 
        const clearSearch = document.getElementById('clearSearch'); 
        const ingredientsTags = document.getElementById('ingredientsTags'); 
        const loadingIndicator = document.getElementById('loadingIndicator'); 
        const emptyState = document.getElementById('emptyState'); 
        const recipesContainer = document.getElementById('recipesContainer'); 
        const addButton = document.getElementById('addButton'); 
        const searchButton = document.getElementById('searchButton'); // Botón Buscar

        // --- LIMPIAR ESTADO INICIAL --- 
        if (ingredientsTags) ingredientsTags.innerHTML = ''; // Asegurar que no haya tags iniciales
        if (recipesContainer) recipesContainer.innerHTML = ''; // Asegurar que no haya recetas iniciales
        if (emptyState) emptyState.style.display = 'flex'; // Mostrar estado vacío por defecto
        if (recipesContainer) recipesContainer.style.display = 'none'; // Ocultar contenedor recetas
        if (loadingIndicator) loadingIndicator.style.display = 'none'; // Ocultar loading

        // --- DEFINICIÓN DE FUNCIONES --- (Definir antes de usar)

        // Función para comprobar si mostrar el estado vacío
        function checkEmptyState() {
            // Comprobación segura de ingredientsTags
            const hasTags = ingredientsTags && ingredientsTags.children.length > 0;
            if (!hasTags) {
                 if (emptyState) emptyState.style.display = 'flex';
            } else {
                 if (emptyState) emptyState.style.display = 'none';
            }
        }

        // Función para añadir un tag (llamada al presionar Enter)
        function addIngredientTag(name) {
            console.log('addIngredientTag llamada con:', name);
            if (!ingredientsTags) {
                console.error('Error: ingredientsTags no encontrado en addIngredientTag');
                 return; 
            }
            const tagWrapper = document.createElement('div'); 
            const safeName = name.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            tagWrapper.innerHTML = `<div class="tag"><span>${safeName}</span><button type="button">×</button></div>`; // Añadir type="button"
            const tagElement = tagWrapper.firstElementChild;
            
            // Listener para el botón de eliminar DENTRO de addIngredientTag
            const deleteButton = tagElement.querySelector('button');
            if (deleteButton) {
                deleteButton.addEventListener('click', function() {
                    this.parentElement.remove();
                    checkEmptyState(); 
                    // findRecipes(); // Sigue comentado
                });
            }

            ingredientsTags.appendChild(tagElement);
            checkEmptyState(); 
        }

        // Función para buscar recetas (sin cambios, solo se llamará manualmente)
        function findRecipes() {
            if (!ingredientsTags || !recipesContainer || !loadingIndicator || !emptyState) {
                console.error("Error: Faltan elementos del DOM para findRecipes.");
                return;
            }
            const ingredientElements = ingredientsTags.querySelectorAll('.tag span');
            const ingredients = Array.from(ingredientElements).map(span => span.textContent);
            
            // Log para ver qué ingredientes se envían (si se habilita console.log)
            console.log('Enviando ingredientes:', ingredients);

            if (ingredients.length === 0) {
                recipesContainer.innerHTML = ''; 
                checkEmptyState();
                return;
            }
            
            loadingIndicator.style.display = 'flex';
            recipesContainer.innerHTML = '';
            recipesContainer.style.display = 'none';
            emptyState.style.display = 'none';
            
             const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
             const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : null;
             
             const fetchConfig = {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'Accept': 'application/json',
                 },
                 body: JSON.stringify({ ingredients: ingredients })
             };
             
             if (csrfToken) {
                 fetchConfig.headers['X-CSRF-TOKEN'] = csrfToken;
             }
             
            fetch('/recipes/search', fetchConfig)
            .then(response => {
                loadingIndicator.style.display = 'none';
                if (!response.ok) {
                     return response.json().then(errData => {
                        throw new Error(`HTTP error ${response.status}: ${JSON.stringify(errData)}`);
                    }).catch(() => {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                // Log para ver qué datos se reciben del backend (si se habilita console.log)
                console.log('Datos recibidos del backend:', data);

                if (data.recipes && data.recipes.length > 0) {
                    renderRecipes(data.recipes);
                    recipesContainer.style.display = ''; 
                    emptyState.style.display = 'none';
                } else {
                    recipesContainer.innerHTML = ''; 
                    recipesContainer.style.display = 'none';
                    checkEmptyState(); 
                }
            })
            .catch(error => {
                loadingIndicator.style.display = 'none';
                console.error('Error buscando recetas:', error);
                recipesContainer.innerHTML = `<div style="color: red; text-align: center; padding: 20px; background-color: #ffebee; border-radius: 8px;">Error al cargar recetas. ${error.message}</div>`;
                recipesContainer.style.display = '';
                emptyState.style.display = 'none';
            });
        }

        // Función para renderizar recetas (sin cambios)
        function renderRecipes(recipesData) {
             if (!recipesContainer || !emptyState) return;
            // ... (código original de renderRecipes) ...
            recipesContainer.innerHTML = ''; 
            if (!recipesData || recipesData.length === 0) {
                checkEmptyState();
                return;
            }
            recipesData.forEach((recipe, index) => {
                const recipeId = `recipe-${index + 1}`;
                const recipeHtml = `
                    <div class="recipe-card-wrapper">
                        <div class="recipe-card">
                            <div class="recipe-avatar">${recipe.titulo ? recipe.titulo.charAt(0).toUpperCase() : 'R'}</div>
                            <div class="recipe-content">
                                <h3 class="recipe-title">${escapeHtml(recipe.titulo || 'Receta sin título')}</h3>
                                <p class="recipe-subtitle">${escapeHtml(recipe.descripcion || '')}</p>
                            </div>
                            <div class="recipe-actions">
                                <button class="action-button toggle-details" data-recipe="${recipeId}" type="button">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <button class="action-button" type="button">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="action-button" type="button">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="recipe-details" id="${recipeId}">
                            ${(recipe.ingredientes && Array.isArray(recipe.ingredientes)) ? `
                            <div class="detail-section">
                                <h4 class="detail-title">Ingredientes:</h4>
                                <ul class="ingredients-list">
                                    ${recipe.ingredientes.map(ing => `<li>${escapeHtml(ing ?? '(Vacío)')}</li>`).join('')}
                                </ul>
                            </div>` : ''}
                            ${(recipe.instrucciones && Array.isArray(recipe.instrucciones)) ? `
                            <div class="detail-section">
                                <h4 class="detail-title">Instrucciones:</h4>
                                <ol class="instructions-list">
                                    ${recipe.instrucciones.map(step => `<li>${escapeHtml(step ?? '(Vacío)')}</li>`).join('')}
                                </ol>
                            </div>` : ''}
                            <div class="recipe-meta">
                                ${recipe.tiempo_preparacion ? `<div class="recipe-meta-item"><i class="far fa-clock"></i><span>${escapeHtml(recipe.tiempo_preparacion ?? '')}</span></div>` : ''}
                                ${recipe.dificultad ? `<div class="recipe-meta-item"><i class="fas fa-fire"></i><span>${escapeHtml(recipe.dificultad ?? '')}</span></div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
                recipesContainer.innerHTML += recipeHtml;
            });
        }
        
        // Función para escapar HTML (sin cambios)
        function escapeHtml(unsafe) {
            if (typeof unsafe !== 'string') {
                 // Si no es string, intentar convertir a string o devolver ''
                 return String(unsafe ?? ''); 
            }
            return unsafe
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
        }

        // --- EVENT LISTENERS --- (Añadir después de definir funciones y con guardas)

        // Listener para el botón Buscar
        if (searchButton) { 
            searchButton.addEventListener('click', findRecipes); // Llama directamente a la función
        } else {
            console.error('Error: Botón de búsqueda (searchButton) no encontrado.');
        }

        // Listener para limpiar búsqueda
        if (clearSearch) { 
            clearSearch.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (searchInput) searchInput.focus();
            });
        } else {
            console.error('Error: Botón de limpiar búsqueda (clearSearch) no encontrado.');
        }

        // Listener para añadir ingredientes
        if (searchInput) { 
            searchInput.addEventListener('keypress', function(e) {
                console.log('Keypress detectado:', e.key);
                const ingredientName = this.value.trim();
                if (e.key === 'Enter' && ingredientName !== '') {
                    console.log('Enter presionado con ingrediente:', ingredientName);
                    addIngredientTag(ingredientName); 
                    this.value = '';
                }
            });
        } else {
             console.error('Error: Campo de entrada de búsqueda (searchInput) no encontrado.');
        }

        // Listener para eliminar tags (delegación)
        if (ingredientsTags) { 
            // Nota: El listener en el botón individual ya se añade en addIngredientTag
            // Este listener de delegación es redundante si el anterior funciona.
            // Lo mantenemos por si acaso, pero asegurando que no llame a findRecipes.
             ingredientsTags.addEventListener('click', function(event) {
                 if (event.target.tagName === 'BUTTON' && event.target.closest('.tag')) {
                     // La eliminación y checkEmptyState ya ocurren en el listener del botón individual.
                     // No es necesario hacerlo de nuevo aquí.
                     // event.target.closest('.tag').remove(); // Redundante
                     // checkEmptyState(); // Redundante
                 }
             });
        } else {
            console.error('Error: Contenedor de tags de ingredientes (ingredientsTags) no encontrado.');
        }

        // Listener para toggle de detalles
        if (recipesContainer) { 
            recipesContainer.addEventListener('click', function(event) {
                const toggleButton = event.target.closest('.toggle-details');
                if (!toggleButton) return; 
                const recipeWrapper = toggleButton.closest('.recipe-card-wrapper');
                 if (!recipeWrapper) return; // Añadir guarda
                const detailsElement = recipeWrapper.querySelector('.recipe-details');
                
                // Cerrar otros detalles...
                document.querySelectorAll('.recipe-details.active').forEach(activeDetail => {
                    if (activeDetail !== detailsElement) {
                        activeDetail.classList.remove('active');
                        const otherWrapper = activeDetail.closest('.recipe-card-wrapper');
                        if (otherWrapper) {
                            const otherIcon = otherWrapper.querySelector('.toggle-details i');
                            if (otherIcon) {
                                 otherIcon.classList.remove('fa-chevron-up');
                                 otherIcon.classList.add('fa-chevron-down');
                            }
                        }
                    }
                });

                // Toggle actual...
                const icon = toggleButton.querySelector('i');
                if (detailsElement && icon) {
                    if (detailsElement.classList.contains('active')) {
                        detailsElement.classList.remove('active');
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    } else {
                        detailsElement.classList.add('active');
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    }
                }
            });
        } else {
             console.error('Error: Contenedor de recetas (recipesContainer) no encontrado.');
        }

        // Listener para el botón de agregar
        if (addButton) { 
            addButton.addEventListener('click', function() {
                alert('Funcionalidad para agregar nueva receta'); 
            });
        } else {
            console.error('Error: Botón de agregar (addButton) no encontrado.');
        }

    });
</script>
@endpush
