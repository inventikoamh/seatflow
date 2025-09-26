@extends('layouts.app')

@section('title', $location->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">{{ $location->name }}</h1>
                    <p class="text-muted-foreground">{{ $location->description ?: 'No description provided' }}</p>
                </div>
                <div class="flex gap-2">
                    @can('edit-locations')
                    <a href="{{ route('locations.edit', $location) }}" 
                       class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                        Edit Location
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Location Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Basic Information -->
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-foreground mb-4">Location Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Name:</span>
                        <span class="text-sm font-medium text-foreground">{{ $location->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Slug:</span>
                        <span class="text-sm font-medium text-foreground">{{ $location->slug }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Areas:</span>
                        <span class="text-sm font-medium text-foreground">{{ $location->areas->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Status:</span>
                        <span class="text-sm font-medium text-foreground">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $location->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $location->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-foreground mb-4">Description</h3>
                <p class="text-sm text-muted-foreground">
                    {{ $location->description ?: 'No description provided for this location.' }}
                </p>
            </div>
        </div>

        <!-- Areas -->
        @if($location->areas->count() > 0)
        <div class="bg-card rounded-lg border border-border p-6 mb-8">
            <h3 class="text-lg font-semibold text-foreground mb-4">Areas in {{ $location->name }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($location->areas as $area)
                    <div class="p-4 bg-muted rounded-md">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-foreground">{{ $area->name }}</h4>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $area->gender_type === 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($area->gender_type) }}
                            </span>
                        </div>
                        <div class="text-sm text-muted-foreground space-y-1">
                            <div>Floor: {{ $area->floor === 0 ? 'Ground' : 'Floor ' . $area->floor }}</div>
                            <div>Capacity: {{ $area->capacity }}</div>
                            <div>Event Type: {{ ucfirst($area->event_type) }}</div>
                            @if($area->section)
                                <div>Section: {{ ucfirst($area->section) }}</div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('seat-maps.show', $area->id) }}" 
                               target="_blank"
                               class="inline-flex items-center text-xs bg-primary text-primary-foreground px-3 py-1 rounded hover:bg-primary/90 transition-colors">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                View Seat Map
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-card rounded-lg border border-border p-6 mb-8">
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-foreground">No areas</h3>
                <p class="mt-1 text-sm text-muted-foreground">This location doesn't have any areas yet.</p>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('locations.index') }}" 
               class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                Back to Locations
            </a>
            
            @can('delete-locations')
            <form method="POST" action="{{ route('locations.destroy', $location) }}" 
                  onsubmit="return confirm('Are you sure you want to delete this location? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                    Delete Location
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>

@endsection
