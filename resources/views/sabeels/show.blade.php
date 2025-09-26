@extends('layouts.app')

@section('title', 'Sabeel Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Sabeel Details</h1>
                <p class="text-muted-foreground">View complete information about this sabeel</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                <a href="{{ route('sabeels.edit', $sabeel) }}" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                    Edit Sabeel
                </a>
                <a href="{{ route('sabeels.index') }}" class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Sabeel Information -->
        <div class="bg-card p-6 rounded-lg border border-border mb-6">
            <h2 class="text-xl font-semibold text-foreground mb-4">Sabeel Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Sabeel Code</label>
                    <p class="text-lg font-semibold text-foreground">{{ $sabeel->sabeel_code }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Sector</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($sabeel->sabeel_sector) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Type</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $sabeel->sabeel_type === 'regular' ? 'bg-green-100 text-green-800' : 
                           ($sabeel->sabeel_type === 'student' ? 'bg-yellow-100 text-yellow-800' : 
                           ($sabeel->sabeel_type === 'moallemeen' ? 'bg-purple-100 text-purple-800' :
                           ($sabeel->sabeel_type === 'regular_lock_joint' ? 'bg-blue-100 text-blue-800' :
                           ($sabeel->sabeel_type === 'left_sabeel' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))) }}">
                        {{ ucfirst(str_replace('_', ' ', $sabeel->sabeel_type)) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Status</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $sabeel->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $sabeel->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-muted-foreground mb-1">Address</label>
                <p class="text-foreground">{{ $sabeel->sabeel_address }}</p>
            </div>
        </div>

        <!-- Head of Family -->
        <div class="bg-card p-6 rounded-lg border border-border mb-6">
            <h2 class="text-xl font-semibold text-foreground mb-4">Head of Family</h2>
            
            @if($sabeel->hasHeadOfFamily())
                @php $headOfFamily = $sabeel->getHeadOfFamily(); @endphp
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Profile Image -->
                    <div class="flex-shrink-0">
                        <div class="w-24 h-30 rounded-md overflow-hidden border border-border">
                            <img src="{{ $headOfFamily->getProfileImageUrl() }}" 
                                 alt="{{ $headOfFamily->full_name }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTYiIGhlaWdodD0iMTIwIiB2aWV3Qm94PSIwIDAgOTYgMTIwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cmVjdCB3aWR0aD0iOTYiIGhlaWdodD0iMTIwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik00OCAzMEMzNi45NTQzIDMwIDI4IDM4Ljk1NDMgMjggNTBDMjggNjEuMDQ1NyAzNi45NTQzIDcwIDQ4IDcwQzU5LjA0NTcgNzAgNjggNjEuMDQ1NyA2OCA1MEM2OCAzOC45NTQzIDU5LjA0NTcgMzAgNDggMzBaIiBmaWxsPSIjOUNBM0FGIi8+CjxwYXRoIGQ9Ik0yNyA3NUMxMiA3NSAwIDg3IDAgMTAyVjEyMEMwIDEyMCAxMiAxMjAgMjcgMTIwSDY5QzY5IDEyMCA5NiAxMjAgOTYgMTIwVjgyQzk2IDg3IDg0IDc1IDY5IDc1SDI3WiIgZmlsbD0iIzlDQTNBRiIvPgo8L3N2Zz4K'">
                        </div>
                        <p class="text-xs text-muted-foreground mt-1 text-center">Profile Photo</p>
                    </div>
                    
                    <!-- Personal Details -->
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-muted-foreground mb-1">Name</label>
                            <p class="text-lg font-semibold text-foreground">{{ $headOfFamily->full_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-muted-foreground mb-1">ITS ID</label>
                            <p class="text-foreground font-mono">{{ $headOfFamily->ITS_ID }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-muted-foreground mb-1">Mobile</label>
                            <p class="text-foreground">{{ $headOfFamily->formatted_mobile ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('mumineen.show', $headOfFamily) }}" 
                       class="text-primary hover:text-primary/80 text-sm">
                        View Full Profile →
                    </a>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-800 font-medium">HOF not found</span>
                    </div>
                    <p class="text-red-700 text-sm mt-1">
                        No mumin found with ITS_ID: <span class="font-mono">{{ $sabeel->sabeel_hof }}</span>
                    </p>
                </div>
            @endif
        </div>

        <!-- Family Members -->
        <div class="bg-card p-6 rounded-lg border border-border">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-foreground">Family Members</h2>
                <a href="{{ route('mumineen.create') }}?sabeel_code={{ $sabeel->sabeel_code }}" 
                   class="bg-primary text-primary-foreground px-3 py-1 rounded-md hover:bg-primary/90 text-sm">
                    Add Member
                </a>
            </div>
            
            @if($sabeel->mumineen->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Name</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">ITS ID</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Mobile</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-foreground">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach($sabeel->mumineen as $mumin)
                                <tr class="hover:bg-muted/50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-foreground">{{ $mumin->full_name }}</div>
                                        @if($mumin->ITS_ID === $sabeel->sabeel_hof)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                Head of Family
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-sm text-foreground">{{ $mumin->ITS_ID }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-foreground">{{ $mumin->formatted_mobile ?? 'Not provided' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <a href="{{ route('mumineen.show', $mumin) }}" 
                                               class="text-primary hover:text-primary/80 text-sm">
                                                View
                                            </a>
                                            <a href="{{ route('mumineen.edit', $mumin) }}" 
                                               class="text-gray-600 hover:text-gray-800 text-sm">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-muted-foreground mb-4">No family members registered for this sabeel.</p>
                    <a href="{{ route('mumineen.create') }}?sabeel_code={{ $sabeel->sabeel_code }}" 
                       class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                        Add First Member
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
