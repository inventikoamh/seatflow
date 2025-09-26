@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-foreground">Settings</h1>
        <p class="text-muted-foreground mt-2">Server management and maintenance tools</p>
    </div>

    <!-- Warning Banner -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
        <div class="flex">
            <svg class="w-6 h-6 text-yellow-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="text-lg font-medium text-yellow-800">Admin Only Access</h3>
                <p class="text-yellow-700 mt-1">
                    These settings are restricted to administrators only. Use with caution as they can affect the entire application.
                </p>
            </div>
        </div>
    </div>

    <!-- Settings Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Migrations Card -->
        <div class="bg-card p-6 rounded-lg border border-border hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-foreground">Database Migrations</h3>
                    <p class="text-sm text-muted-foreground">Manage database schema</p>
                </div>
            </div>
            <p class="text-muted-foreground mb-4">
                Run pending migrations, rollback changes, or execute specific migration files.
            </p>
            <a href="{{ route('settings.migrations') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Manage Migrations
            </a>
        </div>

        <!-- Seeders Card -->
        <div class="bg-card p-6 rounded-lg border border-border hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-foreground">Database Seeders</h3>
                    <p class="text-sm text-muted-foreground">Populate database with data</p>
                </div>
            </div>
            <p class="text-muted-foreground mb-4">
                Run seeders to populate the database with initial data, roles, permissions, and sample records.
            </p>
            <a href="{{ route('settings.seeders') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Manage Seeders
            </a>
        </div>

        <!-- Storage Card -->
        <div class="bg-card p-6 rounded-lg border border-border hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-foreground">Storage Management</h3>
                    <p class="text-sm text-muted-foreground">Manage file storage</p>
                </div>
            </div>
            <p class="text-muted-foreground mb-4">
                Create storage links, manage file uploads, and clear application cache.
            </p>
            <a href="{{ route('settings.storage') }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Manage Storage
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-card p-6 rounded-lg border border-border">
        <h2 class="text-xl font-semibold text-foreground mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="POST" action="{{ route('settings.cache.clear') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors"
                        onclick="return confirm('Are you sure you want to clear all application cache?')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clear Application Cache
                </button>
            </form>
            
            <a href="{{ route('settings.migrations') }}" 
               class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Run All Pending Migrations
            </a>
        </div>
    </div>
</div>
@endsection
