<!-- Sidebar para escritorio -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-title">Recetas IA</div>
        <button class="sidebar-close" id="sidebarClose" type="button">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="sidebar-content">
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-nav-item"> {{-- Enlace de ejemplo --}}
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
            <a href="#" class="sidebar-nav-item active"> {{-- Activo por defecto? --}}
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
            {{-- Mejor obtener info del usuario autenticado --}}
            {{-- <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div> --}}
            <div class="user-avatar">U</div> {{-- Placeholder --}}
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name ?? 'Usuario' }}</div>
                <div class="user-email">{{ Auth::user()->email ?? 'usuario@ejemplo.com' }}</div>
            </div>
        </div>
    </div>
</div> 