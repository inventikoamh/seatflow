@extends('layouts.app')

@section('title', 'Import NOC')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Import NOC</h1>
            <p class="text-muted-foreground mt-2">Import NOC records from CSV file</p>
        </div>
        <a href="{{ route('noc.index') }}" 
           class="bg-muted text-muted-foreground px-4 py-2 rounded-md hover:bg-muted/90 transition-colors">
            Back to NOC
        </a>
    </div>

    <!-- Import Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <h2 class="text-lg font-semibold text-blue-900 mb-3">Import Instructions</h2>
        <div class="text-blue-800 space-y-2">
            <p><strong>CSV Format:</strong> Your CSV file should contain exactly 1 column:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li><strong>sabeel_code:</strong> The sabeel code (e.g., 2163, 2164)</li>
            </ul>
            <p><strong>Event Selection:</strong> All records will be imported for the selected event.</p>
            <p><strong>Auto-Allocation:</strong> All imported NOC records will be automatically allocated.</p>
            <p><strong>Sample File:</strong> <a href="{{ route('noc.sample') }}" class="text-blue-600 hover:text-blue-800 underline">Download sample CSV</a></p>
        </div>
    </div>

    <!-- Import Form -->
    <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
        <form action="{{ route('noc.import.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- CSV File Upload -->
                <div>
                    <label for="csv_file" class="block text-sm font-medium text-foreground mb-2">
                        CSV File <span class="text-destructive">*</span>
                    </label>
                    <input type="file" name="csv_file" id="csv_file" accept=".csv,.txt" required
                           class="w-full px-3 py-2 border border-border rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('csv_file')
                        <p class="text-destructive text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-muted-foreground mt-1">Maximum file size: 10MB</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('noc.index') }}" 
                   class="bg-muted text-muted-foreground px-6 py-2 rounded-md hover:bg-muted/90 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary text-primary-foreground px-6 py-2 rounded-md hover:bg-primary/90 transition-colors">
                    Preview Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
