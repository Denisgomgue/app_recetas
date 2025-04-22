<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <!-- Iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts & Styles (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')

    </head>
    <body class="font-sans antialiased">

        <div class="app-container">

            @include('partials.sidebar')

            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <button class="menu-button" id="menuButton" type="button">
                <i class="fas fa-bars"></i>
            </button>

            @include('partials.header')

            <main class="main-content">
                @yield('content')
            </main>

            @include('partials.bottom-nav')

        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const menuButton = document.getElementById('menuButton');
                const sidebarClose = document.getElementById('sidebarClose');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                const body = document.body;

                function openSidebar() {
                    if (body) body.classList.add('sidebar-open');
                }

                function closeSidebar() {
                    if (body) body.classList.remove('sidebar-open');
                }

                if (menuButton) menuButton.addEventListener('click', openSidebar);
                if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
                if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
            });
        </script>

        @stack('scripts')

    </body>
</html>
