@extends('layouts.app')

@section('title', 'Storage Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Storage Management</h1>
            <p class="text-muted-foreground mt-2">Manage file storage and application cache</p>
        </div>
        <a href="{{ route('settings.index') }}" 
           class="bg-muted text-muted-foreground px-4 py-2 rounded-md hover:bg-muted/90 transition-colors">
            Back to Settings
        </a>
    </div>

    <!-- Storage Status -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Storage Directory -->
        <div class="bg-card p-6 rounded-lg border border-border">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-foreground">Storage Directory</h3>
                    <p class="text-sm text-muted-foreground">storage/app/</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-muted-foreground">Status:</span>
                    <span class="text-sm font-medium {{ $storageInfo['storage_exists'] ? 'text-green-600' : 'text-red-600' }}">
                        {{ $storageInfo['storage_exists'] ? 'Exists' : 'Not Found' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-muted-foreground">Size:</span>
                    <span class="text-sm font-medium text-foreground">
                        {{ \App\Http\Controllers\SettingsController::formatBytes($storageInfo['storage_size']) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Public Storage Link -->
        <div class="bg-card p-6 rounded-lg border border-border">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-foreground">Public Storage Link</h3>
                    <p class="text-sm text-muted-foreground">public/storage/</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-muted-foreground">Status:</span>
                    <span class="text-sm font-medium {{ $storageInfo['is_linked'] ? 'text-green-600' : 'text-red-600' }}">
                        {{ $storageInfo['is_linked'] ? 'Linked' : 'Not Linked' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-muted-foreground">Size:</span>
                    <span class="text-sm font-medium text-foreground">
                        {{ \App\Http\Controllers\SettingsController::formatBytes($storageInfo['public_storage_size']) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-card p-6 rounded-lg border border-border mb-6">
        <h2 class="text-xl font-semibold text-foreground mb-4">Storage Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="POST" action="{{ route('settings.storage.link') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors"
                        onclick="return confirm('This will delete the existing public/storage directory and create a new symbolic link. Continue?')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    Create Storage Link
                </button>
            </form>
            
            <form method="POST" action="{{ route('settings.cache.clear') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors"
                        onclick="return confirm('This will clear all application cache. Continue?')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clear Application Cache
                </button>
            </form>
        </div>
    </div>

    <!-- Storage Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Storage Information</h3>
        <div class="text-sm text-blue-800 space-y-2">
            <p><strong>Storage Directory:</strong> <code class="bg-blue-100 px-2 py-1 rounded">storage/app/</code></p>
            <p><strong>Public Link:</strong> <code class="bg-blue-100 px-2 py-1 rounded">public/storage/</code></p>
            <p><strong>Purpose:</strong> The storage link allows uploaded files to be accessible via web URLs.</p>
            <p><strong>Note:</strong> If the link is broken or missing, uploaded files won't be accessible through the web interface.</p>
        </div>
    </div>

    <!-- Cache Information -->
    <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-green-900 mb-3">Cache Information</h3>
        <div class="text-sm text-green-800 space-y-2">
            <p><strong>Config Cache:</strong> Cached configuration files for faster loading</p>
            <p><strong>Route Cache:</strong> Cached route definitions</p>
            <p><strong>View Cache:</strong> Compiled Blade templates</p>
            <p><strong>Application Cache:</strong> General application cache</p>
            <p><strong>Note:</strong> Clearing cache will temporarily slow down the application until caches are rebuilt.</p>
        </div>
    </div>
</div>
@endsection
