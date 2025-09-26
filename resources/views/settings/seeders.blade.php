@extends('layouts.app')

@section('title', 'Seeder Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Seeder Management</h1>
            <p class="text-muted-foreground mt-2">Manage database seeders and initial data</p>
        </div>
        <a href="{{ route('settings.index') }}" 
           class="bg-muted text-muted-foreground px-4 py-2 rounded-md hover:bg-muted/90 transition-colors">
            Back to Settings
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="bg-card p-6 rounded-lg border border-border mb-6">
        <h2 class="text-xl font-semibold text-foreground mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="POST" action="{{ route('settings.seeders.run-all') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
                        onclick="return confirm('Are you sure you want to run all seeders? This will populate the database with initial data.')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Run All Seeders
                </button>
            </form>
            
            <button onclick="location.reload()" 
                    class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh List
            </button>
        </div>
    </div>

    <!-- Warning -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <div class="flex">
            <svg class="w-5 h-5 text-yellow-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                <p class="text-sm text-yellow-700 mt-1">
                    Running seeders will populate the database with initial data. This may include roles, permissions, 
                    sample users, and other essential data. Make sure you understand what each seeder does before running it.
                </p>
            </div>
        </div>
    </div>

    <!-- All Seeders -->
    <div class="bg-card rounded-lg border border-border overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-xl font-semibold text-foreground">Available Seeders</h2>
            <p class="text-muted-foreground mt-1">List of all seeder classes in the system</p>
        </div>
        
        @if(count($seeders) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-muted">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Seeder Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">File Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Modified</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($seeders as $seeder)
                    <tr class="hover:bg-accent/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-foreground">{{ $seeder['class'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-foreground">{{ $seeder['name'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                            {{ \App\Http\Controllers\SettingsController::formatBytes($seeder['size']) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                            {{ $seeder['modified'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form method="POST" action="{{ route('settings.seeders.run') }}" class="inline">
                                @csrf
                                <input type="hidden" name="seeder" value="{{ $seeder['class'] }}">
                                <button type="submit" 
                                        class="text-green-600 hover:text-green-800 transition-colors"
                                        onclick="return confirm('Run seeder: {{ $seeder['class'] }}?')">
                                    Run
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-6 text-center text-muted-foreground">
            No seeder files found.
        </div>
        @endif
    </div>

    <!-- Common Seeders Info -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Common Seeders</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
            <div>
                <h4 class="font-medium mb-2">Essential Seeders:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>DatabaseSeeder:</strong> Main seeder that runs all others</li>
                    <li><strong>RolePermissionSeeder:</strong> Creates roles and permissions</li>
                    <li><strong>UserSeeder:</strong> Creates initial admin users</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium mb-2">Data Seeders:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>SabeelSeeder:</strong> Sample family data</li>
                    <li><strong>MuminSeeder:</strong> Sample member data</li>
                    <li><strong>EventSeeder:</strong> Sample event data</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
