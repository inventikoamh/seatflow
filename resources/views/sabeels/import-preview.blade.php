@extends('layouts.app')

@section('title', 'Import Preview')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Import Preview</h1>
            <p class="text-muted-foreground">Review and select actions for sabeels to be imported</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('sabeels.index') }}" class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                Back to Sabeels
            </a>
        </div>
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

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">Total Records</h3>
            <p class="text-2xl font-bold text-primary">{{ count($importData) }}</p>
        </div>
        
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">New Records</h3>
            <p class="text-2xl font-bold text-green-600">{{ count($importData) - count($existingSabeels) }}</p>
        </div>
        
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">Existing Records</h3>
            <p class="text-2xl font-bold text-orange-600">{{ count($existingSabeels) }}</p>
        </div>
    </div>

    @if(count($existingSabeels) > 0)
        <div class="bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-orange-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ count($existingSabeels) }} sabeels already exist. Please select an action for each.</span>
            </div>
        </div>
    @endif


    <form method="POST" action="{{ route('sabeels.import.process') }}" id="importForm">
        @csrf
        
        <!-- Bulk Actions -->
        @if(count($existingSabeels) > 0)
            <div class="bg-card p-4 rounded-lg border border-border mb-6">
                <h3 class="font-semibold text-foreground mb-3">Bulk Actions for Existing Records</h3>
                <div class="flex gap-4">
                    <button type="button" onclick="selectAll('overwrite')" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 text-sm">
                        Select All Overwrite
                    </button>
                    <button type="button" onclick="selectAll('skip')" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm">
                        Select All Skip
                    </button>
                    <button type="button" onclick="clearAll()" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                        Clear All
                    </button>
                </div>
            </div>
        @endif

        <!-- Import Data Table -->
        <div class="bg-card rounded-lg border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Row</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Sabeel Code</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Address</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Sector</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Type</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-foreground">HOF ITS_ID</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($importData as $data)
                            @php
                                $isExisting = collect($existingSabeels)->contains('sabeel_code', $data['sabeel_code']);
                                $existingData = collect($existingSabeels)->firstWhere('sabeel_code', $data['sabeel_code']);
                            @endphp
                            <tr class="hover:bg-muted/50 {{ $isExisting ? 'bg-orange-50' : 'bg-green-50' }}">
                                <td class="px-4 py-3">
                                    <span class="text-sm text-muted-foreground">{{ $data['row_number'] }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-foreground">{{ $data['sabeel_code'] }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-muted-foreground max-w-xs truncate">
                                        {{ Str::limit($data['sabeel_address'], 30) }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($data['sabeel_sector']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap
                                        {{ $data['sabeel_type'] === 'regular' ? 'bg-green-100 text-green-800' : 
                                           ($data['sabeel_type'] === 'student' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($data['sabeel_type'] === 'left_sabeel' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $data['sabeel_type'])) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-mono text-sm text-foreground">{{ $data['sabeel_hof'] }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($isExisting)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            Existing
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            New
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($isExisting)
                                        <div class="flex gap-2">
                                            <label class="flex items-center">
                                                <input type="radio" name="action[{{ $data['sabeel_code'] }}]" value="overwrite" 
                                                       class="rounded border-border text-orange-600 focus:ring-orange-600">
                                                <span class="ml-1 text-xs text-orange-600">Overwrite</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="action[{{ $data['sabeel_code'] }}]" value="skip" 
                                                       class="rounded border-border text-gray-600 focus:ring-gray-600" checked>
                                                <span class="ml-1 text-xs text-gray-600">Skip</span>
                                            </label>
                                        </div>
                                    @else
                                        <span class="text-xs text-green-600 font-medium">Will Import</span>
                                        <input type="hidden" name="action[{{ $data['sabeel_code'] }}]" value="create">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-4 pt-6">
            <a href="{{ route('sabeels.index') }}" 
               class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                Cancel Import
            </a>
            <button type="submit" 
                    class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                Process Import
            </button>
        </div>
    </form>
</div>

<script>
function selectAll(action) {
    const radioButtons = document.querySelectorAll(`input[name*="action"][value="${action}"]`);
    radioButtons.forEach(radio => {
        radio.checked = true;
    });
}

function clearAll() {
    const radioButtons = document.querySelectorAll('input[name*="action"]');
    radioButtons.forEach(radio => {
        radio.checked = false;
    });
}

// Form validation
document.getElementById('importForm').addEventListener('submit', function(e) {
    const existingRecords = {{ count($existingSabeels) }};
    const totalRecords = {{ count($importData) }};
    const newRecords = totalRecords - existingRecords;
    
    // Check existing records have radio buttons selected
    if (existingRecords > 0) {
        const checkedRadios = document.querySelectorAll('input[name*="action"]:checked');
        
        if (checkedRadios.length !== existingRecords) {
            e.preventDefault();
            alert('Please select an action for all existing records.');
            return false;
        }
    }
    
    // Check new records have hidden inputs
    if (newRecords > 0) {
        const hiddenInputs = document.querySelectorAll('input[name*="action"][type="hidden"]');
        
        if (hiddenInputs.length !== newRecords) {
            e.preventDefault();
            alert('Missing action for some new records. Please refresh and try again.');
            return false;
        }
    }
});
</script>
@endsection
