@extends('layouts.app')

@section('title', 'Sabeels')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Sabeel Management</h1>
            <p class="text-muted-foreground">Manage families and their information</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-2">
            @can('create-sabeels')
            <a href="{{ route('sabeels.create') }}" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                Add New Sabeel
            </a>
            @endcan
            @can('create-sabeels')
            <button onclick="openImportModal()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                Import Sabeels
            </button>
            @endcan
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">Total Sabeels</h3>
            <p class="text-2xl font-bold text-primary">{{ $statistics['total_sabeels'] }}</p>
        </div>
        
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">Active Sabeels</h3>
            <p class="text-2xl font-bold text-primary">{{ $statistics['active_sabeels'] }}</p>
        </div>
        
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">Total Mumineen</h3>
            <p class="text-2xl font-bold text-primary">{{ $statistics['total_mumineen'] }}</p>
        </div>
        
        <div class="bg-card p-4 rounded-lg border border-border">
            <h3 class="font-semibold text-foreground">Sectors</h3>
            <p class="text-2xl font-bold text-primary">{{ count($statistics['sector_distribution']) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-card p-6 rounded-lg border border-border mb-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Filter Sabeels</h3>
        <form id="filterForm" method="GET" class="space-y-4">
            <!-- First Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="sector" class="block text-sm font-medium text-foreground mb-1">Sector</label>
                    <select name="sector" id="sector" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="">All Sectors</option>
                        <option value="ezzi" {{ request('sector') === 'ezzi' ? 'selected' : '' }}>Ezzi</option>
                        <option value="fakhri" {{ request('sector') === 'fakhri' ? 'selected' : '' }}>Fakhri</option>
                        <option value="hakimi" {{ request('sector') === 'hakimi' ? 'selected' : '' }}>Hakimi</option>
                        <option value="shujai" {{ request('sector') === 'shujai' ? 'selected' : '' }}>Shujai</option>
                        <option value="al_masjid_us_saifee" {{ request('sector') === 'al_masjid_us_saifee' ? 'selected' : '' }}>AL MASJID US SAIFEE</option>
                        <option value="raj_township" {{ request('sector') === 'raj_township' ? 'selected' : '' }}>Raj Township</option>
                        <option value="zainy" {{ request('sector') === 'zainy' ? 'selected' : '' }}>Zainy</option>
                        <option value="student" {{ request('sector') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="mtnc" {{ request('sector') === 'mtnc' ? 'selected' : '' }}>MTNC</option>
                        <option value="unknown" {{ request('sector') === 'unknown' ? 'selected' : '' }}>UNKNOWN</option>
                    </select>
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-foreground mb-1">Type</label>
                    <select name="type" id="type" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="">All Types</option>
                        <option value="regular" {{ request('type') === 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="student" {{ request('type') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="res_without_sabeel" {{ request('type') === 'res_without_sabeel' ? 'selected' : '' }}>Resident Without Sabeel</option>
                        <option value="moallemeen" {{ request('type') === 'moallemeen' ? 'selected' : '' }}>Moallemeen</option>
                        <option value="regular_lock_joint" {{ request('type') === 'regular_lock_joint' ? 'selected' : '' }}>Regular Lock/Joint</option>
                        <option value="left_sabeel" {{ request('type') === 'left_sabeel' ? 'selected' : '' }}>Left Sabeel</option>
                    </select>
                </div>
                
                <div>
                    <label for="member_count_min" class="block text-sm font-medium text-foreground mb-1">Min Members</label>
                    <input type="number" name="member_count_min" id="member_count_min" 
                           value="{{ request('member_count_min') }}" 
                           class="w-full rounded-md border border-border bg-background px-3 py-2" 
                           placeholder="Min members" min="0">
                </div>
                
                <div>
                    <label for="member_count_max" class="block text-sm font-medium text-foreground mb-1">Max Members</label>
                    <input type="number" name="member_count_max" id="member_count_max" 
                           value="{{ request('member_count_max') }}" 
                           class="w-full rounded-md border border-border bg-background px-3 py-2" 
                           placeholder="Max members" min="0">
                </div>
            </div>
            
            <!-- Second Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="hof_status" class="block text-sm font-medium text-foreground mb-1">HOF Status</label>
                    <select name="hof_status" id="hof_status" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="">All HOF Status</option>
                        <option value="has_hof" {{ request('hof_status') === 'has_hof' ? 'selected' : '' }}>Has HOF</option>
                        <option value="no_hof" {{ request('hof_status') === 'no_hof' ? 'selected' : '' }}>No HOF</option>
                    </select>
                </div>
                
                <div>
                    <label for="has_mobile" class="block text-sm font-medium text-foreground mb-1">Mobile Status</label>
                    <select name="has_mobile" id="has_mobile" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="">All Mobile Status</option>
                        <option value="yes" {{ request('has_mobile') === 'yes' ? 'selected' : '' }}>Has Mobile</option>
                        <option value="no" {{ request('has_mobile') === 'no' ? 'selected' : '' }}>No Mobile</option>
                    </select>
                </div>
                
                <div>
                    <label for="sort" class="block text-sm font-medium text-foreground mb-1">Sort By</label>
                    <select name="sort" id="sort" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <option value="sabeel_code" {{ request('sort', 'sabeel_code') === 'sabeel_code' ? 'selected' : '' }}>Sabeel Code</option>
                        <option value="sabeel_address" {{ request('sort') === 'sabeel_address' ? 'selected' : '' }}>Address</option>
                        <option value="sabeel_sector" {{ request('sort') === 'sabeel_sector' ? 'selected' : '' }}>Sector</option>
                        <option value="sabeel_type" {{ request('sort') === 'sabeel_type' ? 'selected' : '' }}>Type</option>
                        <option value="sabeel_hof" {{ request('sort') === 'sabeel_hof' ? 'selected' : '' }}>Head of Family</option>
                        <option value="member_count" {{ request('sort') === 'member_count' ? 'selected' : '' }}>Member Count</option>
                    </select>
                </div>
                
                <div class="flex items-end gap-2">
                    <input type="hidden" name="direction" id="direction" value="{{ request('direction', 'asc') }}">
                    <button type="button" onclick="toggleDirection()" class="px-3 py-2 border border-border bg-background rounded-md hover:bg-accent flex-1" title="Toggle Sort Direction">
                        <div class="flex items-center justify-center">
                            @if(request('direction', 'asc') === 'asc')
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                                <span class="text-sm">Asc</span>
                            @else
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                <span class="text-sm">Desc</span>
                            @endif
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Third Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-foreground mb-1">Search</label>
                    <input type="text" name="search" id="search" 
                           value="{{ request('search') }}" 
                           class="w-full rounded-md border border-border bg-background px-3 py-2" 
                           placeholder="Search by sabeel code, address, HOF, or member name...">
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 w-full">
                        Apply Filters
                    </button>
                </div>
                
                <div class="flex items-end gap-2">
                    <a href="{{ route('sabeels.index') }}" class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90 w-full text-center">
                        Clear All Filters
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Column Visibility Toggle -->
    <div class="bg-card p-4 rounded-lg border border-border mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-foreground">Column Visibility</h3>
            <div class="flex gap-2">
                <button onclick="showAllColumns()" class="text-sm text-primary hover:text-primary/80">Show All</button>
                <button onclick="hideAllColumns()" class="text-sm text-muted-foreground hover:text-foreground">Hide All</button>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" id="toggle-photo" class="column-toggle" data-column="photo" checked>
                <span class="text-sm text-foreground">Photo</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" id="toggle-sabeel-code" class="column-toggle" data-column="sabeel-code" checked>
                <span class="text-sm text-foreground">Sabeel Code</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" id="toggle-hof" class="column-toggle" data-column="hof" checked>
                <span class="text-sm text-foreground">Head of Family</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" id="toggle-sector" class="column-toggle" data-column="sector" checked>
                <span class="text-sm text-foreground">Sector</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" id="toggle-address" class="column-toggle" data-column="address" checked>
                <span class="text-sm text-foreground">Address</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" id="toggle-type" class="column-toggle" data-column="type" checked>
                <span class="text-sm text-foreground">Type</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" id="toggle-members" class="column-toggle" data-column="members" checked>
                <span class="text-sm text-foreground">Members</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" id="toggle-actions" class="column-toggle" data-column="actions" checked>
                <span class="text-sm text-foreground">Actions</span>
            </label>
        </div>
    </div>

    <!-- Sabeels Table -->
    <div class="bg-card rounded-lg border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-muted">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground" data-column="photo">Photo</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground" data-column="sabeel-code">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sabeel_code', 'direction' => request('sort') === 'sabeel_code' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center hover:text-primary">
                                Sabeel Code
                                @if(request('sort') === 'sabeel_code')
                                    @if(request('direction') === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground" data-column="hof">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sabeel_hof', 'direction' => request('sort') === 'sabeel_hof' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center hover:text-primary">
                                Head of Family
                                @if(request('sort') === 'sabeel_hof')
                                    @if(request('direction') === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground" data-column="sector">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sabeel_sector', 'direction' => request('sort') === 'sabeel_sector' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center hover:text-primary">
                                Sector
                                @if(request('sort') === 'sabeel_sector')
                                    @if(request('direction') === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground" data-column="address">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sabeel_address', 'direction' => request('sort') === 'sabeel_address' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center hover:text-primary">
                                Address
                                @if(request('sort') === 'sabeel_address')
                                    @if(request('direction') === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground" data-column="type">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sabeel_type', 'direction' => request('sort') === 'sabeel_type' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center hover:text-primary">
                                Type
                                @if(request('sort') === 'sabeel_type')
                                    @if(request('direction') === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground" data-column="members">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'member_count', 'direction' => request('sort') === 'member_count' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center hover:text-primary">
                                Members
                                @if(request('sort') === 'member_count')
                                    @if(request('direction') === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-foreground" data-column="actions">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($sabeels as $sabeel)
                        <tr class="hover:bg-muted/50">
                            <td class="px-4 py-3" data-column="photo">
                                @if($sabeel->hasHeadOfFamily())
                                    <div class="w-20 h-24 rounded-md overflow-hidden border border-border bg-gray-50">
                                        <img src="{{ $sabeel->getHeadOfFamily()->getProfileImageUrl() }}"
                                             alt="{{ $sabeel->getHeadOfFamily()->full_name }}"
                                             class="w-full h-full object-contain"
                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iOTYiIHZpZXdCb3g9IjAgMCA4MCA5NiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9Ijk2IiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik00MCAyMEMzMi4yNjggMjAgMjYgMjYuMjY4IDI2IDM0QzI2IDQxLjczMiAzMi4yNjggNDggNDAgNDhDNDcuNzMyIDQ4IDU0IDQxLjczMiA1NCAzNEM1NCAyNi4yNjggNDcuNzMyIDIwIDQwIDIwWiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNMjAgNjRDMTAgNjQgMiA3MiAyIDgyVjgwQzIgODAgMTAgODAgMjAgODBINjBDNjAgODAgODAgODAgODAgODBWODJDODAgNzIgNzIgNjQgNjAgNjRIMjBaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPgo='">
                                    </div>
                                @else
                                    <div class="w-20 h-24 rounded-md overflow-hidden border border-border bg-gray-50 flex items-center justify-center">
                                        <div class="text-xs text-muted-foreground text-center">
                                            <div class="text-red-500 font-medium">HOF</div>
                                            <div class="text-red-500 font-medium">Not Found</div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3" data-column="sabeel-code">
                                <div class="font-medium text-foreground">{{ $sabeel->sabeel_code }}</div>
                            </td>
                            <td class="px-4 py-3" data-column="hof">
                                @if($sabeel->hasHeadOfFamily())
                                    @php $headOfFamily = $sabeel->getHeadOfFamily(); @endphp
                                    <div class="text-sm text-foreground">{{ $headOfFamily->full_name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $headOfFamily->ITS_ID }}</div>
                                @else
                                    <div class="text-sm text-red-600 font-medium">HOF not found</div>
                                    <div class="text-xs text-muted-foreground">{{ $sabeel->sabeel_hof }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3" data-column="sector">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($sabeel->sabeel_sector) }}
                                </span>
                            </td>
                            <td class="px-4 py-3" data-column="address">
                                <div class="text-sm text-muted-foreground max-w-xs truncate">
                                    {{ Str::limit($sabeel->sabeel_address, 50) }}
                                </div>
                            </td>
                            <td class="px-4 py-3" data-column="type">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap
                                    {{ $sabeel->sabeel_type === 'regular' ? 'bg-green-100 text-green-800' : 
                                       ($sabeel->sabeel_type === 'student' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($sabeel->sabeel_type === 'moallemeen' ? 'bg-purple-100 text-purple-800' :
                                       ($sabeel->sabeel_type === 'regular_lock_joint' ? 'bg-blue-100 text-blue-800' :
                                       ($sabeel->sabeel_type === 'left_sabeel' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))) }}">
                                    {{ ucfirst(str_replace('_', ' ', $sabeel->sabeel_type)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3" data-column="members">
                                <span class="text-sm font-medium text-foreground">{{ $sabeel->mumineen->count() }}</span>
                            </td>
                            <td class="px-4 py-3" data-column="actions">
                                <div class="flex gap-2">
                                    @can('view-sabeels')
                                    <a href="{{ route('sabeels.show', $sabeel) }}" 
                                       class="text-primary hover:text-primary/80 text-sm">
                                        View
                                    </a>
                                    @endcan
                                    @can('edit-sabeels')
                                    <a href="{{ route('sabeels.edit', $sabeel) }}" 
                                       class="text-gray-600 hover:text-gray-800 text-sm">
                                        Edit
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-muted-foreground">
                                No sabeels found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $sabeels->links() }}
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-card rounded-lg border border-border w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-foreground">Import Sabeels</h3>
                    <button onclick="closeImportModal()" class="text-muted-foreground hover:text-foreground">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-muted-foreground mb-2">
                            Import sabeels from CSV (.csv) file. Download sample file to see the required format.
                        </p>
                        <a href="{{ route('sabeels.sample') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Sample File
                        </a>
                    </div>
                    
                    <form id="importForm" method="POST" action="{{ route('sabeels.import') }}" enctype="multipart/form-data">
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
                        
                        <div class="flex justify-end gap-2 pt-4">
                            <button type="button" onclick="closeImportModal()" 
                                    class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                                Import
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
    document.getElementById('importForm').reset();
}

// Close modal when clicking outside
document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImportModal();
    }
});

// Toggle sort direction
function toggleDirection() {
    const directionInput = document.getElementById('direction');
    const currentDirection = directionInput.value;
    directionInput.value = currentDirection === 'asc' ? 'desc' : 'asc';
    
    // Submit the filter form specifically
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.submit();
    }
}

// Column visibility functionality
document.addEventListener('DOMContentLoaded', function() {
    // Load saved column preferences
    loadColumnPreferences();
    
    // Add event listeners to column toggles
    document.querySelectorAll('.column-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const column = this.getAttribute('data-column');
            const isVisible = this.checked;
            toggleColumn(column, isVisible);
            saveColumnPreferences();
        });
    });
});

function toggleColumn(column, isVisible) {
    // Toggle table headers
    const headers = document.querySelectorAll(`th[data-column="${column}"]`);
    headers.forEach(header => {
        header.style.display = isVisible ? '' : 'none';
    });
    
    // Toggle table cells
    const cells = document.querySelectorAll(`td[data-column="${column}"]`);
    cells.forEach(cell => {
        cell.style.display = isVisible ? '' : 'none';
    });
}

function showAllColumns() {
    document.querySelectorAll('.column-toggle').forEach(toggle => {
        toggle.checked = true;
        const column = toggle.getAttribute('data-column');
        toggleColumn(column, true);
    });
    saveColumnPreferences();
}

function hideAllColumns() {
    document.querySelectorAll('.column-toggle').forEach(toggle => {
        toggle.checked = false;
        const column = toggle.getAttribute('data-column');
        toggleColumn(column, false);
    });
    saveColumnPreferences();
}

function saveColumnPreferences() {
    const preferences = {};
    document.querySelectorAll('.column-toggle').forEach(toggle => {
        const column = toggle.getAttribute('data-column');
        preferences[column] = toggle.checked;
    });
    localStorage.setItem('sabeel-column-preferences', JSON.stringify(preferences));
}

function loadColumnPreferences() {
    const saved = localStorage.getItem('sabeel-column-preferences');
    if (saved) {
        const preferences = JSON.parse(saved);
        document.querySelectorAll('.column-toggle').forEach(toggle => {
            const column = toggle.getAttribute('data-column');
            if (preferences.hasOwnProperty(column)) {
                toggle.checked = preferences[column];
                toggleColumn(column, preferences[column]);
            }
        });
    }
}
</script>
@endsection
