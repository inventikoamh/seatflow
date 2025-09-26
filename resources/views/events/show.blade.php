@extends('layouts.app')

@section('title', $event->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">{{ $event->name }}</h1>
                    <p class="text-muted-foreground">{{ $event->description ?: 'No description provided' }}</p>
                </div>
                <div class="flex gap-2">
                    @can('edit-events')
                    <a href="{{ route('events.edit', $event) }}" 
                       class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                        Edit Event
                    </a>
                    @endcan
                    @can('manage-events')
                    @if(!$event->is_default)
                    <form method="POST" action="{{ route('events.set-default', $event) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
                                onclick="return confirm('Set {{ $event->name }} as the default {{ $event->event_type }} event?')">
                            Set as Default
                        </button>
                    </form>
                    @else
                    <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-md text-sm font-medium">
                        Default {{ ucfirst($event->event_type) }} Event
                    </span>
                    @endif
                    @endcan
                </div>
            </div>
        </div>

        <!-- Event Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Basic Information -->
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-foreground mb-4">Event Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Event Type:</span>
                        <span class="text-sm font-medium text-foreground">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $event->event_type === 'ramzaan' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst($event->event_type) }}
                            </span>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Start Date:</span>
                        <span class="text-sm font-medium text-foreground">{{ $event->start_date->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">End Date:</span>
                        <span class="text-sm font-medium text-foreground">{{ $event->end_date->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Duration:</span>
                        <span class="text-sm font-medium text-foreground">{{ $event->start_date->diffInDays($event->end_date) + 1 }} days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-muted-foreground">Status:</span>
                        <span class="text-sm font-medium text-foreground">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $event->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Previous Event Information -->
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-foreground mb-4">Previous Event</h3>
                @if($event->previousEvent)
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Previous Event:</span>
                            <span class="text-sm font-medium text-foreground">{{ $event->previousEvent->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Previous Start Date:</span>
                            <span class="text-sm font-medium text-foreground">{{ $event->previousEvent->start_date->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Previous End Date:</span>
                            <span class="text-sm font-medium text-foreground">{{ $event->previousEvent->end_date->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Gap:</span>
                            <span class="text-sm font-medium text-foreground">{{ $event->previousEvent->end_date->diffInDays($event->start_date) }} days</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-muted-foreground">No previous event set for this event.</p>
                @endif
            </div>
        </div>

        <!-- Next Events -->
        @if($event->nextEvents->count() > 0)
        <div class="bg-card rounded-lg border border-border p-6 mb-8">
            <h3 class="text-lg font-semibold text-foreground mb-4">Events Using This as Previous Event</h3>
            <div class="space-y-2">
                @foreach($event->nextEvents as $nextEvent)
                    <div class="flex items-center justify-between p-3 bg-muted rounded-md">
                        <div>
                            <span class="font-medium text-foreground">{{ $nextEvent->name }}</span>
                            <span class="text-sm text-muted-foreground ml-2">({{ $nextEvent->start_date->format('M j, Y') }} - {{ $nextEvent->end_date->format('M j, Y') }})</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('events.show', $nextEvent) }}" 
                               class="text-primary hover:text-primary/80 text-sm">
                                View
                            </a>
                            @if($nextEvent->is_default)
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                    Default
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('events.index') }}" 
               class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                Back to Events
            </a>
            
            @can('delete-events')
            <form method="POST" action="{{ route('events.destroy', $event) }}" 
                  onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                    Delete Event
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>
@endsection
