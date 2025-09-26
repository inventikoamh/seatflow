@extends('layouts.app')

@section('title', 'NOC Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">NOC Details</h1>
            <p class="text-muted-foreground mt-2">View NOC information</p>
        </div>
        
        <div class="flex space-x-4">
            @can('edit-noc')
            <a href="{{ route('noc.edit', $noc) }}" 
               class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90 transition-colors">
                Edit NOC
            </a>
            @endcan
            
            <a href="{{ route('noc.index') }}" 
               class="bg-muted text-muted-foreground px-4 py-2 rounded-md hover:bg-muted/90 transition-colors">
                Back to NOC
            </a>
        </div>
    </div>

    <!-- NOC Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Information -->
        <div class="lg:col-span-2 bg-card p-6 rounded-lg border border-border">
            <h2 class="text-xl font-semibold text-foreground mb-4">NOC Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Sabeel</label>
                    <p class="text-lg font-semibold text-foreground">{{ $noc->sabeel->sabeel_code }}</p>
                    <p class="text-sm text-muted-foreground">{{ $noc->sabeel->sabeel_hof }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Event</label>
                    <p class="text-lg font-semibold text-foreground">{{ $noc->event->name }}</p>
                    <p class="text-sm text-muted-foreground">{{ $noc->event->start_date->format('M Y') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Status</label>
                    @if($noc->noc_alloted_at)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Allocated
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Pending
                        </span>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Allocated Date</label>
                    @if($noc->noc_alloted_at)
                        <p class="text-lg text-foreground">{{ $noc->noc_alloted_at->format('M d, Y g:i A') }}</p>
                    @else
                        <p class="text-lg text-muted-foreground">Not allocated</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Created</label>
                    <p class="text-lg text-foreground">{{ $noc->created_at->format('M d, Y g:i A') }}</p>
                </div>
            </div>
            
            @if($noc->remark)
            <div class="mt-6">
                <label class="block text-sm font-medium text-muted-foreground mb-2">Remark</label>
                <p class="text-foreground bg-muted p-3 rounded-md">{{ $noc->remark }}</p>
            </div>
            @endif
        </div>

        <!-- HOF Photo -->
        <div class="bg-card p-6 rounded-lg border border-border">
            <h2 class="text-xl font-semibold text-foreground mb-4">Head of Family</h2>
            
            @if($noc->hasHofPhoto())
                <div class="flex justify-center mb-4">
                    <div class="w-32 h-40 rounded-lg overflow-hidden border border-border bg-gray-50">
                        <img src="{{ $noc->getHofPhotoUrl() }}"
                             alt="{{ $noc->getHeadOfFamily()?->full_name ?? 'HOF' }}"
                             class="w-full h-full object-contain"
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTI4IiBoZWlnaHQ9IjE2MCIgdmlld0JveD0iMCAwIDEyOCAxNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjgiIGhlaWdodD0iMTYwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik02NCAyNEM1Mi4yNjggMjQgNDIgMzQuMjY4IDQyIDQ2QzQyIDU3LjczMiA1Mi4yNjggNjggNjQgNjhDNzUuNzMyIDY4IDg2IDU3LjczMiA4NiA0NkM4NiAzNC4yNjggNzUuNzMyIDI0IDY0IDI0WiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNMzIgMTA0QzE2IDExMiA4IDEyMCA4IDEzMlYxMjhDOCAxMjggMTYgMTI4IDMyIDEyOEg5NkM5NiAxMjggMTI4IDEyOCAxMjggMTI4VjEzMkMxMjggMTIwIDEyMCAxMTIgMTA0IDEwNEgzMloiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+Cg=='">
                    </div>
                </div>
                
                @if($noc->getHeadOfFamily())
                    <div class="text-center">
                        <p class="text-lg font-semibold text-foreground">{{ $noc->getHeadOfFamily()->full_name }}</p>
                        <p class="text-sm text-muted-foreground">ITS ID: {{ $noc->getHeadOfFamily()->ITS_ID }}</p>
                        @if($noc->getHeadOfFamily()->mobile_number)
                            <p class="text-sm text-muted-foreground">{{ $noc->getHeadOfFamily()->formatted_mobile }}</p>
                        @endif
                    </div>
                @endif
            @else
                <div class="flex justify-center mb-4">
                    <div class="w-32 h-40 rounded-lg overflow-hidden border border-border bg-gray-50 flex items-center justify-center">
                        <div class="text-sm text-muted-foreground text-center">
                            <div class="text-red-500 font-medium">HOF</div>
                            <div class="text-red-500 font-medium">Not Found</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-card p-6 rounded-lg border border-border">
        <h2 class="text-xl font-semibold text-foreground mb-4">Actions</h2>
        
        <div class="flex space-x-4">
            @if($noc->noc_alloted_at)
                @can('edit-noc')
                <form method="POST" action="{{ route('noc.revoke', $noc) }}"
                      onsubmit="return confirm('Are you sure you want to revoke this NOC?')">
                    @csrf
                    <button type="submit" 
                            class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors">
                        Revoke NOC
                    </button>
                </form>
                @endcan
            @else
                @can('edit-noc')
                <form method="POST" action="{{ route('noc.allocate', $noc) }}"
                      onsubmit="return confirm('Are you sure you want to allocate this NOC?')">
                    @csrf
                    <button type="submit" 
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                        Allocate NOC
                    </button>
                </form>
                @endcan
            @endif
            
            @can('edit-noc')
            <a href="{{ route('noc.edit', $noc) }}" 
               class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90 transition-colors">
                Edit NOC
            </a>
            @endcan
            
            @can('delete-noc')
            <form method="POST" action="{{ route('noc.destroy', $noc) }}" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this NOC?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-destructive text-destructive-foreground px-4 py-2 rounded-md hover:bg-destructive/90 transition-colors">
                    Delete NOC
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>
@endsection
