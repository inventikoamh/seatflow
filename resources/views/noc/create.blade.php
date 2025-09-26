@extends('layouts.app')

@section('title', 'Create NOC')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Create New NOC</h1>
            <p class="text-muted-foreground mt-2">Add a new No Objection Certificate record</p>
        </div>
        
        <a href="{{ route('noc.index') }}" 
           class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90 transition-colors">
            Back to NOC
        </a>
    </div>

    <!-- Form -->
    <div class="bg-card p-6 rounded-lg border border-border">
        <form method="POST" action="{{ route('noc.store') }}" class="space-y-6">
            @csrf
            
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
                            <option value="{{ $sabeel->id }}" {{ old('sabeel_id') == $sabeel->id ? 'selected' : '' }}>
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
                                    {{ (old('event_id', $currentEvent?->id) == $event->id) ? 'selected' : '' }}>
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
                          placeholder="Enter any remarks or notes...">{{ old('remark') }}</textarea>
                @error('remark')
                    <p class="text-destructive text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('noc.index') }}" 
                   class="bg-muted text-muted-foreground px-6 py-2 rounded-md hover:bg-muted/90 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary text-primary-foreground px-6 py-2 rounded-md hover:bg-primary/90 transition-colors">
                    Create NOC
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
