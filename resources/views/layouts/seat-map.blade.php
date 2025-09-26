<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ auth()->user()->theme_preference ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Seat Map') - {{ config('app.name', 'SeatFlow') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    
    <!-- Prevent theme flash -->
    <script>
        const theme = localStorage.getItem('theme') || '{{ auth()->user()->theme_preference ?? 'light' }}';
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <!-- Full Screen Seat Map -->
    <div class="min-h-screen w-full flex flex-col">
        @yield('content')
    </div>
    
    <!-- Theme Toggle (Floating) -->
    <div class="fixed top-4 right-4 z-50">
        <button 
            id="theme-toggle" 
            class="bg-card border border-border rounded-full p-3 shadow-lg hover:bg-accent transition-colors"
            data-theme-toggle
            title="Toggle theme"
        >
            <svg class="h-5 w-5 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>
    </div>
    
    <!-- Close Button (Floating) -->
    <div class="fixed top-4 left-4 z-50">
        <button 
            onclick="window.close()" 
            class="bg-card border border-border rounded-full p-3 shadow-lg hover:bg-accent transition-colors"
            title="Close window"
        >
            <svg class="h-5 w-5 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</body>
</html>
