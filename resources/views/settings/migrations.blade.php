@extends('layouts.app')

@section('title', 'Migration Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Migration Management</h1>
            <p class="text-muted-foreground mt-2">Manage database migrations and schema changes</p>
        </div>
        <a href="{{ route('settings.index') }}" 
           class="bg-muted text-muted-foreground px-4 py-2 rounded-md hover:bg-muted/90 transition-colors">
            Back to Settings
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="bg-card p-6 rounded-lg border border-border mb-6">
        <h2 class="text-xl font-semibold text-foreground mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <form method="POST" action="{{ route('settings.migrations.run-all') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
                        onclick="return confirm('Are you sure you want to run all pending migrations?')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Run All Pending Migrations
                </button>
            </form>
            
            <form method="POST" action="{{ route('settings.migrations.rollback') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors"
                        onclick="return confirm('Are you sure you want to rollback the last migration?')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    Rollback Last Migration
                </button>
            </form>
            
            <button onclick="location.reload()" 
                    class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Status
            </button>
        </div>
    </div>

    <!-- Pending Migrations -->
    @if(count($pendingMigrations) > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-yellow-900 mb-3">Pending Migrations</h2>
        <div class="space-y-2">
            @foreach($pendingMigrations as $migration)
            <div class="flex items-center justify-between bg-white p-3 rounded border">
                <span class="text-sm font-medium text-gray-900">{{ $migration }}</span>
                <form method="POST" action="{{ route('settings.migrations.run') }}" class="inline">
                    @csrf
                    <input type="hidden" name="migration" value="{{ $migration }}">
                    <button type="submit" 
                            class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition-colors"
                            onclick="return confirm('Run migration: {{ $migration }}?')">
                        Run
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-green-800 font-medium">All migrations are up to date!</span>
        </div>
    </div>
    @endif

    <!-- All Migrations -->
    <div class="bg-card rounded-lg border border-border overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-xl font-semibold text-foreground">All Migration Files</h2>
            <p class="text-muted-foreground mt-1">Complete list of migration files in the system</p>
        </div>
        
        @if(count($migrations) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-muted">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Migration File</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Modified</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($migrations as $migration)
                    <tr class="hover:bg-accent/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-foreground">{{ $migration['name'] }}</div>
                            <div class="text-sm text-muted-foreground">{{ $migration['path'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                            {{ \App\Http\Controllers\SettingsController::formatBytes($migration['size']) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                            {{ $migration['modified'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form method="POST" action="{{ route('settings.migrations.run') }}" class="inline">
                                @csrf
                                <input type="hidden" name="migration" value="{{ $migration['path'] }}">
                                <button type="submit" 
                                        class="text-green-600 hover:text-green-800 transition-colors"
                                        onclick="return confirm('Run migration: {{ $migration['name'] }}?')">
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
            No migration files found.
        </div>
        @endif
    </div>
</div>
@endsection
