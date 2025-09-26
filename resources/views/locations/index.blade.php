@extends('layouts.app')

@section('title', 'Locations')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Locations</h1>
            <p class="text-muted-foreground">Manage masjid and hall locations</p>
        </div>
        @can('create-locations')
        <div class="mt-4 md:mt-0">
            <a href="{{ route('locations.create') }}" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                Add New Location
            </a>
        </div>
        @endcan
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Locations Table -->
    <div class="bg-card rounded-lg border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-muted">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Name</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Description</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Areas</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($locations as $location)
                        <tr class="hover:bg-muted/50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-foreground">{{ $location->name }}</div>
                                <div class="text-sm text-muted-foreground">{{ $location->slug }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-muted-foreground max-w-xs truncate">
                                    {{ $location->description ?: 'No description' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-medium text-foreground">{{ $location->areas->count() }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $location->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $location->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    @can('view-locations')
                                    <a href="{{ route('locations.show', $location) }}" 
                                       class="text-primary hover:text-primary/80 text-sm">
                                        View
                                    </a>
                                    @endcan
                                    @can('edit-locations')
                                    <a href="{{ route('locations.edit', $location) }}" 
                                       class="text-gray-600 hover:text-gray-800 text-sm">
                                        Edit
                                    </a>
                                    @endcan
                                    @can('delete-locations')
                                    <form method="POST" action="{{ route('locations.destroy', $location) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this location?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                            Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-muted-foreground">
                                No locations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
