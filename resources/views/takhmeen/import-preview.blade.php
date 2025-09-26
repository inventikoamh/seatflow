@extends('layouts.app')

@section('title', 'Import Preview - Takhmeen')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Import Preview</h1>
            <p class="text-muted-foreground mt-2">Review your data before importing</p>
        </div>
        <a href="{{ route('takhmeen.import') }}" 
           class="bg-muted text-muted-foreground px-4 py-2 rounded-md hover:bg-muted/90 transition-colors">
            Back to Import
        </a>
    </div>

    <!-- Event Information -->
    <div class="bg-card p-6 rounded-lg border border-border mb-8">
        <h2 class="text-xl font-semibold text-foreground mb-4">Import Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

    @if(count($errors) > 0)
    <!-- Errors Section -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
        <h2 class="text-lg font-semibold text-red-900 mb-4">Validation Errors ({{ count($errors) }} records)</h2>
        <div class="space-y-3">
            @foreach($errors as $rowNumber => $rowErrors)
            <div class="bg-white border border-red-200 rounded p-3">
                <p class="text-sm font-medium text-red-800 mb-1">Row {{ $rowNumber }}:</p>
                <ul class="text-sm text-red-700 list-disc list-inside">
                    @foreach($rowErrors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(count($validatedData) > 0)
    <!-- Valid Records Preview -->
    <div class="bg-card rounded-lg border border-border overflow-hidden mb-8">
        <div class="px-6 py-4 bg-muted border-b border-border">
            <h2 class="text-lg font-semibold text-foreground">Valid Records Preview ({{ count($validatedData) }} records)</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-muted">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Sabeel Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">HOF</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($validatedData as $index => $data)
                    <tr class="hover:bg-accent/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-foreground">
                            {{ $data['sabeel_code'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                            {{ $data['sabeel_hof'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                            {{ $data['formatted_amount'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Confirmation -->
    <div class="bg-card p-6 rounded-lg border border-border">
        <h2 class="text-xl font-semibold text-foreground mb-4">Confirm Import</h2>
        
        @if(count($validatedData) > 0)
        <form action="{{ route('takhmeen.import.process') }}" method="POST" class="space-y-6">
            @csrf
            
            <input type="hidden" name="filename" value="{{ $filename }}">
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            
            @foreach($validatedData as $index => $data)
            <input type="hidden" name="validated_data[{{ $index }}][sabeel_code]" value="{{ $data['sabeel_code'] }}">
            <input type="hidden" name="validated_data[{{ $index }}][amount]" value="{{ $data['amount'] }}">
            <input type="hidden" name="validated_data[{{ $index }}][sabeel_id]" value="{{ $data['sabeel_id'] }}">
            <input type="hidden" name="validated_data[{{ $index }}][sabeel_hof]" value="{{ $data['sabeel_hof'] }}">
            @endforeach
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Import Confirmation</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>You are about to import <strong>{{ count($validatedData) }}</strong> takhmeen records for <strong>{{ $event->name }}</strong>.</p>
                            <p class="mt-1">This action cannot be undone. Please review the data above before proceeding.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('takhmeen.import') }}" 
                   class="bg-secondary text-secondary-foreground px-6 py-2 rounded-md hover:bg-secondary/90 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary text-primary-foreground px-6 py-2 rounded-md hover:bg-primary/90 transition-colors">
                    Import {{ count($validatedData) }} Records
                </button>
            </div>
        </form>
        @else
        <div class="text-center text-muted-foreground py-8">
            <p>No valid records found to import.</p>
            <a href="{{ route('takhmeen.import') }}" 
               class="text-primary hover:text-primary/80 underline mt-2 inline-block">
                Go back and fix your CSV file
            </a>
        </div>
        @endif
    </div>
    @else
    <!-- No Valid Records -->
    <div class="bg-card p-6 rounded-lg border border-border text-center">
        <h2 class="text-xl font-semibold text-foreground mb-4">No Valid Records Found</h2>
        <p class="text-muted-foreground mb-6">All records in your CSV file have validation errors. Please fix the errors and try again.</p>
        <a href="{{ route('takhmeen.import') }}" 
           class="bg-primary text-primary-foreground px-6 py-2 rounded-md hover:bg-primary/90 transition-colors">
            Back to Import
        </a>
    </div>
    @endif
</div>
@endsection
