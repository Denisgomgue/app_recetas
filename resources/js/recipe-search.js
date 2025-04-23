// Recipe search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.querySelector('.search-button, #search-button, button.buscar');
    // Removed ingredientsList querySelector here as it's better to get fresh list on click
    const errorMessageElement = document.querySelector('.error-message');

    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault(); // Keep this

            // Get the current list of ingredient tags *inside* the click handler
            const ingredientTags = document.querySelectorAll('.ingredient-tag');
            const ingredients = [];

            // Extract text content from each tag
            ingredientTags.forEach(tag => {
                let text = tag.textContent.trim();
                text = text.replace('×', '').trim(); // Remove the close button text
                if (text) { // Ensure we don't add empty strings
                    ingredients.push(text);
                }
            });

            // Check if we have ingredients *before* calling searchRecipes
            if (ingredients.length > 0) {
                console.log('Button clicked. Sending ingredients:', ingredients); // Log ingredients being sent
                searchRecipes(ingredients); // Call the search function
            } else {
                console.log('Button clicked. No ingredients selected.'); // Log if no ingredients found
                if (errorMessageElement) {
                    errorMessageElement.textContent = 'Por favor, ingresa al menos un ingrediente.';
                }
            }
        });
    }

    function searchRecipes(ingredients) {
        // Add log here to confirm ingredients arrived
        console.log('Inside searchRecipes. Ingredients received:', ingredients);

        // Clear any previous error message
        if (errorMessageElement) {
            errorMessageElement.textContent = '';
        }

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'); // Use optional chaining

        // Make API request - ensure the URL is exactly '/recipes/search'
        fetch('/recipes/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '' // Ensure token is being sent
            },
            body: JSON.stringify({ ingredients: ingredients })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                // Log the response body for 404 errors to see if there's more info
                return response.text().then(text => {
                    console.error('Error response body:', text);
                    throw new Error('HTTP error! status: ' + response.status);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Recipes received:', data);
            displayRecipes(data);
        })
        .catch(error => {
            console.error('Error buscando recetas:', error);
            if (errorMessageElement) {
                // Display the specific error message from the catch block
                errorMessageElement.textContent = `Error al cargar recetas. ${error.message}`;
            }
        });
    }
    
    function displayRecipes(recipes) {
        // Find or create a container for the recipes
        let resultsContainer = document.querySelector('.results-container');
        if (!resultsContainer) {
            resultsContainer = document.createElement('div');
            resultsContainer.className = 'results-container';
            // Try to insert it logically, e.g., after the ingredients list or error message
            const ingredientsSection = document.querySelector('.ingredients-section');
            if (ingredientsSection) {
                ingredientsSection.parentNode.insertBefore(resultsContainer, ingredientsSection.nextSibling);
            } else {
                 document.querySelector('.main-content')?.appendChild(resultsContainer); // Append to main content
            }
        }

        // Clear previous results
        resultsContainer.innerHTML = '';

        if (!recipes || recipes.length === 0 || recipes.rawResponse) { // Check for rawResponse too
             if (recipes.rawResponse) {
                console.warn('Received raw response from backend:', recipes.rawResponse);
                resultsContainer.innerHTML = `<p class="no-results">La IA no devolvió un formato de receta válido. Respuesta: ${recipes.rawResponse}</p>`;
             } else {
                resultsContainer.innerHTML = '<p class="no-results">No se encontraron recetas con estos ingredientes.</p>';
             }
            return;
        }

        // Create HTML for each recipe
        recipes.forEach(recipe => {
            const recipeCard = document.createElement('div');
            recipeCard.className = 'recipe-card'; // Add a class for styling

            // Use the correct property names based on your API response
            const name = recipe.nombre || recipe.name || 'Receta sin nombre';
            const description = recipe.descripcion || recipe.description || '';
            const ingredientsList = recipe.ingredientes || recipe.ingredients || [];
            const instructions = recipe.instrucciones || recipe.instructions || 'Instrucciones no disponibles.';

            recipeCard.innerHTML = `
                <h3 class="recipe-title">${name}</h3>
                ${description ? `<p class="recipe-description">${description}</p>` : ''}
                <div class="recipe-ingredients">
                    <h4>Ingredientes:</h4>
                    <ul>
                        ${Array.isArray(ingredientsList) && ingredientsList.length > 0 ?
                          ingredientsList.map(ingredient => `<li>${ingredient}</li>`).join('') :
                          '<li>Ingredientes no especificados.</li>'}
                    </ul>
                </div>
                <div class="recipe-instructions">
                    <h4>Instrucciones:</h4>
                    <p>${instructions.replace(/\n/g, '<br>')}</p> <!-- Replace newlines with <br> for display -->
                </div>
            `;

            resultsContainer.appendChild(recipeCard);
        });
    }
});