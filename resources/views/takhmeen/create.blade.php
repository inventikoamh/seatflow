@extends('layouts.app')

@section('title', 'Create Takhmeen')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Create New Takhmeen</h1>
            <p class="text-muted-foreground mt-2">Add a new financial contribution record</p>
        </div>
        
        <a href="{{ route('takhmeen.index') }}" 
           class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90 transition-colors">
            Back to Takhmeen
        </a>
    </div>

    <!-- Form -->
    <div class="bg-card p-6 rounded-lg border border-border">
        <form method="POST" action="{{ route('takhmeen.store') }}" class="space-y-6">
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

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-foreground mb-2">
                        Amount (₹) <span class="text-destructive">*</span>
                    </label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0" required
                           value="{{ old('amount') }}"
                           placeholder="Enter contribution amount"
                           class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('amount')
                        <p class="text-destructive text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-foreground mb-2">
                    Notes
                </label>
                <textarea name="notes" id="notes" rows="4"
                          placeholder="Additional notes or comments..."
                          class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-destructive text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- HOF Photo URL -->
            <div>
                <label for="hof_photo" class="block text-sm font-medium text-foreground mb-2">
                    HOF Photo URL (Optional)
                </label>
                <input type="url" name="hof_photo" id="hof_photo"
                       value="{{ old('hof_photo') }}"
                       placeholder="https://example.com/photo.jpg"
                       class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <p class="text-xs text-muted-foreground mt-1">Leave empty to use default HOF photo from sabeel</p>
                @error('hof_photo')
                    <p class="text-destructive text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('takhmeen.index') }}" 
                   class="bg-secondary text-secondary-foreground px-6 py-2 rounded-md hover:bg-secondary/90 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary text-primary-foreground px-6 py-2 rounded-md hover:bg-primary/90 transition-colors">
                    Create Takhmeen
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
