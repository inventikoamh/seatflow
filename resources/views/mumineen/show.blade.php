@extends('layouts.app')

@section('title', 'Mumin Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Mumin Details</h1>
                <p class="text-muted-foreground">View complete information about this family member</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                <a href="{{ route('mumineen.edit', $mumin) }}" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                    Edit Mumin
                </a>
                <a href="{{ route('mumineen.index') }}" class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Mumin Information -->
        <div class="bg-card p-6 rounded-lg border border-border mb-6">
            <h2 class="text-xl font-semibold text-foreground mb-4">Personal Information</h2>
            
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    <div class="w-32 h-40 rounded-md overflow-hidden border border-border">
                        <img src="{{ $mumin->getProfileImageUrl() }}" 
                             alt="{{ $mumin->full_name }}"
                             class="w-full h-full object-cover"
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTI4IiBoZWlnaHQ9IjE2MCIgdmlld0JveD0iMCAwIDEyOCAxNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjgiIGhlaWdodD0iMTYwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik02NCA0MEM1Mi45NTQzIDQwIDQ0IDQ4Ljk1NDMgNDQgNjBDNDQgNzEuMDQ1NyA1Mi45NTQzIDgwIDY0IDgwQzc1LjA0NTcgODAgODQgNzEuMDQ1NyA4NCA2MEM4NCA0OC45NTQzIDc1LjA0NTcgNDAgNjQgNDBaIiBmaWxsPSIjOUNBM0FGIi8+CjxwYXRoIGQ9Ik0zNiAxMDBDMTYgMTAwIDAgMTE2IDAgMTM2VjE2MEMwIDE2MCAxNiAxNjAgMzYgMTYwSDkyQzkyIDE2MCAxMjggMTYwIDEyOCAxNjBWMzZDMTI4IDExNiAxMTIgMTAwIDkyIDEwMEgzNloiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+Cg=='">
                    </div>
                    <p class="text-xs text-muted-foreground mt-2 text-center">Profile Photo</p>
                </div>
                
                <!-- Personal Details -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Full Name</label>
                        <p class="text-lg font-semibold text-foreground">{{ $mumin->full_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">ITS ID</label>
                        <p class="text-lg font-mono text-foreground">{{ $mumin->ITS_ID }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Mobile Number</label>
                        @if($mumin->mobile_number)
                            <p class="text-lg text-foreground">{{ $mumin->formatted_mobile }}</p>
                        @else
                            <p class="text-lg text-muted-foreground">Not provided</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Age</label>
                        <p class="text-lg text-foreground">{{ $mumin->age ?: 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Gender</label>
                        <p class="text-lg text-foreground">{{ $mumin->gender ?: 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Type</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $mumin->type === 'resident' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                            {{ ucfirst($mumin->type) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Misaq</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $mumin->misaq === 'done' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $mumin->misaq === 'done' ? 'Done' : 'Not Done' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Registration Date</label>
                        <p class="text-lg text-foreground">{{ $mumin->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sabeel Information -->
        <div class="bg-card p-6 rounded-lg border border-border mb-6">
            <h2 class="text-xl font-semibold text-foreground mb-4">Sabeel Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Sabeel Code</label>
                    <p class="text-lg font-semibold text-foreground">{{ $mumin->sabeel->sabeel_code }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Sector</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($mumin->sabeel->sabeel_sector) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Type</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $mumin->sabeel->sabeel_type === 'regular' ? 'bg-green-100 text-green-800' : 
                           ($mumin->sabeel->sabeel_type === 'student' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $mumin->sabeel->sabeel_type)) }}
                    </span>
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-muted-foreground mb-1">Address</label>
                <p class="text-foreground">{{ $mumin->sabeel->sabeel_address }}</p>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('sabeels.show', $mumin->sabeel) }}" 
                   class="text-primary hover:text-primary/80 text-sm">
                    View Complete Sabeel Details →
                </a>
            </div>
        </div>

        <!-- Head of Family Status -->
        @if($mumin->isHeadOfFamily())
        <div class="bg-card p-6 rounded-lg border border-border mb-6">
            <h2 class="text-xl font-semibold text-foreground mb-4">Head of Family</h2>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-yellow-800 font-medium">This mumin is the head of family for the following sabeels:</span>
                </div>
            </div>
            
            <div class="mt-4">
                @foreach($headOfFamilySabeels as $sabeel)
                    <div class="flex items-center justify-between p-3 bg-muted rounded-lg mb-2">
                        <div>
                            <span class="font-medium text-foreground">{{ $sabeel->sabeel_code }}</span>
                            <span class="text-sm text-muted-foreground ml-2">{{ ucfirst($sabeel->sabeel_sector) }}</span>
                        </div>
                        <a href="{{ route('sabeels.show', $sabeel) }}" 
                           class="text-primary hover:text-primary/80 text-sm">
                            View Sabeel
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Family Members -->
        <div class="bg-card p-6 rounded-lg border border-border">
            <h2 class="text-xl font-semibold text-foreground mb-4">Family Members</h2>
            
            @php
                $familyMembers = $mumin->sabeel->mumineen()->where('id', '!=', $mumin->id)->get();
            @endphp
            
            @if($familyMembers->count() > 0)
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
                            @foreach($familyMembers as $member)
                                <tr class="hover:bg-muted/50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-foreground">{{ $member->full_name }}</div>
                                        @if($member->ITS_ID === $mumin->sabeel->sabeel_hof)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                Head of Family
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-sm text-foreground">{{ $member->ITS_ID }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-foreground">{{ $member->formatted_mobile ?? 'Not provided' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <a href="{{ route('mumineen.show', $member) }}" 
                                               class="text-primary hover:text-primary/80 text-sm">
                                                View
                                            </a>
                                            <a href="{{ route('mumineen.edit', $member) }}" 
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
                    <p class="text-muted-foreground">No other family members registered for this sabeel.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
