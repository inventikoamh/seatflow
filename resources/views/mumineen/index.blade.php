@extends('layouts.app')

@section('title', 'Mumineen')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Mumin Management</h1>
            <p class="text-muted-foreground">Manage individual family members</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-2">
            <a href="{{ route('mumineen.create') }}" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                Add New Mumin
            </a>
            <button onclick="openImportModal()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                Import Mumineen
            </button>
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

    @if(isset($sabeelNotFound) && $sabeelNotFound)
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">Sabeel Not Found:</span>
                <span class="ml-1">No sabeel found with code "{{ request('sabeel_code') }}". Please check the sabeel code and try again.</span>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">Total Mumineen</h3>
            <p class="text-2xl font-bold text-primary">{{ $statistics['total_mumineen'] }}</p>
        </div>
        
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">With Mobile</h3>
            <p class="text-2xl font-bold text-primary">{{ $statistics['with_mobile'] }}</p>
        </div>
        
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">Without Mobile</h3>
            <p class="text-2xl font-bold text-primary">{{ $statistics['without_mobile'] }}</p>
        </div>
        
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">Head of Family</h3>
            <p class="text-2xl font-bold text-primary">{{ $statistics['head_of_family_count'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-card p-6 rounded-lg border border-border mb-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Filter Mumineen</h3>
        <form method="GET" class="space-y-4">
            <!-- First Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="sabeel_code" class="block text-sm font-medium text-foreground mb-1">Sabeel Code</label>
                    <input type="text" name="sabeel_code" id="sabeel_code" 
                           value="{{ request('sabeel_code') }}" 
                           class="w-full rounded-md border border-border bg-background px-3 py-2 @if(isset($sabeelNotFound) && $sabeelNotFound) border-red-500 @endif" 
                           placeholder="Enter sabeel code">
                    @if(isset($sabeelNotFound) && $sabeelNotFound)
                        <p class="text-red-500 text-xs mt-1">Sabeel not found</p>
                    @endif
                </div>
                
                <div>
                    <label for="sector" class="block text-sm font-medium text-foreground mb-1">Sector</label>
                    <select name="sector" id="sector" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="">All Sectors</option>
                        <option value="ezzi" {{ request('sector') === 'ezzi' ? 'selected' : '' }}>Ezzi</option>
                        <option value="fakhri" {{ request('sector') === 'fakhri' ? 'selected' : '' }}>Fakhri</option>
                        <option value="hakimi" {{ request('sector') === 'hakimi' ? 'selected' : '' }}>Hakimi</option>
                        <option value="shujai" {{ request('sector') === 'shujai' ? 'selected' : '' }}>Shujai</option>
                    </select>
                </div>
                
                <div>
                    <label for="gender" class="block text-sm font-medium text-foreground mb-1">Gender</label>
                    <select name="gender" id="gender" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="">All Genders</option>
                        <option value="M" {{ request('gender') === 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ request('gender') === 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-foreground mb-1">Type</label>
                    <select name="type" id="type" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="">All Types</option>
                        <option value="resident" {{ request('type') === 'resident' ? 'selected' : '' }}>Resident</option>
                        <option value="mehmaan" {{ request('type') === 'mehmaan' ? 'selected' : '' }}>Mehmaan</option>
                    </select>
                </div>
            </div>
            
            <!-- Second Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="misaq" class="block text-sm font-medium text-foreground mb-1">Misaq Status</label>
                    <select name="misaq" id="misaq" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="">All Status</option>
                        <option value="done" {{ request('misaq') === 'done' ? 'selected' : '' }}>Done</option>
                        <option value="not_done" {{ request('misaq') === 'not_done' ? 'selected' : '' }}>Not Done</option>
                    </select>
                </div>
                
                <div>
                    <label for="age_min" class="block text-sm font-medium text-foreground mb-1">Min Age</label>
                    <input type="number" name="age_min" id="age_min" 
                           value="{{ request('age_min') }}" 
                           class="w-full rounded-md border border-border bg-background px-3 py-2" 
                           placeholder="Min age" min="0" max="120">
                </div>
                
                <div>
                    <label for="age_max" class="block text-sm font-medium text-foreground mb-1">Max Age</label>
                    <input type="number" name="age_max" id="age_max" 
                           value="{{ request('age_max') }}" 
                           class="w-full rounded-md border border-border bg-background px-3 py-2" 
                           placeholder="Max age" min="0" max="120">
                </div>
                
                <div>
                    <label for="has_mobile" class="block text-sm font-medium text-foreground mb-1">Mobile Status</label>
                    <select name="has_mobile" id="has_mobile" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="">All</option>
                        <option value="yes" {{ request('has_mobile') === 'yes' ? 'selected' : '' }}>With Mobile</option>
                        <option value="no" {{ request('has_mobile') === 'no' ? 'selected' : '' }}>Without Mobile</option>
                    </select>
                </div>
            </div>
            
            <!-- Third Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-foreground mb-1">Search</label>
                    <input type="text" name="search" id="search" 
                           value="{{ request('search') }}" 
                           class="w-full rounded-md border border-border bg-background px-3 py-2" 
                           placeholder="Search by ITS ID, Name, or Mobile number...">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="include_left_sabeels" id="include_left_sabeels" value="yes" 
                           {{ request('include_left_sabeels') === 'yes' ? 'checked' : '' }}
                           class="rounded border-border text-primary focus:ring-primary">
                    <label for="include_left_sabeels" class="ml-2 text-sm text-foreground">
                        Include Left Sabeels
                    </label>
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 w-full">
                        Apply Filters
                    </button>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end gap-2 pt-2 border-t border-border">
                <a href="{{ route('mumineen.index') }}" class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                    Clear All Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Mumineen Table -->
    <div class="bg-card rounded-lg border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-muted">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Photo</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">ITS ID</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Name</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Sabeel</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Sector</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Age</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Gender</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Misaq</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Mobile</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($mumineen as $mumin)
                        <tr class="hover:bg-muted/50">
                            <td class="px-4 py-3">
                                <div class="w-20 h-24 rounded-md overflow-hidden border border-border bg-gray-50">
                                    <img src="{{ $mumin->getProfileImageUrl() }}" 
                                         alt="{{ $mumin->full_name }}"
                                         class="w-full h-full object-contain"
                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iOTYiIHZpZXdCb3g9IjAgMCA4MCA5NiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9Ijk2IiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik00MCAyMEMzMi4yNjggMjAgMjYgMjYuMjY4IDI2IDM0QzI2IDQxLjczMiAzMi4yNjggNDggNDAgNDhDNDcuNzMyIDQ4IDU0IDQxLjczMiA1NCAzNEM1NCAyNi4yNjggNDcuNzMyIDIwIDQwIDIwWiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNMjAgNjRDMTAgNjQgMiA3MiAyIDgyVjgwQzIgODAgMTAgODAgMjAgODBINjBDNjAgODAgODAgODAgODAgODBWODJDODAgNzIgNzIgNjQgNjAgNjRIMjBaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPgo='">
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm text-foreground">{{ $mumin->ITS_ID }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-foreground">{{ $mumin->full_name }}</div>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @if($mumin->isHeadOfFamily())
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Head of Family
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $mumin->type === 'resident' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ ucfirst($mumin->type) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-foreground">{{ $mumin->sabeel->sabeel_code }}</div>
                                <div class="text-xs text-muted-foreground">{{ ucfirst($mumin->sabeel->sabeel_type) }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($mumin->sabeel->sabeel_sector) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-muted-foreground">{{ $mumin->age ?: 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-foreground">{{ $mumin->gender ?: 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $mumin->misaq === 'done' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $mumin->misaq === 'done' ? 'Done' : 'Not Done' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($mumin->mobile_number)
                                    <span class="text-sm text-foreground">{{ $mumin->formatted_mobile }}</span>
                                @else
                                    <span class="text-sm text-muted-foreground">Not provided</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    @can('view-mumineen')
                                    <a href="{{ route('mumineen.show', $mumin) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View
                                    </a>
                                    @endcan
                                    @can('edit-mumineen')
                                    <a href="{{ route('mumineen.edit', $mumin) }}" 
                                       class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                        Edit
                                    </a>
                                    @endcan
                                    @can('delete-mumineen')
                                    <form method="POST" action="{{ route('mumineen.destroy', $mumin) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this mumin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-muted-foreground">
                                No mumineen found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $mumineen->links() }}
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-card rounded-lg border border-border w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-foreground">Import Mumineen</h3>
                    <button onclick="closeImportModal()" class="text-muted-foreground hover:text-foreground">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-muted-foreground mb-2">
                            Import mumineen from CSV (.csv) file. Download sample file to see the required format.
                        </p>
                        <a href="{{ route('mumineen.sample') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Sample File
                        </a>
                    </div>
                    
                    <form method="POST" action="{{ route('mumineen.import') }}" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="import_file" class="block text-sm font-medium text-foreground mb-1">
                                Select File <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="import_file" id="import_file" 
                                   accept=".csv"
                                   class="w-full rounded-md border border-border bg-background px-3 py-2 @error('import_file') border-red-500 @enderror"
                                   required>
                            @error('import_file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex justify-end gap-4 mt-6">
                            <button type="button" onclick="closeImportModal()" 
                                    class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                                Import File
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImportModal();
    }
});
</script>
@endsection
