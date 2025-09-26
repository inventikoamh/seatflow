<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $theme ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SeatFlow') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Prevent theme flash -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.add(theme);
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen">
        @if(auth()->check())
            <!-- Sidebar -->
            @include('components.sidebar', ['sidebarOpen' => $sidebarOpen ?? false])

            <!-- Top Navigation -->
            @include('components.top-nav', ['breadcrumbs' => $breadcrumbs ?? null])
        @endif

        <!-- Page Content -->
        <main class="@if(auth()->check()) lg:ml-64 @endif">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('user-menu');
            const button = event.target.closest('button');
            
            if (menu && !menu.contains(event.target) && !button?.onclick) {
                menu.classList.add('hidden');
            }
        });

        // Sidebar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            // Toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            }

            // Close sidebar
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Collapsible menu functionality
            const menuToggles = document.querySelectorAll('[id$="-menu-toggle"]');
            menuToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const menuId = this.id.replace('-toggle', '');
                    const menu = document.getElementById(menuId);
                    const arrow = document.getElementById(menuId + '-arrow');
                    
                    if (menu && arrow) {
                        menu.classList.toggle('hidden');
                        arrow.classList.toggle('rotate-180');
                    }
                });
            });
        });
    </script>
</body>
</html>
