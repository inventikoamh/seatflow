@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8 animate-fade-in">
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-foreground mb-2">
                        Welcome back, {{ $user->display_name }}! 👋
                    </h1>
                    <p class="text-muted-foreground">
                        Here's what's happening with your account today.
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

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-md bg-primary/10 flex items-center justify-center">
                        <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-muted-foreground">Total Users</p>
                    <p class="text-2xl font-semibold text-foreground">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-md bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                        <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-muted-foreground">Active Sessions</p>
                    <p class="text-2xl font-semibold text-foreground">1</p>
                </div>
            </div>
        </div>

        <!-- Theme Preference -->
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-md bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                        <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-muted-foreground">Theme</p>
                    <p class="text-2xl font-semibold text-foreground capitalize">{{ $theme }}</p>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-md bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-muted-foreground">Account Status</p>
                    <p class="text-2xl font-semibold text-green-600 dark:text-green-400">Active</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Activity -->
        <div class="bg-card rounded-lg border border-border shadow-sm animate-fade-in">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Recent Activity</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-2 w-2 rounded-full bg-green-500"></div>
                        <div class="flex-1">
                            <p class="text-sm text-foreground">Successfully logged in</p>
                            <p class="text-xs text-muted-foreground">{{ now()->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="h-2 w-2 rounded-full bg-blue-500"></div>
                        <div class="flex-1">
                            <p class="text-sm text-foreground">Theme preference updated</p>
                            <p class="text-xs text-muted-foreground">{{ now()->subMinutes(5)->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="h-2 w-2 rounded-full bg-yellow-500"></div>
                        <div class="flex-1">
                            <p class="text-sm text-foreground">Account created</p>
                            <p class="text-xs text-muted-foreground">{{ $user->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-card rounded-lg border border-border shadow-sm animate-fade-in">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button class="p-4 text-left rounded-lg border border-border hover:bg-accent transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-md bg-primary/10 flex items-center justify-center">
                                <svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-foreground">Update Profile</p>
                                <p class="text-xs text-muted-foreground">Manage your account</p>
                            </div>
                        </div>
                    </button>

                    <button class="p-4 text-left rounded-lg border border-border hover:bg-accent transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-md bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-foreground">Security Settings</p>
                                <p class="text-xs text-muted-foreground">Password & privacy</p>
                            </div>
                        </div>
                    </button>

                    <button class="p-4 text-left rounded-lg border border-border hover:bg-accent transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-md bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                                <svg class="h-4 w-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-foreground">Theme Settings</p>
                                <p class="text-xs text-muted-foreground">Customize appearance</p>
                            </div>
                        </div>
                    </button>

                    <button class="p-4 text-left rounded-lg border border-border hover:bg-accent transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-md bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-foreground">Help & Support</p>
                                <p class="text-xs text-muted-foreground">Get assistance</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Information Card -->
    <!-- Admin Section -->
    <div class="bg-red-50 border-2 border-red-200 rounded-lg shadow-sm animate-fade-in mb-8">
        <div class="p-6 border-b border-red-200">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-red-800">Admin Tools</h3>
            </div>
        </div>
        <div class="p-6">
            <p class="text-red-700 mb-4">
                <strong>⚠️ DANGER ZONE:</strong> These tools can permanently delete all data. Use with extreme caution!
            </p>
            <a href="{{ route('admin.clear-all-data') }}" 
               class="inline-flex items-center bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 font-semibold">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Clear All Data
            </a>
        </div>
    </div>

    <div class="bg-card rounded-lg border border-border shadow-sm animate-fade-in">
        <div class="p-6 border-b border-border">
            <h3 class="text-lg font-semibold text-foreground">Account Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-muted-foreground mb-2">Personal Details</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Name:</span>
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
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Member since:</span>
                            <span class="text-sm font-medium text-foreground">{{ $user->created_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-muted-foreground mb-2">Preferences</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Theme:</span>
                            <span class="text-sm font-medium text-foreground capitalize">{{ $theme }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-foreground">Last login:</span>
                            <span class="text-sm font-medium text-foreground">{{ now()->format('M j, Y g:i A') }}</span>
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
