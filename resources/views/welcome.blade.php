<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Recetas</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Styles -->
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-light: #A5D6A7;
            --primary-dark: #2E7D32;
            --secondary-color: #FF5722;
            --secondary-light: #FFAB91;
            --text-color: #333333;
            --text-light: #757575;
            --background-color: #F5F5F5;
            --card-color: #FFFFFF;
            --border-radius-sm: 8px;
            --border-radius-md: 12px;
            --border-radius-lg: 24px;
            --border-radius-xl: 32px;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 8px rgba(0,0,0,0.12);
            --shadow-lg: 0 8px 16px rgba(0,0,0,0.14);
            --spacing-xs: 4px;
            --spacing-sm: 8px;
            --spacing-md: 16px;
            --spacing-lg: 24px;
            --spacing-xl: 32px;
            --transition-fast: 0.2s ease;
            --transition-normal: 0.3s ease;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Layout principal */
        .app-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
        }

        .main-content {
            flex: 1;
            padding-bottom: 70px; /* Espacio para nav móvil */
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            transition: padding var(--transition-normal);
        }

        .container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            padding: var(--spacing-md);
        }

        /* Header */
        .app-header {
            background-color: var(--card-color);
            padding: var(--spacing-md) var(--spacing-md);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .app-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: var(--spacing-xs);
            text-align: center;
        }

        .app-subtitle {
            font-size: 0.9rem;
            color: var(--text-light);
            text-align: center;
            margin-bottom: var(--spacing-md);
        }

        /* Barra de búsqueda */
        .search-container {
            position: relative;
            margin-bottom: var(--spacing-lg);
        }

        .search-input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 1px solid #E0E0E0;
            border-radius: var(--border-radius-lg);
            font-size: 1rem;
            background-color: var(--card-color);
            transition: all var(--transition-fast);
            box-shadow: var(--shadow-sm);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }

        .search-clear {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            transition: all var(--transition-fast);
        }

        .search-clear:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--text-color);
        }

        /* Sección de ingredientes */
        .ingredients-section {
            margin-bottom: var(--spacing-lg);
        }

        .section-title {
            font-weight: 500;
            font-size: 1rem;
            margin-bottom: var(--spacing-md);
            color: var(--text-color);
        }

        .ingredient-tags {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-md);
        }

        .tag {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            padding: 6px 12px;
            border-radius: var(--border-radius-lg);
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all var(--transition-fast);
        }

        .tag span {
            margin-right: var(--spacing-sm);
        }

        .tag button {
            background: none;
            border: none;
            color: var(--primary-dark);
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            transition: all var(--transition-fast);
        }

        .tag button:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        /* Indicador de carga */
        .loading-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: var(--spacing-xl) 0;
            color: var(--text-light);
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(76, 175, 80, 0.2);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s linear infinite;
            margin-bottom: var(--spacing-md);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Tarjetas de recetas */
        .recipes-container {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .recipe-card {
            background-color: var(--card-color);
            border-radius: var(--border-radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-fast);
            display: flex;
            padding: var(--spacing-md);
        }

        .recipe-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .recipe-avatar {
            width: 48px;
            height: 48px;
            background-color: var(--primary-light);
            color: var(--primary-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.2rem;
            margin-right: var(--spacing-md);
            flex-shrink: 0;
        }

        .recipe-content {
            flex-grow: 1;
            min-width: 0; /* Para que el texto se corte correctamente */
        }

        .recipe-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .recipe-subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .recipe-actions {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-xs);
            margin-left: var(--spacing-md);
        }

        .action-button {
            width: 36px;
            height: 36px;
            border-radius: var(--border-radius-sm);
            background-color: var(--background-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            border: none;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .action-button:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        .add-button {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            border: none;
            cursor: pointer;
            position: fixed;
            bottom: 80px;
            right: 20px;
            box-shadow: var(--shadow-md);
            z-index: 5;
            transition: all var(--transition-fast);
        }

        .add-button:hover {
            background-color: #E64A19;
            transform: scale(1.05);
        }

        /* Navegación inferior (móvil) */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: var(--card-color);
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            font-size: 0.75rem;
            padding: 6px 0;
            transition: all var(--transition-fast);
            position: relative;
        }

        .nav-item .icon {
            font-size: 1.4rem;
            margin-bottom: 4px;
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 3px;
        }

        /* Sidebar (escritorio) */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--card-color);
            height: 100vh;
            position: fixed;
            left: -100%;
            top: 0;
            z-index: 20;
            box-shadow: var(--shadow-lg);
            transition: left var(--transition-normal);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: var(--spacing-lg);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .sidebar-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-light);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            transition: all var(--transition-fast);
        }

        .sidebar-close:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--text-color);
        }

        .sidebar-content {
            padding: var(--spacing-lg);
            flex: 1;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            padding: var(--spacing-md);
            border-radius: var(--border-radius-md);
            text-decoration: none;
            color: var(--text-color);
            transition: all var(--transition-fast);
        }

        .sidebar-nav-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .sidebar-nav-item.active {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        .sidebar-nav-item .icon {
            font-size: 1.2rem;
            margin-right: var(--spacing-md);
            width: 24px;
            text-align: center;
        }

        .sidebar-footer {
            padding: var(--spacing-lg);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: 0.8rem;
            color: var(--text-light);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Botón de menú (escritorio) */
        .menu-button {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: var(--card-color);
            color: var(--text-color);
            border: none;
            cursor: pointer;
            z-index: 15;
            box-shadow: var(--shadow-md);
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all var(--transition-fast);
        }

        .menu-button:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        /* Overlay para sidebar en móvil */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 15;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-normal);
        }

        /* Detalles de receta expandibles */
        .recipe-details {
            display: none;
            padding: var(--spacing-md);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin-top: var(--spacing-md);
        }

        .recipe-details.active {
            display: block;
        }

        .detail-section {
            margin-bottom: var(--spacing-md);
        }

        .detail-title {
            font-weight: 600;
            margin-bottom: var(--spacing-xs);
            font-size: 0.9rem;
        }

        .ingredients-list, .instructions-list {
            padding-left: var(--spacing-lg);
            font-size: 0.9rem;
        }

        .ingredients-list li, .instructions-list li {
            margin-bottom: var(--spacing-xs);
        }

        .recipe-meta {
            display: flex;
            gap: var(--spacing-md);
            margin-top: var(--spacing-md);
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .recipe-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Estado vacío */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-xl) var(--spacing-md);
            text-align: center;
            color: var(--text-light);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: var(--spacing-md);
            color: var(--primary-light);
        }

        .empty-text {
            font-size: 1rem;
            max-width: 300px;
            line-height: 1.5;
        }

        /* Media queries para responsive */
        @media (min-width: 768px) {
            .bottom-nav {
                display: none;
            }

            .menu-button {
                display: flex;
            }

            .main-content {
                padding-bottom: var(--spacing-md);
                max-width: 1200px;
            }

            .container {
                max-width: 800px;
                padding: var(--spacing-lg);
            }

            .add-button {
                bottom: 30px;
                right: 30px;
            }

            .recipes-container {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                gap: var(--spacing-lg);
            }
        }

        @media (min-width: 1024px) {
            .sidebar {
                left: 0;
            }

            .menu-button {
                display: none;
            }

            .main-content {
                padding-left: var(--sidebar-width);
            }

            .app-header {
                padding-left: calc(var(--sidebar-width) + var(--spacing-md));
            }

            .add-button {
                right: 40px;
            }

            /* Clase para cuando el sidebar está abierto en móvil */
            body.sidebar-open .sidebar {
                left: 0;
            }

            body.sidebar-open .sidebar-overlay {
                opacity: 1;
                visibility: visible;
            }
        }

        @media (max-width: 1023px) {
            body.sidebar-open .sidebar {
                left: 0;
            }

            body.sidebar-open .sidebar-overlay {
                opacity: 1;
                visibility: visible;
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="app-container">
        <!-- Sidebar para escritorio -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">Recetas IA</div>
                <button class="sidebar-close" id="sidebarClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="sidebar-content">
                <nav class="sidebar-nav">
                    <a href="#" class="sidebar-nav-item">
                        <i class="fas fa-home icon"></i>
                        Inicio
                    </a>
                    <a href="#" class="sidebar-nav-item">
                        <i class="fas fa-comments icon"></i>
                        Chat
                    </a>
                    <a href="#" class="sidebar-nav-item">
                        <i class="fas fa-shopping-basket icon"></i>
                        Compras
                    </a>
                    <a href="#" class="sidebar-nav-item active">
                        <i class="fas fa-camera icon"></i>
                        Reconocer
                    </a>
                    <a href="#" class="sidebar-nav-item">
                        <i class="fas fa-book icon"></i>
                        Mis recetas
                    </a>
                    <a href="#" class="sidebar-nav-item">
                        <i class="fas fa-cog icon"></i>
                        Ajustes
                    </a>
                </nav>
            </div>
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">U</div>
                    <div class="user-info">
                        <div class="user-name">Usuario</div>
                        <div class="user-email">usuario@ejemplo.com</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overlay para sidebar en móvil -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Botón de menú para móvil/tablet -->
        <button class="menu-button" id="menuButton">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Header -->
        <header class="app-header">
            <h1 class="app-title">Recetas IA</h1>
            <p class="app-subtitle">Encuentra recetas con los ingredientes que tienes</p>
        </header>

        <!-- Contenido principal -->
        <main class="main-content">
            <div class="container">
                <!-- Barra de búsqueda -->
                <div class="search-container">
                    <input type="text" id="searchInput" class="search-input" placeholder="Cebolla, tomate...">
                    <button class="search-clear" id="clearSearch">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Sección de ingredientes -->
                <div class="ingredients-section">
                    <h2 class="section-title">Ingredientes ingresados</h2>
                    <div class="ingredient-tags" id="ingredientsTags">
                        <div class="tag"><span>Cilantro</span><button>×</button></div>
                        <div class="tag"><span>Tarwi</span><button>×</button></div>
                        <div class="tag"><span>Limón</span><button>×</button></div>
                        <div class="tag"><span>Perejil</span><button>×</button></div>
                        <div class="tag"><span>Pimienta</span><button>×</button>
                    </div>
                </div>

                <!-- Indicador de carga (oculto por defecto) -->
                <div class="loading-indicator" id="loadingIndicator" style="display: none;">
                    <div class="spinner"></div>
                    <p>Buscando recetas...</p>
                </div>

                <!-- Estado vacío (se muestra cuando no hay recetas) -->
                <div class="empty-state" id="emptyState" style="display: none;">
                    <div class="empty-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <p class="empty-text">Ingresa algunos ingredientes para obtener sugerencias de recetas</p>
                </div>

                <!-- Contenedor de recetas -->
                <div class="recipes-container" id="recipesContainer">
                    <!-- Ejemplo de tarjeta de receta -->
                    <div class="recipe-card">
                        <div class="recipe-avatar">R</div>
                        <div class="recipe-content">
                            <h3 class="recipe-title">Ensalada de cilantro y limón</h3>
                            <p class="recipe-subtitle">Una refrescante ensalada con cilantro, limón y especias</p>
                        </div>
                        <div class="recipe-actions">
                            <button class="action-button toggle-details" data-recipe="1">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <button class="action-button">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="action-button">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Detalles expandibles de la receta -->
                    <div class="recipe-details" id="recipe-details-1">
                        <div class="detail-section">
                            <h4 class="detail-title">Ingredientes:</h4>
                            <ul class="ingredients-list">
                                <li>1 manojo de cilantro fresco</li>
                                <li>2 limones</li>
                                <li>1 cucharadita de pimienta negra</li>
                                <li>1/2 taza de perejil picado</li>
                                <li>2 cucharadas de aceite de oliva</li>
                                <li>Sal al gusto</li>
                            </ul>
                        </div>
                        <div class="detail-section">
                            <h4 class="detail-title">Instrucciones:</h4>
                            <ol class="instructions-list">
                                <li>Lavar y picar finamente el cilantro y el perejil</li>
                                <li>Exprimir el jugo de los limones</li>
                                <li>Mezclar todos los ingredientes en un bowl</li>
                                <li>Agregar sal y pimienta al gusto</li>
                                <li>Servir frío como acompañamiento</li>
                            </ol>
                        </div>
                        <div class="recipe-meta">
                            <div class="recipe-meta-item">
                                <i class="far fa-clock"></i>
                                <span>15 min</span>
                            </div>
                            <div class="recipe-meta-item">
                                <i class="fas fa-fire"></i>
                                <span>Fácil</span>
                            </div>
                            <div class="recipe-meta-item">
                                <i class="fas fa-utensils"></i>
                                <span>4 porciones</span>
                            </div>
                        </div>
                    </div>

                    <!-- Más tarjetas de recetas -->
                    <div class="recipe-card">
                        <div class="recipe-avatar">R</div>
                        <div class="recipe-content">
                            <h3 class="recipe-title">Salsa de limón y hierbas</h3>
                            <p class="recipe-subtitle">Perfecta para acompañar pescados y mariscos</p>
                        </div>
                        <div class="recipe-actions">
                            <button class="action-button toggle-details" data-recipe="2">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <button class="action-button">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="action-button">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Detalles expandibles de la receta -->
                    <div class="recipe-details" id="recipe-details-2">
                        <div class="detail-section">
                            <h4 class="detail-title">Ingredientes:</h4>
                            <ul class="ingredients-list">
                                <li>3 limones</li>
                                <li>2 cucharadas de cilantro picado</li>
                                <li>1 cucharada de perejil picado</li>
                                <li>1/2 cucharadita de pimienta</li>
                                <li>3 cucharadas de aceite de oliva</li>
                                <li>1 diente de ajo (opcional)</li>
                                <li>Sal al gusto</li>
                            </ul>
                        </div>
                        <div class="detail-section">
                            <h4 class="detail-title">Instrucciones:</h4>
                            <ol class="instructions-list">
                                <li>Exprimir el jugo de los limones en un bowl</li>
                                <li>Picar finamente el cilantro, perejil y ajo</li>
                                <li>Mezclar todos los ingredientes</li>
                                <li>Agregar sal y pimienta al gusto</li>
                                <li>Dejar reposar por 10 minutos antes de servir</li>
                            </ol>
                        </div>
                        <div class="recipe-meta">
                            <div class="recipe-meta-item">
                                <i class="far fa-clock"></i>
                                <span>10 min</span>
                            </div>
                            <div class="recipe-meta-item">
                                <i class="fas fa-fire"></i>
                                <span>Fácil</span>
                            </div>
                            <div class="recipe-meta-item">
                                <i class="fas fa-utensils"></i>
                                <span>6 porciones</span>
                            </div>
                        </div>
                    </div>

                    <div class="recipe-card">
                        <div class="recipe-avatar">R</div>
                        <div class="recipe-content">
                            <h3 class="recipe-title">Aderezo de hierbas</h3>
                            <p class="recipe-subtitle">Un aderezo versátil para ensaladas y platos fríos</p>
                        </div>
                        <div class="recipe-actions">
                            <button class="action-button toggle-details" data-recipe="3">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <button class="action-button">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="action-button">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Detalles expandibles de la receta -->
                    <div class="recipe-details" id="recipe-details-3">
                        <div class="detail-section">
                            <h4 class="detail-title">Ingredientes:</h4>
                            <ul class="ingredients-list">
                                <li>2 cucharadas de cilantro picado</li>
                                <li>2 cucharadas de perejil picado</li>
                                <li>1 limón (jugo)</li>
                                <li>1/4 cucharadita de pimienta</li>
                                <li>1/4 taza de aceite de oliva</li>
                                <li>1 cucharadita de miel</li>
                                <li>Sal al gusto</li>
                            </ul>
                        </div>
                        <div class="detail-section">
                            <h4 class="detail-title">Instrucciones:</h4>
                            <ol class="instructions-list">
                                <li>Mezclar el jugo de limón con la miel</li>
                                <li>Agregar el aceite de oliva poco a poco mientras se bate</li>
                                <li>Incorporar las hierbas picadas</li>
                                <li>Sazonar con sal y pimienta</li>
                                <li>Refrigerar por 30 minutos antes de usar</li>
                            </ol>
                        </div>
                        <div class="recipe-meta">
                            <div class="recipe-meta-item">
                                <i class="far fa-clock"></i>
                                <span>5 min + 30 min reposo</span>
                            </div>
                            <div class="recipe-meta-item">
                                <i class="fas fa-fire"></i>
                                <span>Fácil</span>
                            </div>
                            <div class="recipe-meta-item">
                                <i class="fas fa-utensils"></i>
                                <span>4 porciones</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón flotante de agregar -->
                <button class="add-button" id="addButton">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </main>

        <!-- Navegación inferior (móvil) -->
        <nav class="bottom-nav">
            <a href="#" class="nav-item">
                <i class="fas fa-comments icon"></i>
                <span>Chat</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-shopping-basket icon"></i>
                <span>Compras</span>
            </a>
            <a href="#" class="nav-item active">
                <i class="fas fa-camera icon"></i>
                <span>Reconocer</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-book icon"></i>
                <span>Mis recetas</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-cog icon"></i>
                <span>Ajustes</span>
            </a>
        </nav>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias a elementos del DOM
            const menuButton = document.getElementById('menuButton');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const searchInput = document.getElementById('searchInput');
            const clearSearch = document.getElementById('clearSearch');
            const toggleButtons = document.querySelectorAll('.toggle-details');
            const addButton = document.getElementById('addButton');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const emptyState = document.getElementById('emptyState');
            const recipesContainer = document.getElementById('recipesContainer');
            const ingredientsTags = document.getElementById('ingredientsTags');

            // Función para abrir el sidebar
            function openSidebar() {
                document.body.classList.add('sidebar-open');
            }

            // Función para cerrar el sidebar
            function closeSidebar() {
                document.body.classList.remove('sidebar-open');
            }

            // Event listeners para el sidebar
            menuButton.addEventListener('click', openSidebar);
            sidebarClose.addEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Event listener para limpiar la búsqueda
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.focus();
            });

            // Event listeners para los botones de toggle de detalles
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const recipeId = this.getAttribute('data-recipe');
                    const detailsElement = document.getElementById(`recipe-details-${recipeId}`);
                    
                    // Cerrar todos los detalles abiertos
                    document.querySelectorAll('.recipe-details').forEach(el => {
                        if (el.id !== `recipe-details-${recipeId}`) {
                            el.classList.remove('active');
                        }
                    });
                    
                    // Resetear todos los iconos
                    document.querySelectorAll('.toggle-details').forEach(btn => {
                        if (btn !== this) {
                            btn.innerHTML = '<i class="fas fa-chevron-down"></i>';
                        }
                    });
                    
                    // Toggle del detalle actual
                    if (detailsElement.classList.contains('active')) {
                        detailsElement.classList.remove('active');
                        this.innerHTML = '<i class="fas fa-chevron-down"></i>';
                    } else {
                        detailsElement.classList.add('active');
                        this.innerHTML = '<i class="fas fa-chevron-up"></i>';
                    }
                });
            });

            // Event listener para el botón de agregar
            addButton.addEventListener('click', function() {
                alert('Funcionalidad para agregar nueva receta');
            });

            // Event listeners para eliminar tags de ingredientes
            const tagButtons = document.querySelectorAll('.tag button');
            tagButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.remove();
                    
                    // Si no quedan ingredientes, mostrar estado vacío
                    if (ingredientsTags.children.length === 0) {
                        recipesContainer.style.display = 'none';
                        emptyState.style.display = 'flex';
                    }
                });
            });

            // Event listener para agregar ingredientes al presionar Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.trim() !== '') {
                    // Crear nuevo tag
                    const tag = document.createElement('div');
                    tag.className = 'tag';
                    tag.innerHTML = `<span>${this.value.trim()}</span><button>×</button>`;
                    
                    // Agregar event listener al botón de eliminar
                    tag.querySelector('button').addEventListener('click', function() {
                        this.parentElement.remove();
                        
                        // Si no quedan ingredientes, mostrar estado vacío
                        if (ingredientsTags.children.length === 0) {
                            recipesContainer.style.display = 'none';
                            emptyState.style.display = 'flex';
                        }
                    });
                    
                    // Agregar tag al contenedor
                    ingredientsTags.appendChild(tag);
                    
                    // Limpiar input
                    this.value = '';
                    
                    // Simular búsqueda
                    simulateSearch();
                }
            });

            // Función para simular búsqueda
            function simulateSearch() {
                // Ocultar recetas y estado vacío
                recipesContainer.style.display = 'none';
                emptyState.style.display = 'none';
                
                // Mostrar indicador de carga
                loadingIndicator.style.display = 'flex';
                
                // Simular tiempo de carga
                setTimeout(function() {
                    // Ocultar indicador de carga
                    loadingIndicator.style.display = 'none';
                    
                    // Mostrar recetas
                    recipesContainer.style.display = '';
                }, 1500);
            }
        });
    </script>
</body>
</html>
