@extends('layouts.app')

@section('title', 'Role Details')

@section('content')
    <!-- Header -->
    <div class="mb-8 animate-fade-in">
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-foreground mb-2">{{ $role->name }}</h1>
                    <p class="text-muted-foreground">{{ $role->description ?: 'No description provided' }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('roles.edit', $role) }}" class="inline-flex items-center px-4 py-2 bg-primary text-primary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Role
                    </a>
                    <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-secondary text-secondary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-secondary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Roles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Role Information -->
        <div class="bg-card rounded-lg border border-border shadow-sm">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Role Information</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Name:</span>
                    <span class="text-sm text-foreground">{{ $role->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Slug:</span>
                    <span class="text-sm text-foreground font-mono">{{ $role->slug }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Status:</span>
                    @if($role->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                            Inactive
                        </span>
                    @endif
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Created:</span>
                    <span class="text-sm text-foreground">{{ $role->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Last Updated:</span>
                    <span class="text-sm text-foreground">{{ $role->updated_at->format('M d, Y') }}</span>
                </div>
                @if($role->description)
                    <div>
                        <span class="text-sm font-medium text-muted-foreground block mb-2">Description:</span>
                        <p class="text-sm text-foreground">{{ $role->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Users with this Role -->
        <div class="bg-card rounded-lg border border-border shadow-sm">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Users with this Role</h3>
                <p class="text-sm text-muted-foreground mt-1">{{ $role->users->count() }} users assigned</p>
            </div>
            <div class="p-6">
                @if($role->users->count() > 0)
                    <div class="space-y-3">
                        @foreach($role->users as $user)
                            <div class="flex items-center justify-between p-3 border border-border rounded-md hover:bg-accent/50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-primary-foreground font-medium text-sm">
                                        {{ substr($user->display_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-foreground">{{ $user->display_name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('users.show', $user) }}" class="text-primary hover:text-primary/80 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-muted-foreground mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <p class="text-muted-foreground">No users assigned to this role</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Permissions -->
    <div class="mt-8 bg-card rounded-lg border border-border shadow-sm">
        <div class="p-6 border-b border-border">
            <h3 class="text-lg font-semibold text-foreground">Permissions</h3>
            <p class="text-sm text-muted-foreground mt-1">{{ $role->permissions->count() }} permissions assigned</p>
        </div>
        <div class="p-6">
            @if($role->permissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($role->permissions->groupBy('module') as $module => $permissions)
                        <div class="border border-border rounded-md p-4">
                            <h4 class="text-sm font-semibold text-foreground mb-3 capitalize">{{ $module }} Permissions</h4>
                            <div class="space-y-2">
                                @foreach($permissions as $permission)
                                    <div class="flex items-center space-x-2">
                                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-sm text-foreground">{{ $permission->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-muted-foreground mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <p class="text-muted-foreground">No permissions assigned to this role</p>
                </div>
            @endif
        </div>
    </div>
@endsection
