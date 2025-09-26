@extends('layouts.app')

@section('title', 'Import Mumineen Preview')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-foreground">Import Mumineen Preview</h1>
            <p class="text-muted-foreground">Review the data before importing. Select actions for existing mumineen.</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-card p-4 rounded-lg border border-border">
                <h3 class="font-semibold text-foreground">Total Records</h3>
                <p class="text-2xl font-bold text-primary">{{ count($importData) }}</p>
            </div>
            <div class="bg-card p-4 rounded-lg border border-border">
                <h3 class="font-semibold text-foreground">New Mumineen</h3>
                <p class="text-2xl font-bold text-green-600">{{ count($importData) - count($existingMumineen) }}</p>
            </div>
            <div class="bg-card p-4 rounded-lg border border-border">
                <h3 class="font-semibold text-foreground">Existing Mumineen</h3>
                <p class="text-2xl font-bold text-orange-600">{{ count($existingMumineen) }}</p>
            </div>
            <div class="bg-card p-4 rounded-lg border border-border">
                <h3 class="font-semibold text-foreground">Invalid Sabeels</h3>
                <p class="text-2xl font-bold text-red-600">{{ count($invalidSabeels) }}</p>
            </div>
        </div>

        <!-- Invalid Sabeels Alert -->
        @if(count($invalidSabeels) > 0)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-red-800 mb-2">Invalid Sabeel Codes Found</h3>
                <p class="text-red-700 text-sm mb-3">The following mumineen have invalid sabeel codes and will be skipped:</p>
                <div class="max-h-32 overflow-y-auto">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach(array_slice($invalidSabeels, 0, 10) as $invalid)
                            <li>Row {{ $invalid['row_number'] }}: {{ $invalid['mumin_name'] }} (Sabeel: {{ $invalid['sabeel_code'] }})</li>
                        @endforeach
                        @if(count($invalidSabeels) > 10)
                            <li class="font-semibold">... and {{ count($invalidSabeels) - 10 }} more</li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('mumineen.import.process') }}" class="space-y-6">
            @csrf

            <!-- Bulk Actions -->
            <div class="flex justify-end gap-2 mb-4">
                <button type="button" id="selectAllOverwrite" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 text-sm">
                    Select All Overwrite
                </button>
                <button type="button" id="selectAllSkip" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 text-sm">
                    Select All Skip
                </button>
                <button type="button" id="clearAll" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 text-sm">
                    Clear All
                </button>
            </div>

            <!-- Import Data Table -->
            <div class="bg-card rounded-lg border border-border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Row</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">ITS ID</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Name</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Sabeel</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Mobile</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Age</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Gender</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Type</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Misaq</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Status</th>
                                @if(count($existingMumineen) > 0)
                                    <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach($importData as $data)
                                @php
                                    $isExisting = collect($existingMumineen)->contains('ITS_ID', $data['ITS_ID']);
                                    $existingData = collect($existingMumineen)->firstWhere('ITS_ID', $data['ITS_ID']);
                                @endphp
                                <tr class="hover:bg-muted/50 {{ $isExisting ? 'bg-orange-50' : 'bg-green-50' }}">
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-muted-foreground">{{ $data['row_number'] }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-sm text-foreground">{{ $data['ITS_ID'] }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-foreground">{{ $data['full_name'] }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-muted-foreground">{{ $data['sabeel_code'] }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-muted-foreground">{{ $data['mobile_number'] ?: 'N/A' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-muted-foreground">{{ $data['age'] ?: 'N/A' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-foreground">{{ $data['gender'] ?: 'N/A' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap
                                            {{ $data['type'] === 'resident' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ ucfirst($data['type']) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap
                                            {{ $data['misaq'] === 'done' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $data['misaq'])) }}
                                        </span>
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
                                    @if(count($existingMumineen) > 0)
                                        <td class="px-4 py-3">
                                            @if($isExisting)
                                                <div class="text-orange-600 font-medium mb-1">Existing Mumin</div>
                                                <div class="text-xs text-muted-foreground mb-2">
                                                    Current: {{ $existingData['existing_data']->full_name }}, {{ $existingData['existing_data']->sabeel_code }}
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <input type="radio" name="action[{{ $data['ITS_ID'] }}]" value="skip" id="skip-{{ $data['ITS_ID'] }}" class="rounded border-border text-gray-600 focus:ring-gray-600" checked>
                                                    <label for="skip-{{ $data['ITS_ID'] }}" class="text-sm text-foreground">Skip</label>
                                                    <input type="radio" name="action[{{ $data['ITS_ID'] }}]" value="overwrite" id="overwrite-{{ $data['ITS_ID'] }}" class="rounded border-border text-orange-600 focus:ring-orange-600">
                                                    <label for="overwrite-{{ $data['ITS_ID'] }}" class="text-sm text-foreground">Overwrite</label>
                                                </div>
                                            @else
                                                <span class="text-green-600 font-medium">Will Import</span>
                                                <input type="hidden" name="action[{{ $data['ITS_ID'] }}]" value="create">
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t border-border">
                <a href="{{ route('mumineen.index') }}" 
                   class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                    Process Import
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bulk action buttons
    document.getElementById('selectAllOverwrite').addEventListener('click', function() {
        document.querySelectorAll('input[value="overwrite"]').forEach(radio => {
            radio.checked = true;
        });
    });

    document.getElementById('selectAllSkip').addEventListener('click', function() {
        document.querySelectorAll('input[value="skip"]').forEach(radio => {
            radio.checked = true;
        });
    });

    document.getElementById('clearAll').addEventListener('click', function() {
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            if (radio.value === 'skip') {
                radio.checked = true;
            } else {
                radio.checked = false;
            }
        });
    });
});
</script>
@endsection
