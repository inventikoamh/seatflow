<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SeatFlow') }} - Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-background to-muted">
        <!-- Theme Toggle -->
        <div class="absolute top-4 right-4">
            <button 
                data-theme-toggle
                class="p-2 rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
                title="Toggle theme"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                </svg>
            </button>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-card shadow-lg overflow-hidden sm:rounded-lg border border-border animate-fade-in">
            <!-- Logo -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-primary">{{ config('app.name', 'SeatFlow') }}</h1>
                <p class="text-muted-foreground mt-2">Create your account to get started.</p>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" data-validate>
                @csrf
                
                <!-- First Name and Last Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-foreground mb-2">
                            First Name
                        </label>
                        <input 
                            id="first_name" 
                            type="text" 
                            name="first_name" 
                            value="{{ old('first_name') }}" 
                            required 
                            autofocus 
                            autocomplete="given-name"
                            class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('first_name') border-destructive @enderror"
                            placeholder="Enter your first name"
                        >
                        @error('first_name')
                            <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-foreground mb-2">
                            Last Name
                        </label>
                        <input 
                            id="last_name" 
                            type="text" 
                            name="last_name" 
                            value="{{ old('last_name') }}" 
                            required 
                            autocomplete="family-name"
                            class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('last_name') border-destructive @enderror"
                            placeholder="Enter your last name"
                        >
                        @error('last_name')
                            <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-foreground mb-2">
                        Email Address
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email"
                        class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('email') border-destructive @enderror"
                        placeholder="Enter your email"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile -->
                <div class="mb-4">
                    <label for="mobile" class="block text-sm font-medium text-foreground mb-2">
                        Mobile Number (Optional)
                    </label>
                    <input 
                        id="mobile" 
                        type="tel" 
                        name="mobile" 
                        value="{{ old('mobile') }}" 
                        autocomplete="tel"
                        class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('mobile') border-destructive @enderror"
                        placeholder="Enter your mobile number"
                    >
                    @error('mobile')
                        <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-foreground mb-2">
                        Password
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('password') border-destructive @enderror"
                        placeholder="Create a password"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-foreground mb-2">
                        Confirm Password
                    </label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground"
                        placeholder="Confirm your password"
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Create Account
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-muted-foreground">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-primary hover:text-primary/80 font-medium transition-colors">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-xs text-muted-foreground">
                © {{ date('Y') }} {{ config('app.name', 'SeatFlow') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
