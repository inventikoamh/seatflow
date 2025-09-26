@extends('layouts.app')

@section('title', 'Import NOC Preview')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Import NOC Preview</h1>
            <p class="text-muted-foreground mt-2">Review data before importing</p>
        </div>
        <a href="{{ route('noc.import') }}" 
           class="bg-muted text-muted-foreground px-4 py-2 rounded-md hover:bg-muted/90 transition-colors">
            Back to Import
        </a>
    </div>

    <!-- Event Information -->
    <div class="bg-card p-6 rounded-lg border border-border mb-6">
        <h2 class="text-xl font-semibold text-foreground mb-4">Import Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-muted-foreground mb-1">Event</label>
                <p class="text-lg font-semibold text-foreground">{{ $event->name }}</p>
                <p class="text-sm text-muted-foreground">{{ $event->start_date->format('M Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-muted-foreground mb-1">Total Records</label>
                <p class="text-lg font-semibold text-foreground">{{ count($csvData) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-muted-foreground mb-1">Valid Records</label>
                <p class="text-lg font-semibold text-green-600">{{ count($validatedData) }}</p>
            </div>
        </div>
    </div>

    <!-- Errors -->
    @if(count($errors) > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-red-900 mb-3">Errors Found</h2>
        <div class="space-y-2">
            @foreach($errors as $rowNumber => $rowErrors)
            <div class="text-red-800">
                <strong>Row {{ $rowNumber }}:</strong>
                <ul class="list-disc list-inside ml-4">
                    @foreach($rowErrors as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Valid Data Preview -->
    @if(count($validatedData) > 0)
    <div class="bg-card rounded-lg border border-border overflow-hidden mb-6">
        <div class="p-6 border-b border-border">
            <h2 class="text-xl font-semibold text-foreground">Valid Records Preview</h2>
            <p class="text-muted-foreground mt-1">These records will be imported as NOC</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-muted">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Sabeel Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">HOF</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($validatedData as $data)
                    <tr class="hover:bg-accent/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-foreground">
                            {{ $data['sabeel_code'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                            {{ $data['sabeel_hof'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Ready to Import
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Confirmation -->
    <div class="bg-card p-6 rounded-lg border border-border">
        <h2 class="text-xl font-semibold text-foreground mb-4">Import Confirmation</h2>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        All imported NOC records will be automatically allocated. This action cannot be undone.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('noc.import.process') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="filename" value="{{ $filename }}">
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            
            @foreach($validatedData as $index => $data)
            <input type="hidden" name="validated_data[{{ $index }}][sabeel_code]" value="{{ $data['sabeel_code'] }}">
            <input type="hidden" name="validated_data[{{ $index }}][sabeel_id]" value="{{ $data['sabeel_id'] }}">
            <input type="hidden" name="validated_data[{{ $index }}][sabeel_hof]" value="{{ $data['sabeel_hof'] }}">
            @endforeach

            <div class="flex justify-end space-x-4">
                <a href="{{ route('noc.import') }}" 
                   class="bg-muted text-muted-foreground px-6 py-2 rounded-md hover:bg-muted/90 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary text-primary-foreground px-6 py-2 rounded-md hover:bg-primary/90 transition-colors">
                    Import {{ count($validatedData) }} NOC Records
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="bg-card p-6 rounded-lg border border-border text-center">
        <div class="text-muted-foreground">
            <svg class="w-12 h-12 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <h2 class="text-xl font-semibold text-foreground mb-2">No Valid Records Found</h2>
            <p class="text-muted-foreground mb-4">All records in the CSV file have errors and cannot be imported.</p>
            <a href="{{ route('noc.import') }}" 
               class="bg-primary text-primary-foreground px-6 py-2 rounded-md hover:bg-primary/90 transition-colors">
                Try Again
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
