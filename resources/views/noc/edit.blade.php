@extends('layouts.app')

@section('title', 'Edit NOC')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Edit NOC</h1>
            <p class="text-muted-foreground mt-2">Update NOC details</p>
        </div>
        
        <a href="{{ route('noc.index') }}" 
           class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90 transition-colors">
            Back to NOC
        </a>
    </div>

    <!-- Form -->
    <div class="bg-card p-6 rounded-lg border border-border">
        <form method="POST" action="{{ route('noc.update', $noc) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sabeel Selection -->
                <div>
                    <label for="sabeel_id" class="block text-sm font-medium text-foreground mb-2">
                        Sabeel <span class="text-destructive">*</span>
                    </label>
                    <select name="sabeel_id" id="sabeel_id" required
                            class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent select2">
                        <option value="">Search and select a Sabeel...</option>
                        @foreach($sabeels as $sabeel)
                            <option value="{{ $sabeel->id }}" {{ old('sabeel_id', $noc->sabeel_id) == $sabeel->id ? 'selected' : '' }}>
                                {{ $sabeel->sabeel_code }} - {{ $sabeel->sabeel_hof }}
                            </option>
                        @endforeach
                    </select>
                    @error('sabeel_id')
                        <p class="text-destructive text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Selection -->
                <div>
                    <label for="event_id" class="block text-sm font-medium text-foreground mb-2">
                        Event <span class="text-destructive">*</span>
                    </label>
                    <select name="event_id" id="event_id" required
                            class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Select an Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" 
                                    {{ (old('event_id', $noc->event_id) == $event->id) ? 'selected' : '' }}>
                                {{ $event->name }} ({{ $event->start_date->format('M Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('event_id')
                        <p class="text-destructive text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Remark -->
            <div>
                <label for="remark" class="block text-sm font-medium text-foreground mb-2">
                    Remark (Optional)
                </label>
                <textarea name="remark" id="remark" rows="4"
                          class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="Enter any remarks or notes...">{{ old('remark', $noc->remark) }}</textarea>
                @error('remark')
                    <p class="text-destructive text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Status -->
            <div class="bg-muted p-4 rounded-md">
                <h3 class="text-sm font-medium text-foreground mb-2">Current Status</h3>
                @if($noc->noc_alloted_at)
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Allocated
                        </span>
                        <span class="text-sm text-muted-foreground">
                            on {{ $noc->noc_alloted_at->format('M d, Y g:i A') }}
                        </span>
                    </div>
                @else
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Pending
                        </span>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('noc.show', $noc) }}" 
                   class="bg-muted text-muted-foreground px-6 py-2 rounded-md hover:bg-muted/90 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary text-primary-foreground px-6 py-2 rounded-md hover:bg-primary/90 transition-colors">
                    Update NOC
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for sabeel selection
    $('#sabeel_id').select2({
        placeholder: 'Search and select a Sabeel...',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });
});
</script>
@endsection
