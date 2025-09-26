@extends('layouts.app')

@section('title', 'Takhmeen Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Takhmeen Management</h1>
            <p class="text-muted-foreground mt-2">Manage financial contributions for events</p>
            @if($currentEvent)
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                        Current Event: {{ $currentEvent->name }}
                    </span>
                </div>
            @endif
        </div>
        
        @can('create-takhmeen')
        <a href="{{ route('takhmeen.create') }}" 
           class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
            Add New Takhmeen
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-card p-6 rounded-lg border border-border mb-6">
        <form method="GET" class="space-y-4">
            <!-- Filter Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="event_id" class="block text-sm font-medium text-foreground mb-2">Event</label>
                    <select name="event_id" id="event_id" class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground">
                        <option value="">All Events</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="sabeel_id" class="block text-sm font-medium text-foreground mb-2">Sabeel</label>
                    <select name="sabeel_id" id="sabeel_id" class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground select2">
                        <option value="">All Sabeels</option>
                        @foreach($sabeels as $sabeel)
                            <option value="{{ $sabeel->id }}" {{ request('sabeel_id') == $sabeel->id ? 'selected' : '' }}>
                                {{ $sabeel->sabeel_code }} - {{ $sabeel->sabeel_hof }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="search" class="block text-sm font-medium text-foreground mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Sabeel code..." 
                           class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors w-full">
                        Filter
                    </button>
                </div>
            </div>
            
            <!-- Sorting Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-border">
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-foreground mb-2">Sort By</label>
                    <select name="sort_by" id="sort_by" class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground">
                        <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="amount" {{ $sortBy == 'amount' ? 'selected' : '' }}>Amount</option>
                        <option value="sabeel_code" {{ $sortBy == 'sabeel_code' ? 'selected' : '' }}>Sabeel Code</option>
                        <option value="event_name" {{ $sortBy == 'event_name' ? 'selected' : '' }}>Event Name</option>
                    </select>
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-foreground mb-2">Order</label>
                    <select name="sort_order" id="sort_order" class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground">
                        <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90 transition-colors">
                        Sort
                    </button>
                    <a href="{{ route('takhmeen.index') }}" class="bg-muted text-muted-foreground px-4 py-2 rounded-md hover:bg-muted/90 transition-colors">
                        Clear All
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Takhmeen List -->
    <div class="bg-card rounded-lg border border-border overflow-hidden">
        @if($takhmeen->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Photo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'sabeel_code', 'sort_order' => $sortBy == 'sabeel_code' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 hover:text-foreground transition-colors">
                                    <span>Sabeel</span>
                                    @if($sortBy == 'sabeel_code')
                                        @if($sortOrder == 'asc')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'event_name', 'sort_order' => $sortBy == 'event_name' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 hover:text-foreground transition-colors">
                                    <span>Event</span>
                                    @if($sortBy == 'event_name')
                                        @if($sortOrder == 'asc')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'amount', 'sort_order' => $sortBy == 'amount' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 hover:text-foreground transition-colors">
                                    <span>Amount</span>
                                    @if($sortBy == 'amount')
                                        @if($sortOrder == 'asc')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => $sortBy == 'created_at' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 hover:text-foreground transition-colors">
                                    <span>Created</span>
                                    @if($sortBy == 'created_at')
                                        @if($sortOrder == 'asc')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($takhmeen as $t)
                        <tr class="hover:bg-accent/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($t->hasHofPhoto())
                                    <div class="w-20 h-24 rounded-md overflow-hidden border border-border bg-gray-50">
                                        <img src="{{ $t->getHofPhotoUrl() }}"
                                             alt="{{ $t->getHeadOfFamily()?->full_name ?? 'HOF' }}"
                                             class="w-full h-full object-contain"
                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iOTYiIHZpZXdCb3g9IjAgMCA4MCA5NiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9Ijk2IiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik00MCAyMEMzMi4yNjggMjAgMjYgMjYuMjY4IDI2IDM0QzI2IDQxLjczMiAzMi4yNjggNDggNDAgNDhDNDcuNzMyIDQ4IDU0IDQxLjczMiA1NCAzNEM1NCAyNi4yNjggNDcuNzMyIDIwIDQwIDIwWiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNMjAgNjRDMTAgNjQgMiA3MiAyIDgyVjgwQzIgODAgMTAgODAgMjAgODBINjBDNjAgODAgODAgODAgODAgODBWODJDODAgNzIgNzIgNjQgNjAgNjRIMjBaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPgo='">
                                    </div>
                                @else
                                    <div class="w-20 h-24 rounded-md overflow-hidden border border-border bg-gray-50 flex items-center justify-center">
                                        <div class="text-xs text-muted-foreground text-center">
                                            <div class="text-red-500 font-medium">HOF</div>
                                            <div class="text-red-500 font-medium">Not Found</div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-foreground">{{ $t->sabeel->sabeel_code }}</div>
                                <div class="text-sm text-muted-foreground">{{ $t->sabeel->sabeel_hof }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-foreground">{{ $t->event->name }}</div>
                                <div class="text-sm text-muted-foreground">{{ $t->event->start_date->format('M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                                {{ $t->indian_comma_amount }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                                {{ $t->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @can('view-takhmeen')
                                    <a href="{{ route('takhmeen.show', $t) }}" 
                                       class="text-primary hover:text-primary/80 transition-colors">
                                        View
                                    </a>
                                    @endcan
                                    
                                    @can('edit-takhmeen')
                                    <a href="{{ route('takhmeen.edit', $t) }}" 
                                       class="text-gray-600 hover:text-gray-800 transition-colors">
                                        Edit
                                    </a>
                                    @endcan
                                    
                                    @can('delete-takhmeen')
                                    <form method="POST" action="{{ route('takhmeen.destroy', $t) }}" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this takhmeen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-destructive hover:text-destructive/80 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-border">
                {{ $takhmeen->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-foreground">No takhmeen found</h3>
                <p class="mt-1 text-sm text-muted-foreground">Get started by creating a new takhmeen.</p>
                @can('create-takhmeen')
                <div class="mt-6">
                    <a href="{{ route('takhmeen.create') }}" 
                       class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                        Add New Takhmeen
                    </a>
                </div>
                @endcan
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for sabeel filter
    $('#sabeel_id').select2({
        placeholder: 'All Sabeels',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });
});
</script>
@endsection
