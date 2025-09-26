@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Events</h1>
            <p class="text-muted-foreground">Manage Ramzaan and Ashara events</p>
        </div>
        @can('create-events')
        <div class="mt-4 md:mt-0">
            <a href="{{ route('events.create') }}" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                Add New Event
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

    <!-- Filters -->
    <div class="bg-card p-4 rounded-lg border border-border mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="event_type" class="block text-sm font-medium text-foreground mb-1">Event Type</label>
                <select name="event_type" id="event_type" class="w-full rounded-md border border-border bg-background px-3 py-2">
                    <option value="">All Types</option>
                    <option value="ramzaan" {{ request('event_type') === 'ramzaan' ? 'selected' : '' }}>Ramzaan</option>
                    <option value="ashara" {{ request('event_type') === 'ashara' ? 'selected' : '' }}>Ashara</option>
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-foreground mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-md border border-border bg-background px-3 py-2">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                    Filter
                </button>
                <a href="{{ route('events.index') }}" class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Events Table -->
    <div class="bg-card rounded-lg border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-muted">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Event</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Type</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Duration</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Previous Event</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($events as $event)
                        <tr class="hover:bg-muted/50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-foreground">{{ $event->name }}</div>
                                <div class="text-sm text-muted-foreground">{{ $event->slug }}</div>
                                @if($event->is_default)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        Default {{ ucfirst($event->event_type) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $event->event_type === 'ramzaan' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ ucfirst($event->event_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-foreground">{{ $event->start_date->format('M j, Y') }}</div>
                                <div class="text-sm text-muted-foreground">to {{ $event->end_date->format('M j, Y') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($event->previousEvent)
                                    <div class="text-sm text-foreground">{{ $event->previousEvent->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $event->previousEvent->start_date->format('Y') }}</div>
                                @else
                                    <span class="text-sm text-muted-foreground">None</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $event->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    @can('view-events')
                                    <a href="{{ route('events.show', $event) }}" 
                                       class="text-primary hover:text-primary/80 text-sm">
                                        View
                                    </a>
                                    @endcan
                                    @can('edit-events')
                                    <a href="{{ route('events.edit', $event) }}" 
                                       class="text-gray-600 hover:text-gray-800 text-sm">
                                        Edit
                                    </a>
                                    @endcan
                                    @can('manage-events')
                                    @if(!$event->is_default)
                                    <form method="POST" action="{{ route('events.set-default', $event) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                                            Set Default
                                        </button>
                                    </form>
                                    @endif
                                    @endcan
                                    @can('delete-events')
                                    <form method="POST" action="{{ route('events.destroy', $event) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this event?')">
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
                            <td colspan="6" class="px-4 py-8 text-center text-muted-foreground">
                                No events found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $events->links() }}
    </div>
</div>
@endsection
