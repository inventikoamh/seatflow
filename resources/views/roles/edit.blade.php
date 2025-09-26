@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <!-- Header -->
    <div class="mb-8 animate-fade-in">
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-foreground mb-2">Edit Role</h1>
                    <p class="text-muted-foreground">Update role information and permissions</p>
                </div>
                <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-secondary text-secondary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-secondary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Roles
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Role Form -->
    <div class="bg-card rounded-lg border border-border shadow-sm">
        <div class="p-6 border-b border-border">
            <h3 class="text-lg font-semibold text-foreground">Role Information</h3>
            <p class="text-muted-foreground text-sm mt-1">Update the role details and permissions.</p>
        </div>
        
        <form method="POST" action="{{ route('roles.update', $role) }}" class="p-6" data-validate>
            @csrf
            @method('PUT')

            <!-- Role Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-foreground mb-2">Role Name *</label>
                <input id="name" type="text" name="name" value="{{ old('name', $role->name) }}" required autofocus class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('name') border-destructive @enderror" placeholder="Enter role name">
                @error('name')
                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-foreground mb-2">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('description') border-destructive @enderror" placeholder="Enter role description">{{ old('description', $role->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Permissions -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-foreground mb-3">Permissions *</label>
                
                @foreach($permissions as $module => $modulePermissions)
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-foreground mb-3 capitalize">{{ $module }} Permissions</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($modulePermissions as $permission)
                                <label class="flex items-center space-x-3 p-3 border border-input rounded-md hover:bg-accent/50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }} class="rounded border-input text-primary focus:ring-ring">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-foreground">{{ $permission->name }}</div>
                                        @if($permission->description)
                                            <div class="text-xs text-muted-foreground">{{ $permission->description }}</div>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                
                @error('permissions')
                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end mt-8 space-x-4">
                <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-secondary text-secondary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-secondary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-primary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                    Update Role
                </button>
            </div>
        </form>
    </div>
@endsection
