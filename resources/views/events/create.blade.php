@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-foreground">Create New Event</h1>
            <p class="text-muted-foreground">Add a new Ramzaan or Ashara event</p>
        </div>

        <div class="bg-card rounded-lg border border-border shadow-sm">
            <form method="POST" action="{{ route('events.store') }}" class="p-6" data-validate>
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-foreground mb-2">
                            Event Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name') }}" 
                               class="w-full rounded-md border border-border bg-background px-3 py-2 @error('name') border-red-500 @enderror" 
                               placeholder="e.g., Ramzaan 1447, Ashara 1447"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="event_type" class="block text-sm font-medium text-foreground mb-2">
                            Event Type <span class="text-red-500">*</span>
                        </label>
                        <select name="event_type" id="event_type" 
                                class="w-full rounded-md border border-border bg-background px-3 py-2 @error('event_type') border-red-500 @enderror" 
                                required>
                            <option value="">Select Event Type</option>
                            <option value="ramzaan" {{ old('event_type') === 'ramzaan' ? 'selected' : '' }}>Ramzaan</option>
                            <option value="ashara" {{ old('event_type') === 'ashara' ? 'selected' : '' }}>Ashara</option>
                        </select>
                        @error('event_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-foreground mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date" 
                                   value="{{ old('start_date') }}" 
                                   class="w-full rounded-md border border-border bg-background px-3 py-2 @error('start_date') border-red-500 @enderror" 
                                   required>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-foreground mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="end_date" id="end_date" 
                                   value="{{ old('end_date') }}" 
                                   class="w-full rounded-md border border-border bg-background px-3 py-2 @error('end_date') border-red-500 @enderror" 
                                   required>
                            @error('end_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="previous_event_id" class="block text-sm font-medium text-foreground mb-2">
                            Previous Event
                        </label>
                        <select name="previous_event_id" id="previous_event_id" 
                                class="w-full rounded-md border border-border bg-background px-3 py-2 @error('previous_event_id') border-red-500 @enderror">
                            <option value="">No Previous Event</option>
                            <!-- Options will be populated by JavaScript based on event type -->
                        </select>
                        <p class="text-xs text-muted-foreground mt-1">
                            Select the previous event of the same type for reference
                        </p>
                        @error('previous_event_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-foreground mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full rounded-md border border-border bg-background px-3 py-2 @error('description') border-red-500 @enderror" 
                                  placeholder="Brief description of the event">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_default" id="is_default" value="1" 
                                   {{ old('is_default') ? 'checked' : '' }}
                                   class="rounded border-border text-primary focus:ring-primary">
                            <label for="is_default" class="ml-2 text-sm text-foreground">
                                Set as Default Event
                            </label>
                        </div>
                        <p class="text-xs text-muted-foreground ml-6">
                            This will make this event the default for the selected type (Ramzaan or Ashara)
                        </p>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-border text-primary focus:ring-primary">
                            <label for="is_active" class="ml-2 text-sm text-foreground">
                                Active Event
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('events.index') }}" 
                       class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                        Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventTypeSelect = document.getElementById('event_type');
    const previousEventSelect = document.getElementById('previous_event_id');
    
    // Available events data
    const ramzaanEvents = @json($ramzaanEvents);
    const asharaEvents = @json($asharaEvents);
    
    function updatePreviousEventOptions() {
        const selectedType = eventTypeSelect.value;
        previousEventSelect.innerHTML = '<option value="">No Previous Event</option>';
        
        if (selectedType === 'ramzaan') {
            ramzaanEvents.forEach(event => {
                const option = document.createElement('option');
                option.value = event.id;
                option.textContent = `${event.name} (${event.start_date})`;
                previousEventSelect.appendChild(option);
            });
        } else if (selectedType === 'ashara') {
            asharaEvents.forEach(event => {
                const option = document.createElement('option');
                option.value = event.id;
                option.textContent = `${event.name} (${event.start_date})`;
                previousEventSelect.appendChild(option);
            });
        }
    }
    
    eventTypeSelect.addEventListener('change', updatePreviousEventOptions);
    
    // Initialize on page load if event type is already selected
    if (eventTypeSelect.value) {
        updatePreviousEventOptions();
    }
});
</script>
@endsection
