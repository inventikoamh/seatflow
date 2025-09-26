@extends('layouts.app')

@section('title', 'Takhmeen Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Takhmeen Details</h1>
            <p class="text-muted-foreground mt-2">View takhmeen information</p>
        </div>
        
        <div class="flex space-x-4">
            @can('edit-takhmeen')
            <a href="{{ route('takhmeen.edit', $takhmeen) }}" 
               class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90 transition-colors">
                Edit Takhmeen
            </a>
            @endcan
            
            <a href="{{ route('takhmeen.index') }}" 
               class="bg-muted text-muted-foreground px-4 py-2 rounded-md hover:bg-muted/90 transition-colors">
                Back to Takhmeen
            </a>
        </div>
    </div>

    <!-- Takhmeen Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Information -->
        <div class="lg:col-span-2 bg-card p-6 rounded-lg border border-border">
            <h2 class="text-xl font-semibold text-foreground mb-4">Takhmeen Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Sabeel</label>
                    <p class="text-lg font-semibold text-foreground">{{ $takhmeen->sabeel->sabeel_code }}</p>
                    <p class="text-sm text-muted-foreground">{{ $takhmeen->sabeel->sabeel_hof }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Event</label>
                    <p class="text-lg font-semibold text-foreground">{{ $takhmeen->event->name }}</p>
                    <p class="text-sm text-muted-foreground">{{ $takhmeen->event->start_date->format('M Y') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Amount</label>
                    <p class="text-2xl font-bold text-foreground">{{ $takhmeen->formatted_amount }}</p>
                    @if($takhmeen->amount >= 100000)
                        <p class="text-sm text-muted-foreground mt-1">{{ $takhmeen->indian_amount }}</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Created</label>
                    <p class="text-lg text-foreground">{{ $takhmeen->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            
            @if($takhmeen->notes)
            <div class="mt-6">
                <label class="block text-sm font-medium text-muted-foreground mb-2">Notes</label>
                <p class="text-foreground bg-muted p-3 rounded-md">{{ $takhmeen->notes }}</p>
            </div>
            @endif
        </div>

        <!-- HOF Photo -->
        <div class="bg-card p-6 rounded-lg border border-border">
            <h2 class="text-xl font-semibold text-foreground mb-4">Head of Family</h2>
            
            @if($takhmeen->hasHofPhoto())
                <div class="flex justify-center mb-4">
                    <div class="w-32 h-40 rounded-lg overflow-hidden border border-border bg-gray-50">
                        <img src="{{ $takhmeen->getHofPhotoUrl() }}"
                             alt="{{ $takhmeen->getHeadOfFamily()?->full_name ?? 'HOF' }}"
                             class="w-full h-full object-contain"
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTI4IiBoZWlnaHQ9IjE2MCIgdmlld0JveD0iMCAwIDEyOCAxNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjgiIGhlaWdodD0iMTYwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik02NCAyNEM1Mi4yNjggMjQgNDIgMzQuMjY4IDQyIDQ2QzQyIDU3LjczMiA1Mi4yNjggNjggNjQgNjhDNzUuNzMyIDY4IDg2IDU3LjczMiA4NiA0NkM4NiAzNC4yNjggNzUuNzMyIDI0IDY0IDI0WiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNMzIgMTA0QzE2IDExMiA4IDEyMCA4IDEzMlYxMjhDOCAxMjggMTYgMTI4IDMyIDEyOEg5NkM5NiAxMjggMTI4IDEyOCAxMjggMTI4VjEzMkMxMjggMTIwIDEyMCAxMTIgMTA0IDEwNEgzMloiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+Cg=='">
                    </div>
                </div>
                
                @if($takhmeen->getHeadOfFamily())
                    <div class="text-center">
                        <p class="text-lg font-semibold text-foreground">{{ $takhmeen->getHeadOfFamily()->full_name }}</p>
                        <p class="text-sm text-muted-foreground">ITS ID: {{ $takhmeen->getHeadOfFamily()->ITS_ID }}</p>
                        @if($takhmeen->getHeadOfFamily()->mobile_number)
                            <p class="text-sm text-muted-foreground">{{ $takhmeen->getHeadOfFamily()->formatted_mobile }}</p>
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
</div>

@endsection
