@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <!-- Header -->
    <div class="mb-8 animate-fade-in">
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-foreground mb-2">
                        Profile Settings
                    </h1>
                    <p class="text-muted-foreground">
                        Manage your account information and preferences.
                    </p>
                </div>
                <div class="hidden sm:block">
                    <div class="h-16 w-16 rounded-full bg-primary flex items-center justify-center text-primary-foreground text-2xl font-bold">
                        {{ substr($user->display_name, 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 animate-fade-in">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Information -->
        <div class="bg-card rounded-lg border border-border shadow-sm animate-slide-in">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Personal Information</h3>
                <p class="text-sm text-muted-foreground mt-1">Update your personal details</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('profile.update') }}" data-validate>
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-foreground mb-2">
                                First Name
                            </label>
                            <input 
                                id="first_name" 
                                type="text" 
                                name="first_name" 
                                value="{{ old('first_name', $user->first_name) }}" 
                                required 
                                class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('first_name') border-destructive @enderror"
                                placeholder="Enter your first name"
                            >
                            @error('first_name')
                                <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-foreground mb-2">
                                Last Name
                            </label>
                            <input 
                                id="last_name" 
                                type="text" 
                                name="last_name" 
                                value="{{ old('last_name', $user->last_name) }}" 
                                required 
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
                            value="{{ old('email', $user->email) }}" 
                            required 
                            class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('email') border-destructive @enderror"
                            placeholder="Enter your email"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mobile -->
                    <div class="mb-6">
                        <label for="mobile" class="block text-sm font-medium text-foreground mb-2">
                            Mobile Number
                        </label>
                        <input 
                            id="mobile" 
                            type="tel" 
                            name="mobile" 
                            value="{{ old('mobile', $user->mobile) }}" 
                            class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('mobile') border-destructive @enderror"
                            placeholder="Enter your mobile number"
                        >
                        @error('mobile')
                            <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Update Profile
                    </button>
                </form>
            </div>
        </div>

        <!-- Password Change -->
        <div class="bg-card rounded-lg border border-border shadow-sm animate-slide-in">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Change Password</h3>
                <p class="text-sm text-muted-foreground mt-1">Update your account password</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('profile.password.update') }}" data-validate>
                    @csrf
                    @method('PUT')
                    
                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-foreground mb-2">
                            Current Password
                        </label>
                        <input 
                            id="current_password" 
                            type="password" 
                            name="current_password" 
                            required 
                            class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('current_password') border-destructive @enderror"
                            placeholder="Enter your current password"
                        >
                        @error('current_password')
                            <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-foreground mb-2">
                            New Password
                        </label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('password') border-destructive @enderror"
                            placeholder="Enter your new password"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-foreground mb-2">
                            Confirm New Password
                        </label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground"
                            placeholder="Confirm your new password"
                        >
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Account Information -->
    <div class="mt-6 bg-card rounded-lg border border-border shadow-sm animate-fade-in">
        <div class="p-6 border-b border-border">
            <h3 class="text-lg font-semibold text-foreground">Account Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-muted-foreground mb-2">Account Details</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Full Name:</span>
                            <span class="text-sm font-medium text-foreground">{{ $user->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Email:</span>
                            <span class="text-sm font-medium text-foreground">{{ $user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Mobile:</span>
                            <span class="text-sm font-medium text-foreground">{{ $user->mobile ?: 'Not provided' }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-muted-foreground mb-2">Account Status</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Member since:</span>
                            <span class="text-sm font-medium text-foreground">{{ $user->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Last updated:</span>
                            <span class="text-sm font-medium text-foreground">{{ $user->updated_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Status:</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
